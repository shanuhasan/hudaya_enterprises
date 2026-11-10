<?php

namespace Tests\Feature\Admin;

use App\Enums\GalleryGroup;
use App\Models\GalleryItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GalleryTest extends AdminTestCase
{
    private function payload(array $overrides = []): array
    {
        return $overrides + [
            'image_file' => UploadedFile::fake()->image('g.jpg', 1200, 900),
            'caption' => 'Hem finishing',
            'image_alt' => 'Close-up of a finished hem',
            'group' => 'details',
            'aspect' => '4/3',
            'sort_order' => 30,
            'home_position' => '',
            'short_caption' => '',
            'is_active' => '1',
        ];
    }

    public function test_index_filters_by_group_and_search(): void
    {
        $this->get('/admin/gallery')->assertOk()->assertSee('Classic track pant')->assertSee('Casual lower');
        $this->get('/admin/gallery?group=details')->assertOk()->assertSee('Fabric texture')->assertDontSee('Bomber jacket');
        $this->get('/admin/gallery?q=zip')->assertOk()->assertSee('Jacket zip detail')->assertDontSee('Fabric cutting');
    }

    public function test_create_requires_an_image_and_valid_options(): void
    {
        $this->get('/admin/gallery/create')->assertOk();

        $this->post('/admin/gallery', $this->payload(['image_file' => null, 'group' => 'shoes', 'aspect' => '2/1', 'home_position' => 1]))
            ->assertSessionHasErrors(['image_file', 'group', 'aspect', 'home_position']); // slot 1 is taken
    }

    public function test_create_update_and_delete(): void
    {
        $this->post('/admin/gallery', $this->payload())->assertRedirect('/admin/gallery');
        $item = GalleryItem::query()->where('caption', 'Hem finishing')->firstOrFail();
        $this->assertSame(GalleryGroup::Details, $item->group);
        Storage::disk('public')->assertExists(substr($item->image, 8));

        $this->get("/admin/gallery/{$item->id}/edit")->assertOk()->assertSee('Hem finishing');

        // Free slot 5, then give it to the new image.
        GalleryItem::query()->where('home_position', 5)->update(['home_position' => null]);
        $this->put("/admin/gallery/{$item->id}", $this->payload(['image_file' => null, 'caption' => 'Hem detail', 'home_position' => '5', 'short_caption' => 'Hems']))
            ->assertRedirect("/admin/gallery/{$item->id}/edit");
        $this->assertSame(5, $item->fresh()->home_position);
        $this->assertSame('Hem detail', $item->fresh()->caption);

        $this->delete("/admin/gallery/{$item->id}")->assertRedirect('/admin/gallery');
        $this->assertDatabaseMissing('gallery_items', ['id' => $item->id]);
        Storage::disk('public')->assertMissing(substr($item->image, 8));
    }
}
