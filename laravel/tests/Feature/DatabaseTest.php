<?php

namespace Tests\Feature;

use App\Enums\BuyerType;
use App\Enums\GalleryGroup;
use App\Enums\InquiryCategory;
use App\Enums\InquiryStatus;
use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_seeders_load_the_approved_content(): void
    {
        $this->assertDatabaseCount('settings', 19);
        $this->assertDatabaseCount('pages', 7);
        $this->assertSame(['lowers', 'jackets'], Category::query()->ordered()->pluck('slug')->all());
        $this->assertDatabaseCount('product_types', 12);
        $this->assertDatabaseCount('products', 16);
        $this->assertDatabaseCount('gallery_items', 18);
    }

    public function test_seeding_twice_does_not_duplicate_or_overwrite(): void
    {
        Setting::query()->where('key', 'email')->update(['value' => 'sales@example.com']);

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('products', 16);
        $this->assertDatabaseCount('gallery_items', 18);
        $this->assertSame('sales@example.com', Setting::query()->where('key', 'email')->value('value'));
    }

    public function test_company_details_stay_empty_until_supplied(): void
    {
        foreach (['phone', 'phone_href', 'email', 'whatsapp_number', 'address', 'hours', 'certifications'] as $key) {
            $this->assertSame('', (string) Setting::query()->where('key', $key)->value('value'), "$key should be empty");
        }
    }

    public function test_products_carry_their_filter_keys_and_featured_order(): void
    {
        $product = Product::query()->where('slug', 'mens-slim-track-pant')->with('types')->firstOrFail();
        $this->assertSame('mens sports', $product->filterKeys());

        $featured = Product::query()->active()->featured()->pluck('name')->all();
        $this->assertCount(8, $featured);
        $this->assertSame("Men's Slim Track Pant", $featured[0]);
        $this->assertSame('Bomber Jacket', $featured[7]);
    }

    public function test_category_has_types_products_and_specs(): void
    {
        $lowers = Category::query()->where('slug', 'lowers')->firstOrFail();

        $this->assertSame(['mens', 'womens', 'joggers', 'sports', 'casual', 'custom'], $lowers->types->pluck('slug')->all());
        $this->assertCount(8, $lowers->products);
        $this->assertSame('Fabric', $lowers->specs[0]['label']);
        $this->assertSame('01', $lowers->indexLabel());
    }

    public function test_gallery_home_mosaic_and_groups(): void
    {
        $home = GalleryItem::query()->active()->onHome()->get();

        $this->assertSame([1, 2, 3, 4, 5], $home->pluck('home_position')->all());
        $this->assertSame('Stitching', $home->first()->short_caption);
        $this->assertInstanceOf(GalleryGroup::class, $home->first()->group);
    }

    public function test_setting_changes_reach_the_site_immediately(): void
    {
        $this->get('/')->assertDontSee('wa.me', false);

        Setting::query()->where('key', 'whatsapp_number')->firstOrFail()->update(['value' => '919800000000']);
        $this->app->forgetInstance('site');

        $this->get('/')->assertSee('https://wa.me/919800000000?text=', false);
    }

    public function test_inquiry_casts_and_status(): void
    {
        $inquiry = Inquiry::query()->create([
            'name' => 'Test Buyer',
            'company' => 'Test Brand',
            'email' => 'buyer@example.com',
            'phone' => '+91 98000 00000',
            'buyer_type' => 'private-label',
            'category' => 'lowers',
            'quantity' => '500 pieces',
            'customisation' => ['Custom fabric'],
            'message' => 'Looking for joggers in our own fabric.',
        ]);

        $inquiry->refresh();
        $this->assertSame(BuyerType::PrivateLabel, $inquiry->buyer_type);
        $this->assertSame(InquiryCategory::Lowers, $inquiry->category);
        $this->assertSame(InquiryStatus::New, $inquiry->status);
        $this->assertSame(['Custom fabric'], $inquiry->customisation);

        $inquiry->markAsRead();
        $this->assertSame(InquiryStatus::Read, $inquiry->fresh()->status);
        $this->assertNotNull($inquiry->fresh()->read_at);
    }
}
