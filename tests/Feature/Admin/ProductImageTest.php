<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductImageTest extends AdminTestCase
{
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::query()->where('slug', 'cuffed-jogger')->firstOrFail();
    }

    public function test_index_and_manage_pages_render(): void
    {
        $this->get('/admin/product-images')->assertOk()->assertSee('Cuffed Jogger')->assertSee('Manage');
        $this->get("/admin/products/{$this->product->id}/images")->assertOk()->assertSee('Featured image')->assertSee('No extra images yet.');
    }

    public function test_upload_several_images(): void
    {
        $this->post("/admin/products/{$this->product->id}/images", [
            'images' => [UploadedFile::fake()->image('a.jpg', 900, 1100), UploadedFile::fake()->image('b.webp', 900, 900)],
        ])->assertRedirect()->assertSessionHas('status', '2 images uploaded.');

        $images = $this->product->images()->get();
        $this->assertCount(2, $images);
        $this->assertSame([1, 2], $images->pluck('sort_order')->all());
        $images->each(fn ($i) => Storage::disk('public')->assertExists(substr($i->path, 8)));
    }

    public function test_upload_rejects_non_images_and_empty_submissions(): void
    {
        $this->post("/admin/products/{$this->product->id}/images", [])->assertSessionHasErrors('images');
        $this->post("/admin/products/{$this->product->id}/images", [
            'images' => [UploadedFile::fake()->create('script.php', 10, 'text/x-php')],
        ])->assertSessionHasErrors('images.0');

        $this->assertSame(0, $this->product->images()->count());
    }

    public function test_update_description_and_order(): void
    {
        $this->post("/admin/products/{$this->product->id}/images", ['images' => [UploadedFile::fake()->image('a.jpg', 900, 1100)]]);
        $image = $this->product->images()->firstOrFail();

        $this->put("/admin/product-images/{$image->id}", ['alt' => 'Ribbed cuff close-up', 'sort_order' => 5])
            ->assertSessionHas('status');

        $this->assertSame('Ribbed cuff close-up', $image->fresh()->alt);
        $this->assertSame(5, $image->fresh()->sort_order);
    }

    public function test_promote_an_image_to_featured(): void
    {
        $previous = $this->product->image; // bundled photo
        $this->post("/admin/products/{$this->product->id}/images", ['images' => [UploadedFile::fake()->image('a.jpg', 900, 1100)]]);
        $image = $this->product->images()->firstOrFail();

        $this->post("/admin/product-images/{$image->id}/feature")->assertSessionHas('status', 'Featured image updated.');

        $this->product->refresh();
        $this->assertSame($image->path, $this->product->image);
        $this->assertSame([$previous], $this->product->images()->pluck('path')->all()); // old featured kept as extra
    }

    public function test_delete_an_image_removes_the_file(): void
    {
        $this->post("/admin/products/{$this->product->id}/images", ['images' => [UploadedFile::fake()->image('a.jpg', 900, 1100)]]);
        $image = $this->product->images()->firstOrFail();

        $this->delete("/admin/product-images/{$image->id}")->assertSessionHas('status', 'Image deleted.');

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing(substr($image->path, 8));
    }
}
