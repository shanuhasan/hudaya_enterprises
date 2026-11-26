<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

/**
 * Gallery images as approved in Phase 1 (stock placeholders, see public/images/placeholders/CREDITS.md).
 * Captions describe the subject only — never "our factory".
 * Re-running is safe: items are matched by image path.
 */
class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $img = 'images/placeholders/';

        // caption, group, aspect, image, alt, [home position, short caption]
        $items = [
            ['Classic track pant', 'lowers', '4/5', 'lowers/classic-track-pant.jpg', 'Black track pant photographed in a studio'],
            ['Stitching on industrial machines', 'manufacturing', '4/3', 'manufacturing/stitching-line.jpg', 'Garments being stitched on industrial sewing machines', 1, 'Stitching'],
            ['Full-zip track jacket', 'jackets', '3/4', 'jackets/zip-track-jacket.jpg', 'Full-zip track jacket'],
            ['Waistband and drawcord', 'details', '1/1', 'details/waistband-detail.jpg', 'Close-up of an elasticated waistband with drawcord', 3, 'Waistband detail'],
            ['Fabric cutting', 'manufacturing', '4/5', 'manufacturing/fabric-cutting.jpg', 'Fabric being cut on a cutting table'],
            ['Tapered jogger', 'lowers', '3/4', 'lowers/tapered-jogger.jpg', 'Tapered jogger with ribbed cuffs'],
            ['Jacket zip detail', 'details', '4/3', 'details/zip-detail.jpg', 'Close-up of a jacket zip and pull', 2, 'Zip & trim detail'],
            ['Quilted winter jacket', 'jackets', '4/5', 'jackets/quilted-winter-jacket.jpg', 'Quilted winter jacket'],
            ['Pattern making', 'manufacturing', '1/1', 'manufacturing/pattern-making.jpg', 'Garment pattern pieces laid out on a table'],
            ['Stitching close-up', 'details', '3/4', 'details/stitching-closeup.jpg', 'Close-up of stitching along a black garment seam'],
            ['Sports lower', 'lowers', '4/3', 'lowers/sports-lower.jpg', 'Lightweight sports lower'],
            ['Pressing and finishing', 'manufacturing', '4/5', 'manufacturing/finishing.jpg', 'Garment being pressed during finishing', 4, 'Finishing'],
            ['Bomber jacket', 'jackets', '1/1', 'jackets/bomber-jacket.jpg', 'Black bomber jacket'],
            ['Fabric texture', 'details', '4/5', 'details/fabric-texture.jpg', 'Folded black fabric showing its texture'],
            ['Quality inspection', 'manufacturing', '3/4', 'quality/inspection.jpg', 'Garment being measured during quality inspection'],
            ['Sports windcheater', 'jackets', '4/3', 'jackets/windcheater.jpg', 'Lightweight sports windcheater'],
            ['Ready for packing', 'manufacturing', '4/5', 'jackets/jacket-detail.jpg', 'Folded jackets ready for packing', 5, 'Ready for dispatch'],
            ['Casual lower', 'lowers', '4/5', 'lowers/casual-lower.jpg', 'Relaxed casual lower'],
        ];

        foreach ($items as $i => $item) {
            [$caption, $group, $aspect, $image, $alt] = $item;

            GalleryItem::query()->firstOrCreate(['image' => $img.$image], [
                'caption' => $caption,
                'short_caption' => $item[6] ?? null,
                'group' => $group,
                'aspect' => $aspect,
                'image_alt' => $alt,
                'home_position' => $item[5] ?? null,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
