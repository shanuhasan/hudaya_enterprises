<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\UploadedFile;

class CategoryTest extends AdminTestCase
{
    private function payload(array $overrides = []): array
    {
        return $overrides + [
            'name' => 'Hoodies',
            'slug' => '',
            'sort_order' => 3,
            'is_active' => '1',
            'title' => 'Hoodies',
            'title_em' => '& Sweatshirts',
            'summary' => 'Pullover and zip hoodies.',
            'highlights_text' => "Pullover\nZip-through\n\n",
            'card_image_file' => UploadedFile::fake()->image('card.jpg', 900, 1100),
            'hero_title' => 'Hoodies,',
            'hero_title_em' => 'made to order.',
            'intro_title' => 'Warmth and',
            'intro_title_em' => 'structure.',
            'intro_image_position' => 'left',
            'types_title' => 'Styles to',
            'types_title_em' => 'choose from.',
            'specs' => [['label' => 'Fabric', 'value' => 'Fleece or loopback'], ['label' => '', 'value' => '']],
            'meta_title' => 'Hoodies',
            'meta_description' => 'Hoodies manufactured to order.',
        ];
    }

    public function test_index_and_forms_render(): void
    {
        $lowers = Category::query()->where('slug', 'lowers')->firstOrFail();

        $this->get('/admin/categories')->assertOk()->assertSee('Lowers')->assertSee('/products/lowers');
        $this->get('/admin/categories/create')->assertOk()->assertSee('Add category');
        $this->get("/admin/categories/{$lowers->id}/edit")->assertOk()
            ->assertSee('Comfort, fit and')->assertSee('Waistband')->assertSee("Men's Lowers")->assertSee('Add type');
    }

    public function test_create_category_with_highlights_and_specs(): void
    {
        $this->post('/admin/categories', $this->payload())->assertSessionHasNoErrors();

        $category = Category::query()->where('slug', 'hoodies')->firstOrFail();
        $this->assertSame(['Pullover', 'Zip-through'], $category->highlights);
        $this->assertSame([['label' => 'Fabric', 'value' => 'Fleece or loopback']], $category->specs);
        $this->assertStringStartsWith('storage/categories/', $category->card_image);
        $this->assertSame('left', $category->intro_image_position);
    }

    public function test_category_validation(): void
    {
        $this->post('/admin/categories', $this->payload([
            'slug' => 'lowers',
            'hero_title' => '',
            'intro_image_position' => 'top',
            'meta_description' => str_repeat('x', 200),
            'specs' => [['label' => 'Fabric', 'value' => '']],
        ]))->assertSessionHasErrors(['slug', 'hero_title', 'intro_image_position', 'meta_description', 'specs.0.value']);
    }

    public function test_update_category(): void
    {
        $lowers = Category::query()->where('slug', 'lowers')->firstOrFail();
        $data = array_merge($lowers->only(array_keys($this->payload())), [
            'highlights_text' => 'Track pants',
            'specs' => [['label' => 'Fabric', 'value' => 'Any']],
            'intro_title' => 'Comfort above all',
            'is_active' => '1',
        ]);
        unset($data['card_image_file']);

        $this->put("/admin/categories/{$lowers->id}", $data)->assertRedirect("/admin/categories/{$lowers->id}/edit");

        $this->assertSame('Comfort above all', $lowers->fresh()->intro_title);
        $this->assertSame(['Track pants'], $lowers->fresh()->highlights);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $lowers = Category::query()->where('slug', 'lowers')->firstOrFail();

        $this->delete("/admin/categories/{$lowers->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $lowers->id]);
    }

    public function test_empty_category_can_be_deleted_with_its_types(): void
    {
        $this->post('/admin/categories', $this->payload());
        $category = Category::query()->where('slug', 'hoodies')->firstOrFail();
        $category->types()->create(['name' => 'Zip hoodies', 'short_name' => 'Zip', 'slug' => 'zip']);

        $this->delete("/admin/categories/{$category->id}")->assertRedirect('/admin/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('product_types', ['category_id' => $category->id]);
    }

    public function test_product_types_crud(): void
    {
        $jackets = Category::query()->where('slug', 'jackets')->firstOrFail();

        $this->get("/admin/categories/{$jackets->id}/types/create")->assertOk()->assertSee('Filter tab label');

        $this->post("/admin/categories/{$jackets->id}/types", [
            'name' => 'Varsity Jackets', 'short_name' => 'Varsity', 'slug' => '', 'description' => 'Wool body, contrast sleeves.',
            'image_file' => UploadedFile::fake()->image('v.jpg', 800, 1000), 'sort_order' => 7, 'is_active' => '1',
        ])->assertRedirect("/admin/categories/{$jackets->id}/edit");

        $type = ProductType::query()->where('category_id', $jackets->id)->where('slug', 'varsity')->firstOrFail();
        $this->assertStringStartsWith('storage/types/', $type->image);

        // Same filter key twice in one category is rejected.
        $this->post("/admin/categories/{$jackets->id}/types", ['name' => 'X', 'short_name' => 'Varsity', 'sort_order' => 8])
            ->assertSessionHasErrors('slug');

        $this->get("/admin/types/{$type->id}/edit")->assertOk()->assertSee('Varsity Jackets');
        $this->put("/admin/types/{$type->id}", ['name' => 'Varsity & Letterman', 'short_name' => 'Varsity', 'slug' => 'varsity', 'sort_order' => 7, 'is_active' => '0'])
            ->assertRedirect("/admin/categories/{$jackets->id}/edit");
        $this->assertSame('Varsity & Letterman', $type->fresh()->name);
        $this->assertFalse($type->fresh()->is_active);

        $this->delete("/admin/types/{$type->id}")->assertRedirect("/admin/categories/{$jackets->id}/edit");
        $this->assertDatabaseMissing('product_types', ['id' => $type->id]);
    }

    public function test_deleting_a_type_keeps_its_products(): void
    {
        $type = ProductType::query()->where('slug', 'joggers')->firstOrFail();
        $count = Product::query()->count();

        $this->delete("/admin/types/{$type->id}");

        $this->assertSame($count, Product::query()->count());
        $this->assertDatabaseMissing('product_product_type', ['product_type_id' => $type->id]);
    }
}
