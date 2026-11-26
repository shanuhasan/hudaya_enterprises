<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(DatabaseSeeder::class);
    }

    public static function protectedPages(): array
    {
        return array_map(fn ($u) => [$u], [
            '/admin', '/admin/products', '/admin/products/create', '/admin/categories', '/admin/product-images',
            '/admin/gallery', '/admin/inquiries', '/admin/settings', '/admin/profile',
        ]);
    }

    #[DataProvider('protectedPages')]
    public function test_guests_are_sent_to_the_login_page(string $uri): void
    {
        $this->get($uri)->assertRedirect('/admin/login');
    }

    #[DataProvider('protectedPages')]
    public function test_non_admin_users_are_signed_out(string $uri): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get($uri)
            ->assertRedirect('/admin/login');

        $this->assertGuest();
    }

    public function test_write_actions_are_protected_too(): void
    {
        $this->delete('/admin/products/1')->assertRedirect('/admin/login');
        $this->put('/admin/settings', ['name' => 'Hacked'])->assertRedirect('/admin/login');
        $this->assertDatabaseMissing('settings', ['key' => 'name', 'value' => 'Hacked']);
        $this->assertDatabaseHas('products', ['id' => 1]);
    }

    public function test_login_page_renders(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('Sign in')->assertSee('noindex', false);
    }

    public function test_admin_can_sign_in_and_out(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'email' => 'owner@example.com', 'password' => 'correct-horse-42']);

        $this->post('/admin/login', ['email' => 'owner@example.com', 'password' => 'correct-horse-42'])
            ->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->assertNotNull($admin->fresh()->last_login_at);

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_wrong_password_and_non_admin_accounts_are_rejected(): void
    {
        User::factory()->create(['is_admin' => true, 'email' => 'owner@example.com', 'password' => 'correct-horse-42']);
        User::factory()->create(['is_admin' => false, 'email' => 'staff@example.com', 'password' => 'correct-horse-42']);

        $this->from('/admin/login')->post('/admin/login', ['email' => 'owner@example.com', 'password' => 'wrong'])
            ->assertRedirect('/admin/login')->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => 'staff@example.com', 'password' => 'correct-horse-42'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_repeated_failures_lock_the_account_temporarily(): void
    {
        User::factory()->create(['is_admin' => true, 'email' => 'owner@example.com', 'password' => 'correct-horse-42']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'owner@example.com', 'password' => 'wrong']);
        }

        $this->post('/admin/login', ['email' => 'owner@example.com', 'password' => 'correct-horse-42'])
            ->assertSessionHasErrors('email');
        $this->assertStringStartsWith('Too many sign-in attempts.', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_signed_in_admin_skips_the_login_page(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/admin/login')
            ->assertRedirect('/admin');
    }

    public function test_create_admin_command(): void
    {
        $this->artisan('admin:create', ['email' => 'new@example.com', '--name' => 'New Admin', '--password' => 'long-enough-1'])
            ->assertSuccessful();

        $this->assertTrue(User::query()->where('email', 'new@example.com')->value('is_admin'));

        $this->artisan('admin:create', ['email' => 'x@example.com', '--name' => 'X', '--password' => 'short'])
            ->assertFailed();
    }
}
