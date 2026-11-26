<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Categories, product types and example styles — the approved Phase 1 content.
 * Re-running is safe: rows are matched by slug and left alone if they already exist.
 *
 * Note: in the static site the Home "Featured styles" cards were separate copies with
 * their own names (e.g. "Classic Track Pant"). Each used the same photo as one of the
 * category-page styles, so here they are that same product, marked featured.
 */
class CatalogueSeeder extends Seeder
{
    private const IMG = 'images/placeholders/';

    private const QUOTE_TEXT = 'Share your style, fabric preferences and estimated quantities, and we will respond with sampling options and a quote.';

    public function run(): void
    {
        $this->seedCategory(
            [
                'name' => 'Lowers',
                'slug' => 'lowers',
                'title' => 'Lowers &',
                'title_em' => 'Track Pants',
                'summary' => 'Track pants, joggers, sports and casual lowers for men and women — made in the fabric, fit and finish your brand needs.',
                'highlights' => ['Track pants', 'Joggers', 'Sports lowers', 'Casual lowers'],
                'card_image' => self::IMG.'lowers/category-lowers.jpg',
                'card_image_alt' => 'Black track pants photographed in a studio',
                'hero_eyebrow' => 'Collection 01',
                'hero_title' => 'Lowers &',
                'hero_title_em' => 'Track Pants.',
                'hero_lead' => "Men's and women's lowers, joggers, sports and casual track pants, made to your specification.",
                'hero_image' => self::IMG.'hero/lowers.jpg',
                'intro_title' => 'Comfort, fit and',
                'intro_title_em' => 'durability.',
                'intro_text' => 'From performance track pants to relaxed casual lowers, each style is developed around how it will be worn — and built to hold its shape wash after wash.',
                'intro_image' => self::IMG.'details/waistband-detail.jpg',
                'intro_image_alt' => 'Close-up of an elasticated waistband with drawcord',
                'intro_image_position' => 'right',
                'types_title' => 'Six ways to',
                'types_title_em' => 'wear it.',
                'specs' => [
                    ['label' => 'Fabric', 'value' => 'Type, composition and weight'],
                    ['label' => 'Waistband', 'value' => 'Elastic, drawcord or both'],
                    ['label' => 'Pockets', 'value' => 'Side, zip or back pockets'],
                    ['label' => 'Hem', 'value' => 'Open, ribbed cuff or zip hem'],
                    ['label' => 'Panels', 'value' => 'Side panels, stripes and piping'],
                    ['label' => 'Branding', 'value' => 'Labels, tags and logo placement'],
                    ['label' => 'Sizing', 'value' => 'Your size chart and grading'],
                ],
                'cta_eyebrow' => 'Lowers manufacturing',
                'cta_title' => 'Request a quote',
                'cta_title_em' => 'for lowers.',
                'cta_text' => self::QUOTE_TEXT,
                'meta_title' => 'Lowers & Track Pants',
                'meta_description' => "Men's and women's lowers, joggers, sports and casual track pants and custom lowers, manufactured to order by Hudaya Enterprises.",
                'sort_order' => 1,
            ],
            types: [
                ['mens', "Men's Lowers", "Men's", "Track pants, joggers and lowers cut for men's fits and sizing.", 'type-mens.jpg', "Men's black lower"],
                ['womens', "Women's Lowers", "Women's", "Lowers and track pants developed for women's fits, rises and lengths.", 'type-womens.jpg', "Women's black lower"],
                ['joggers', 'Joggers', 'Joggers', 'Tapered legs with cuffed hems for everyday and athleisure wear.', 'type-joggers.jpg', 'Jogger with ribbed cuffs'],
                ['sports', 'Sports Track Pants', 'Sports', 'Lightweight, freedom-of-movement styles for training and sport.', 'type-sports.jpg', 'Sports track pants in motion'],
                ['casual', 'Casual Track Pants', 'Casual', 'Relaxed fits and soft fabrics for lounge and casual wear.', 'type-casual.jpg', 'Relaxed casual track pants'],
                ['custom', 'Custom Lowers', 'Custom', 'Your own design, fabric and branding, developed from a sample.', 'type-custom.jpg', 'Lower with a custom woven label'],
            ],
            // name, type label, type slugs, image, alt, featured position
            products: [
                ["Men's Slim Track Pant", "Men's · Sports", ['mens', 'sports'], 'classic-track-pant.jpg', "Men's slim track pant", 1],
                ["Women's Flared Track Pant", "Women's · Casual", ['womens', 'casual'], 'womens-flared.jpg', "Women's flared track pant", null],
                ['Cuffed Jogger', 'Joggers', ['joggers', 'mens'], 'tapered-jogger.jpg', 'Jogger with ribbed cuffs', 2],
                ["Women's Jogger", "Women's · Joggers", ['joggers', 'womens'], 'womens-jogger.jpg', "Women's jogger", null],
                ['Performance Track Pant', 'Sports', ['sports', 'mens'], 'sports-lower.jpg', 'Lightweight performance track pant', 5],
                ["Women's Training Lower", "Women's · Sports", ['sports', 'womens'], 'womens-training.jpg', "Women's training lower", null],
                ['Relaxed Lounge Lower', 'Casual', ['casual', 'mens'], 'casual-lower.jpg', 'Relaxed lounge lower', 7],
                ['Private Label Lower', 'Custom', ['custom'], 'type-custom.jpg', 'Lower with a custom woven brand label', null],
            ],
        );

        $this->seedCategory(
            [
                'name' => 'Jackets',
                'slug' => 'jackets',
                'title' => 'Jackets',
                'title_em' => null,
                'summary' => 'Sports, track, winter and fashion jackets for men and women — developed from your design and produced to your specification.',
                'highlights' => ['Sports', 'Track', 'Winter', 'Fashion'],
                'card_image' => self::IMG.'jackets/category-jackets.jpg',
                'card_image_alt' => 'Black track jacket on a dark background',
                'hero_eyebrow' => 'Collection 02',
                'hero_title' => 'Jackets,',
                'hero_title_em' => 'built to last.',
                'hero_lead' => "Men's and women's sports, track and winter jackets, made to your specification.",
                'hero_image' => self::IMG.'hero/jackets.jpg',
                'intro_title' => 'Structure, warmth and',
                'intro_title_em' => 'finish.',
                'intro_text' => 'Jackets demand precise construction — clean zips, balanced panels and neat finishing. Each style is sampled and approved before bulk production.',
                'intro_image' => self::IMG.'details/zip-detail.jpg',
                'intro_image_alt' => 'Close-up of a jacket zip and pull',
                'intro_image_position' => 'left',
                'types_title' => 'A jacket for every',
                'types_title_em' => 'season.',
                'specs' => [
                    ['label' => 'Fabric', 'value' => 'Shell fabric type, composition and weight'],
                    ['label' => 'Closure', 'value' => 'Full zip, half zip or press studs'],
                    ['label' => 'Collar & hood', 'value' => 'Stand collar, hood or collarless'],
                    ['label' => 'Lining', 'value' => 'Unlined, mesh, fleece or padded'],
                    ['label' => 'Pockets', 'value' => 'Side, zip, chest or inner pockets'],
                    ['label' => 'Cuffs & hem', 'value' => 'Ribbed, elasticated or adjustable'],
                    ['label' => 'Branding', 'value' => 'Labels, tags and logo placement'],
                ],
                'cta_eyebrow' => 'Jacket manufacturing',
                'cta_title' => 'Request a quote',
                'cta_title_em' => 'for jackets.',
                'cta_text' => self::QUOTE_TEXT,
                'meta_title' => 'Jackets',
                'meta_description' => "Men's and women's jackets, sports, track and winter jackets and custom jackets, manufactured to order by Hudaya Enterprises.",
                'sort_order' => 2,
            ],
            types: [
                ['mens', "Men's Jackets", "Men's", "Track, sports and winter jackets cut for men's fits and sizing.", 'type-mens.jpg', "Men's black jacket"],
                ['womens', "Women's Jackets", "Women's", "Jackets developed for women's fits, lengths and proportions.", 'type-womens.jpg', "Women's black jacket"],
                ['sports', 'Sports Jackets', 'Sports', 'Lightweight training jackets and windcheaters for active use.', 'type-sports.jpg', 'Lightweight sports jacket'],
                ['track', 'Track Jackets', 'Track', 'Full-zip track tops with collars, cuffs and panel details.', 'type-track.jpg', 'Full-zip track jacket'],
                ['winter', 'Winter Jackets', 'Winter', 'Padded, quilted and fleece-lined jackets for colder months.', 'type-winter.jpg', 'Quilted winter jacket'],
                ['custom', 'Custom Jackets', 'Custom', 'Your own design, fabric and branding, developed from a sample.', 'type-custom.jpg', 'Jacket with a custom brand label'],
            ],
            products: [
                ["Men's Full-Zip Track Jacket", "Men's · Track", ['mens', 'track'], 'zip-track-jacket.jpg', "Men's full-zip track jacket", 3],
                ["Women's Cropped Track Jacket", "Women's · Track", ['womens', 'track'], 'womens-cropped.jpg', "Women's cropped track jacket", null],
                ['Sports Windcheater', 'Sports', ['sports', 'mens', 'womens'], 'windcheater.jpg', 'Lightweight sports windcheater', 6],
                ['Training Jacket', 'Sports', ['sports', 'mens'], 'training-jacket.jpg', 'Lightweight training jacket', null],
                ['Quilted Puffer Jacket', 'Winter', ['winter', 'mens'], 'quilted-winter-jacket.jpg', 'Quilted puffer jacket', 4],
                ["Women's Padded Jacket", "Women's · Winter", ['winter', 'womens'], 'womens-padded.jpg', "Women's padded jacket", null],
                ['Bomber Jacket', "Men's · Fashion", ['mens'], 'bomber-jacket.jpg', 'Black bomber jacket', 8],
                ['Private Label Jacket', 'Custom', ['custom'], 'type-custom.jpg', 'Jacket with a custom brand label', null],
            ],
        );
    }

    private function seedCategory(array $attributes, array $types, array $products): void
    {
        $category = Category::query()->firstOrCreate(['slug' => $attributes['slug']], $attributes);
        $folder = self::IMG.$category->slug.'/';

        $typeIds = [];
        foreach ($types as $i => [$slug, $name, $short, $description, $image, $alt]) {
            $type = $category->types()->firstOrCreate(['slug' => $slug], [
                'name' => $name,
                'short_name' => $short,
                'description' => $description,
                'image' => $folder.$image,
                'image_alt' => $alt,
                'sort_order' => $i + 1,
            ]);
            $typeIds[$slug] = $type->id;
        }

        foreach ($products as $i => [$name, $typeLabel, $typeSlugs, $image, $alt, $featured]) {
            $slug = Str::slug(str_replace("'", '', $name));

            if (Product::withTrashed()->where('slug', $slug)->exists()) {
                continue;
            }

            $product = $category->products()->create([
                'name' => $name,
                'slug' => $slug,
                'type_label' => $typeLabel,
                'image' => $folder.$image,
                'image_alt' => $alt,
                'featured_position' => $featured,
                'sort_order' => $i + 1,
            ]);

            $product->types()->sync(array_map(fn ($s) => $typeIds[$s], $typeSlugs));
        }
    }
}
