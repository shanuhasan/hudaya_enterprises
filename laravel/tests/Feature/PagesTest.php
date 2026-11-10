<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PagesTest extends TestCase
{
    public static function pages(): array
    {
        return [
            'home' => ['/', 'Crafted with'],
            'about' => ['/about', 'Craft, care and'],
            'products' => ['/products', 'Products'],
            'lowers' => ['/products/lowers', 'Lowers &amp; Track Pants'],
            'jackets' => ['/products/jackets', 'Jackets'],
            'manufacturing' => ['/manufacturing', 'Manufacturing'],
            'quality' => ['/quality', 'Quality'],
            'gallery' => ['/gallery', 'Gallery'],
            'contact' => ['/contact', 'Contact'],
        ];
    }

    #[DataProvider('pages')]
    public function test_page_renders(string $uri, string $text): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee($text, false)
            ->assertSee('<h1', false)
            ->assertDontSee('.html"', false);
    }

    public function test_unknown_category_is_404(): void
    {
        $this->get('/products/shoes')->assertNotFound();
    }

    public function test_unset_company_details_are_not_rendered_as_links(): void
    {
        config(['site.whatsapp_number' => '', 'site.email' => '', 'site.phone_href' => '']);

        $this->get('/contact')
            ->assertOk()
            ->assertDontSee('wa.me', false)
            ->assertDontSee('href="tel:', false)
            ->assertDontSee('href="mailto:', false)
            ->assertSee('[Email address]');
    }

    public function test_whatsapp_buttons_appear_once_a_number_is_set(): void
    {
        config(['site.whatsapp_number' => '919800000000']);

        $this->get('/')
            ->assertOk()
            ->assertSee('https://wa.me/919800000000?text=', false)
            ->assertSee('fab--whatsapp', false);
    }
}
