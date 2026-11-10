<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductTest extends AdminTestCase
{
    private function payload(array $overrides = []): array
    {
        $lowers = Category::query()->where('slug', 'lowers')->firstOrFail();

        return $overrides + [
            'name' => "Women's Wide-Leg Lower",
            'slug' => '',
            'category_id' => $lowers->id,
            'type_label' => "Women's · Casual",
            'types' => $lowers->types()->whereIn('slug', ['womens', 'casual'])->pluck('id')->all(),
            'description' => 'Relaxed wide-leg lower with an elastic waistband.',
            'material' => 'Cotton fleece',
            'sizes_text' => 'XS, S, M, L, XL, M',
            'moq' => '300 pieces per colour',
            'image' => UploadedFile::fake()->image('front.jpg', 1200, 1500),
            'image_alt' => "Women's wide-leg lower",
            'gallery' => [UploadedFile::fake()->image('back.jpg', 900, 1100), UploadedFile::fake()->image('detail.png', 800, 800)],
            'is_active' => '1',
            'is_featured' => '0',
            'sort_order' => '9',
        ];
    }

    public function test_index_lists_searches_filters_and_paginates(): void
    {
        $this->get('/admin/products')->assertOk()->assertSee("Men's Slim Track Pant")->assertSee('Showing 1–15 of 16');
        $this->get('/admin/products?page=2')->assertOk()->assertSee('Showing 16–16 of 16');

        $this->get('/admin/products?q=jogger')->assertOk()->assertSee('Cuffed Jogger')->assertDontSee('Bomber Jacket');

        $jackets = Category::query()->where('slug', 'jackets')->value('id');
        $this->get("/admin/products?category=$jackets")->assertOk()->assertSee('Bomber Jacket')->assertDontSee('Cuffed Jogger');
        $this->get('/admin/products?status=featured')->assertOk()->assertSee('Featured #1')->assertDontSee('Private Label Lower');
    }

    public function test_create_form_renders(): void
    {
        $this->get('/admin/products/create')->assertOk()->assertSee('Add product')->assertSee('Minimum order quantity')->assertSee('Available sizes');
    }

    public function test_admin_can_create_a_product_with_images_types_and_sizes(): void
    {
        $response = $this->post('/admin/products', $this->payload());

        $product = Product::query()->where('slug', 'womens-wide-leg-lower')->firstOrFail();
        $response->assertRedirect("/admin/products/{$product->id}/edit")->assertSessionHas('status');

        $this->assertSame(['XS', 'S', 'M', 'L', 'XL'], $product->sizes);
        $this->assertSame('300 pieces per colour', $product->moq);
        $this->assertSame('Cotton fleece', $product->material);
        $this->assertSame('womens casual', $product->load('types')->filterKeys());
        $this->assertNull($product->featured_position);

        $this->assertStringStartsWith('storage/products/', $product->image);
        Storage::disk('public')->assertExists(substr($product->image, 8));
        Storage::disk('public')->assertExists(str_replace('.jpg', '@800.jpg', substr($product->image, 8)));
        $this->assertCount(2, $product->images);
        $product->images->each(fn ($image) => Storage::disk('public')->assertExists(substr($image->path, 8)));
    }

    public function test_validation_errors_are_reported(): void
    {
        $jacketType = Category::query()->where('slug', 'jackets')->firstOrFail()->types()->value('id');

        $this->from('/admin/products/create')
            ->post('/admin/products', $this->payload([
                'name' => '',
                'slug' => 'mens-slim-track-pant', // already used
                'types' => [$jacketType],          // belongs to another category
                'image' => UploadedFile::fake()->create('notes.pdf', 50, 'application/pdf'),
            ]))
            ->assertRedirect('/admin/products/create')
            ->assertSessionHasErrors(['name', 'slug', 'types.0', 'image']);

        $this->post('/admin/products', $this->payload(['image' => UploadedFile::fake()->image('tiny.jpg', 100, 100)]))
            ->assertSessionHasErrors(['image']);

        $this->assertDatabaseMissing('products', ['material' => 'Cotton fleece']);
    }

    public function test_an_active_product_needs_a_featured_image(): void
    {
        $this->post('/admin/products', $this->payload(['image' => null]))->assertSessionHasErrors('image');
        $this->post('/admin/products', $this->payload(['image' => null, 'is_active' => '0', 'gallery' => []]))->assertSessionHasNoErrors();
    }

    public function test_admin_can_update_feature_and_replace_the_image(): void
    {
        $this->post('/admin/products', $this->payload());
        $product = Product::query()->where('slug', 'womens-wide-leg-lower')->firstOrFail();
        $oldImage = $product->image;

        $this->get("/admin/products/{$product->id}/edit")->assertOk()->assertSee('Cotton fleece')->assertSee('XS, S, M, L, XL');

        $this->put("/admin/products/{$product->id}", $this->payload([
            'name' => "Women's Wide-Leg Lower",
            'slug' => 'womens-wide-leg-lower',
            'material' => 'Brushed fleece',
            'is_featured' => '1',
            'gallery' => [],
            'image' => UploadedFile::fake()->image('new.jpg', 1000, 1200),
        ]))->assertRedirect("/admin/products/{$product->id}/edit");

        $product->refresh();
        $this->assertSame('Brushed fleece', $product->material);
        $this->assertSame(9, $product->featured_position); // after the 8 seeded featured products
        $this->assertNotSame($oldImage, $product->image);
        Storage::disk('public')->assertMissing(substr($oldImage, 8));

        $this->put("/admin/products/{$product->id}", $this->payload(['slug' => 'womens-wide-leg-lower', 'is_featured' => '0', 'is_active' => '0', 'image' => null, 'gallery' => []]));
        $this->assertNull($product->fresh()->featured_position);
        $this->assertFalse($product->fresh()->is_active);
    }

    public function test_admin_can_delete_a_product_and_its_files(): void
    {
        $this->post('/admin/products', $this->payload());
        $product = Product::query()->where('slug', 'womens-wide-leg-lower')->with('images')->firstOrFail();
        $files = array_merge([$product->image], $product->images->pluck('path')->all());

        $this->delete("/admin/products/{$product->id}")->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_images', ['product_id' => $product->id]);
        foreach ($files as $file) {
            Storage::disk('public')->assertMissing(substr($file, 8));
        }
    }

    public function test_deleting_a_seeded_product_never_touches_bundled_photos(): void
    {
        $product = Product::query()->where('slug', 'bomber-jacket')->firstOrFail();

        $this->delete("/admin/products/{$product->id}")->assertRedirect('/admin/products');

        $this->assertFileExists(public_path($product->image));
    }
}
