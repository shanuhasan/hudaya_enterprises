<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsAndProfileTest extends AdminTestCase
{
    private function settings(array $overrides = []): array
    {
        return $overrides + Setting::query()->pluck('value', 'key')->map(fn ($v) => (string) $v)->except('logo')->all();
    }

    public function test_settings_page_renders_with_current_values(): void
    {
        $this->get('/admin/settings')->assertOk()
            ->assertSee('Company settings')->assertSee('Hudaya Enterprises')->assertSee('WhatsApp number')->assertSee('Social links');
    }

    public function test_contact_details_are_saved_and_appear_on_the_website(): void
    {
        $this->put('/admin/settings', $this->settings([
            'phone' => '+91 98000 00000',
            'phone_href' => '+919800000000',
            'email' => 'sales@example.com',
            'whatsapp_number' => '919800000000',
            'address' => 'Unit 4, Example Road, Ludhiana, Punjab 141001',
            'instagram' => 'https://instagram.com/example',
        ]))->assertRedirect('/admin/settings')->assertSessionHas('status');

        $this->assertSame('sales@example.com', Setting::query()->where('key', 'email')->value('value'));

        $this->app->forgetInstance('site');
        $this->get('/contact')->assertOk()
            ->assertSee('href="tel:+919800000000"', false)
            ->assertSee('href="mailto:sales@example.com"', false)
            ->assertSee('Unit 4, Example Road')
            ->assertSee('https://instagram.com/example', false);
        $this->get('/')->assertSee('https://wa.me/919800000000?text=', false);
    }

    public function test_settings_validation(): void
    {
        $this->put('/admin/settings', $this->settings([
            'name' => '',
            'email' => 'not-an-email',
            'whatsapp_number' => '+91 98000',
            'phone_href' => '98000 00000',
            'facebook' => 'http://facebook.com/x',
        ]))->assertSessionHasErrors(['name', 'email', 'whatsapp_number', 'phone_href', 'facebook']);

        $this->assertSame('Hudaya Enterprises', Setting::query()->where('key', 'name')->value('value'));
    }

    public function test_logo_upload_and_removal(): void
    {
        $this->put('/admin/settings', $this->settings(['logo_file' => UploadedFile::fake()->image('logo.png', 600, 200)]))
            ->assertSessionHasNoErrors();

        $logo = Setting::query()->where('key', 'logo')->value('value');
        $this->assertStringStartsWith('storage/branding/', $logo);
        Storage::disk('public')->assertExists(substr($logo, 8));

        $this->app->forgetInstance('site');
        $this->get('/')->assertSee(asset($logo), false);

        $this->put('/admin/settings', $this->settings(['remove_logo' => '1']))->assertSessionHasNoErrors();
        $this->assertSame('', Setting::query()->where('key', 'logo')->value('value'));
        Storage::disk('public')->assertMissing(substr($logo, 8));
    }

    public function test_profile_details_update(): void
    {
        $this->get('/admin/profile')->assertOk()->assertSee('Change password');

        $this->put('/admin/profile', ['name' => 'Owner Two', 'email' => 'OWNER2@example.com'])->assertSessionHas('status');
        $this->assertSame('owner2@example.com', $this->admin->fresh()->email);
        $this->assertSame('Owner Two', $this->admin->fresh()->name);
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $this->admin->update(['password' => 'old-password-1']);

        $this->put('/admin/profile/password', ['current_password' => 'wrong', 'password' => 'new-password-2', 'password_confirmation' => 'new-password-2'])
            ->assertSessionHasErrors('current_password');
        $this->put('/admin/profile/password', ['current_password' => 'old-password-1', 'password' => 'short', 'password_confirmation' => 'short'])
            ->assertSessionHasErrors('password');

        $this->put('/admin/profile/password', ['current_password' => 'old-password-1', 'password' => 'new-password-2', 'password_confirmation' => 'new-password-2'])
            ->assertSessionHasNoErrors()->assertSessionHas('status');
        $this->assertTrue(Hash::check('new-password-2', $this->admin->fresh()->password));
    }
}
