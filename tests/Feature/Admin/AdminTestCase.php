<?php

namespace Tests\Feature\Admin;

use App\Models\Inquiry;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Seeded database, faked public disk, and a signed-in admin. */
abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(DatabaseSeeder::class);
        Storage::fake('public');

        $this->admin = User::factory()->create(['is_admin' => true, 'name' => 'Owner', 'email' => 'owner@example.com']);
        $this->actingAs($this->admin);
    }

    protected function makeInquiry(array $overrides = []): Inquiry
    {
        return Inquiry::query()->create($overrides + [
            'name' => 'Asha Verma',
            'company' => 'North Label',
            'email' => 'asha@example.com',
            'phone' => '+91 98000 00000',
            'buyer_type' => 'brand',
            'category' => 'lowers',
            'product' => 'Cuffed Jogger',
            'quantity' => '600 pieces',
            'customisation' => ['Custom fabric'],
            'message' => 'We need joggers in our own fleece with woven labels.',
        ]);
    }
}
