<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Database performance guard: every page must stay within a small, fixed number of queries
 * no matter how many products / images there are (no N+1). Lazy loading also throws in tests.
 */
class QueryBudgetTest extends TestCase
{
    use RefreshDatabase;

    private const PUBLIC_BUDGET = 8;

    private const ADMIN_BUDGET = 14;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(DatabaseSeeder::class);
    }

    private function queriesFor(string $uri): int
    {
        $this->app->forgetInstance('site');
        DB::flushQueryLog();
        DB::enableQueryLog();
        $this->get($uri)->assertOk();
        $count = count(DB::getQueryLog());
        DB::disableQueryLog();

        return $count;
    }

    public function test_public_pages_stay_within_the_query_budget(): void
    {
        $this->queriesFor('/'); // warm the settings cache, as in production
        $report = [];
        foreach (['/', '/about', '/products', '/products/lowers', '/products/jackets', '/manufacturing', '/quality', '/gallery', '/contact', '/sitemap.xml'] as $uri) {
            $report[$uri] = $this->queriesFor($uri);
            $this->assertLessThanOrEqual(self::PUBLIC_BUDGET, $report[$uri], "$uri used {$report[$uri]} queries");
        }
        fwrite(STDERR, "\nqueries per public page: ".json_encode($report)."\n");
    }

    public function test_admin_pages_stay_within_the_query_budget(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $report = [];
        foreach (['/admin', '/admin/products', '/admin/products/1/edit', '/admin/product-images', '/admin/categories', '/admin/categories/1/edit', '/admin/gallery', '/admin/inquiries', '/admin/settings'] as $uri) {
            $report[$uri] = $this->queriesFor($uri);
            $this->assertLessThanOrEqual(self::ADMIN_BUDGET, $report[$uri], "$uri used {$report[$uri]} queries");
        }
        fwrite(STDERR, 'queries per admin page: '.json_encode($report)."\n");
    }
}
