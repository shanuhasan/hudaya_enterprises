<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use App\Services\SettingsService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(DatabaseSeeder::class);
    }

    public static function pages(): array
    {
        return [
            'home' => ['/', 'Crafted with'],
            'about' => ['/about', 'Craft, care and'],
            'products' => ['/products', 'Two categories,'],
            'lowers' => ['/products/lowers', 'Lowers &amp;'],
            'jackets' => ['/products/jackets', 'built to last.'],
            'manufacturing' => ['/manufacturing', 'From pattern'],
            'quality' => ['/quality', 'Checked at'],
            'gallery' => ['/gallery', 'Inside the'],
            'contact' => ['/contact', 'Start a'],
        ];
    }

    #[DataProvider('pages')]
    public function test_page_renders(string $uri, string $text): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee($text, false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('application/ld+json', false)
            ->assertDontSee('.html"', false);
    }

    public function test_every_page_has_exactly_one_h1(): void
    {
        foreach (self::pages() as [$uri]) {
            $this->assertSame(1, substr_count($this->get($uri)->getContent(), '<h1'), $uri);
        }
    }

    public function test_unknown_and_inactive_categories_are_404(): void
    {
        $this->get('/products/shoes')->assertNotFound();

        Category::query()->where('slug', 'jackets')->update(['is_active' => false]);
        $this->get('/products/jackets')->assertNotFound();
        $this->get('/')->assertDontSee('/products/jackets', false);
    }

    public function test_old_static_urls_redirect_permanently(): void
    {
        $this->get('/index.html')->assertStatus(301)->assertRedirect('/');
        $this->get('/lowers.html')->assertStatus(301)->assertRedirect('/products/lowers');
        $this->get('/contact.html')->assertStatus(301)->assertRedirect('/contact');
    }

    public function test_sitemap_lists_pages_and_active_categories(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>'.url('/products/lowers').'</loc>', false)
            ->assertSee('<loc>'.url('/contact').'</loc>', false)
            ->assertDontSee('/admin', false);
    }

    public function test_robots_blocks_everything_outside_production(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');

        $this->app['env'] = 'production';
        $this->get('/robots.txt')->assertSee('Disallow: /admin')->assertSee('Sitemap: '.url('/sitemap.xml'));
    }

    public function test_unset_company_details_are_not_rendered_as_links(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertDontSee('wa.me', false)
            ->assertDontSee('href="tel:', false)
            ->assertDontSee('href="mailto:', false)
            ->assertSee('[Email address]');
    }

    public function test_whatsapp_links_use_the_number_and_encoded_message(): void
    {
        Setting::query()->where('key', 'whatsapp_number')->update(['value' => '919800000000']);
        SettingsService::flush();

        $this->get('/')
            ->assertOk()
            ->assertSee('https://wa.me/919800000000?text=Hello%20Hudaya%20Enterprises%2C%20I%20would%20like', false)
            ->assertSee('fab--whatsapp', false);
        $this->get('/contact')->assertDontSee('fab--whatsapp', false); // contact page has its own button
    }

    public function test_admin_content_changes_appear_on_the_website(): void
    {
        Page::query()->where('key', 'about')->update(['hero_title' => 'Made with care by', 'meta_title' => 'About the company']);
        Product::query()->where('slug', 'bomber-jacket')->update(['name' => 'Classic Bomber']);
        Product::query()->where('slug', 'training-jacket')->update(['is_active' => false]);
        GalleryItem::query()->where('caption', 'Fabric texture')->update(['is_active' => false]);

        $this->get('/about')->assertSee('Made with care by')->assertSee('<title>About the company | Hudaya Enterprises</title>', false);
        $this->get('/products/jackets')->assertSee('Classic Bomber')->assertDontSee('Training Jacket');
        $this->get('/')->assertSee('Classic Bomber');
        $this->get('/gallery')->assertDontSee('Fabric texture');
    }

    public function test_featured_products_on_home_and_products(): void
    {
        $home = $this->get('/')->getContent();
        $this->assertSame(8, substr_count($home, 'class="product-card"'));

        $products = $this->get('/products')->getContent();
        $this->assertSame(4, substr_count($products, 'class="product-card"'));
    }

    public function test_category_page_filter_keys_come_from_product_types(): void
    {
        $this->get('/products/lowers')
            ->assertSee('data-category="mens sports"', false)
            ->assertSee('data-filter="joggers"', false)
            ->assertSee('Waistband');
    }
}
