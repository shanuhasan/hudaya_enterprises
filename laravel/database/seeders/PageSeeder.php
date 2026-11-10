<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/** SEO + hero content of the fixed pages, exactly as approved in Phase 1. */
class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'home' => [
                'meta_title' => 'Premium Lowers & Jackets Manufacturer',
                'meta_description' => 'Premium lowers, track pants and jackets manufactured for clothing brands, retailers, wholesalers and private label businesses. Request a quote.',
                'hero_eyebrow' => 'Garment Manufacturing Excellence',
                'hero_title' => 'Crafted with',
                'hero_title_em' => 'precision.',
                'hero_title_line2' => 'Built for excellence.',
                'hero_lead' => 'Premium lower and jacket manufacturing for brands, retailers and businesses.',
                'hero_image' => 'images/placeholders/hero/hero-main.jpg',
            ],
            'about' => [
                'meta_title' => 'About Us',
                'meta_description' => 'Hudaya Enterprises is a garment manufacturer focused on lowers, track pants and jackets for clothing brands, retailers and private label businesses.',
                'hero_eyebrow' => 'About Hudaya Enterprises',
                'hero_title' => 'Craft, care and',
                'hero_title_em' => 'consistency.',
                'hero_lead' => 'A garment manufacturer focused on lowers and jackets, working with brands, retailers and bulk buyers.',
                'hero_image' => 'images/placeholders/hero/about.jpg',
            ],
            'products' => [
                'meta_title' => 'Products',
                'meta_description' => 'Lowers, track pants and jackets manufactured to your design, fabric and branding requirements by Hudaya Enterprises.',
                'hero_eyebrow' => 'Our products',
                'hero_title' => 'Two categories,',
                'hero_title_em' => 'made to order.',
                'hero_lead' => 'Lowers and jackets manufactured to your design, fabric and branding requirements.',
                'hero_image' => 'images/placeholders/hero/products.jpg',
            ],
            'manufacturing' => [
                'meta_title' => 'Manufacturing',
                'meta_description' => 'The complete garment production workflow at Hudaya Enterprises, from design review and sampling to cutting, stitching, finishing, inspection and dispatch.',
                'hero_eyebrow' => 'Manufacturing',
                'hero_title' => 'From pattern',
                'hero_title_em' => 'to packed order.',
                'hero_lead' => 'Every stage of production is planned around one approved sample, so bulk orders match what you signed off.',
                'hero_image' => 'images/placeholders/hero/manufacturing.jpg',
            ],
            'quality' => [
                'meta_title' => 'Quality Assurance',
                'meta_description' => 'How Hudaya Enterprises inspects and finishes garments: fabric checks, inline inspection, measurements, finishing and final inspection before packing.',
                'hero_eyebrow' => 'Quality Assurance',
                'hero_title' => 'Checked at',
                'hero_title_em' => 'every stage.',
                'hero_lead' => 'Inspection and finishing are built into production, so what you approve is what you receive.',
                'hero_image' => 'images/placeholders/hero/quality.jpg',
            ],
            'gallery' => [
                'meta_title' => 'Gallery',
                'meta_description' => 'A visual look at lowers, jackets, garment details and production at Hudaya Enterprises.',
                'hero_eyebrow' => 'Gallery',
                'hero_title' => 'Inside the',
                'hero_title_em' => 'work.',
                'hero_lead' => 'Products, details and production — the garment types, finishes and processes we work with.',
                'hero_image' => 'images/placeholders/hero/gallery.jpg',
            ],
            'contact' => [
                'meta_title' => 'Contact & Request a Quote',
                'meta_description' => 'Request a quote from Hudaya Enterprises for lowers, track pants and jackets manufacturing. Share your design, quantities and timeline.',
                'hero_eyebrow' => 'Contact & Quote',
                'hero_title' => 'Start a',
                'hero_title_em' => 'conversation.',
                'hero_lead' => 'Tell us about your product, quantities and timeline. We will reply with next steps, sampling options and a quote.',
                'hero_image' => 'images/placeholders/hero/contact.jpg',
            ],
        ];

        foreach ($pages as $key => $values) {
            // Hero images are decorative (the headline carries the meaning), so alt stays empty.
            Page::query()->firstOrCreate(['key' => $key], $values + ['hero_image_alt' => '']);
        }
    }
}
