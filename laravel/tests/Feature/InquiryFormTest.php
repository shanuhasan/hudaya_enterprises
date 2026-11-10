<?php

namespace Tests\Feature;

use App\Enums\InquiryStatus;
use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Models\Setting;
use App\Services\SettingsService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquiryFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(DatabaseSeeder::class);
        Mail::fake();
    }

    private function valid(array $overrides = []): array
    {
        return $overrides + [
            'name' => 'Asha Verma',
            'company' => 'North Label',
            'email' => 'Asha@Example.com',
            'phone' => '+91 98000 00000',
            'country' => 'India',
            'city' => 'Ludhiana',
            'buyer_type' => 'private-label',
            'category' => 'lowers',
            'product' => 'Cuffed Jogger',
            'quantity' => '600 pieces',
            'timeline' => '1-3-months',
            'customisation' => ['Custom fabric', 'Private label / branding'],
            'message' => 'We need joggers in our own fleece with woven labels.',
            'consent' => '1',
            'website' => '',
        ];
    }

    public function test_form_renders_with_csrf_and_prefill(): void
    {
        $this->get('/contact?product=Cuffed%20Jogger&category=jackets')
            ->assertOk()
            ->assertSee('action="'.route('inquiries.store').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('value="Cuffed Jogger"', false)
            ->assertSee('<option value="jackets" selected', false);
    }

    public function test_valid_inquiry_is_saved_and_thanked(): void
    {
        $this->from('/contact')->post('/contact', $this->valid())
            ->assertRedirect(route('contact').'#quote')
            ->assertSessionHas('inquiry_sent');

        $inquiry = Inquiry::query()->sole();
        $this->assertSame('asha@example.com', $inquiry->email);
        $this->assertSame(InquiryStatus::New, $inquiry->status);
        $this->assertSame(['Custom fabric', 'Private label / branding'], $inquiry->customisation);
        $this->assertSame('127.0.0.1', $inquiry->ip_address);

        $this->get('/contact')->assertSee('Thank you — your inquiry has been sent.');
    }

    public function test_notification_email_goes_to_the_company_address_when_set(): void
    {
        $this->post('/contact', $this->valid());
        Mail::assertNothingSent(); // no company email set yet

        Setting::query()->where('key', 'email')->update(['value' => 'sales@example.com']);
        SettingsService::flush();
        $this->app->forgetInstance('site');

        $this->post('/contact', $this->valid(['name' => 'Second Buyer']));
        Mail::assertSent(InquiryReceived::class, fn ($mail) => $mail->hasTo('sales@example.com') && $mail->hasReplyTo('asha@example.com'));
    }

    public function test_mail_failure_does_not_lose_the_inquiry(): void
    {
        Setting::query()->where('key', 'email')->update(['value' => 'sales@example.com']);
        SettingsService::flush();
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/contact', $this->valid())->assertSessionHas('inquiry_sent');
        $this->assertSame(1, Inquiry::query()->count());
    }

    public function test_invalid_input_is_rejected_with_messages_and_kept(): void
    {
        $this->from('/contact')->post('/contact', $this->valid([
            'name' => '',
            'email' => 'not-an-email',
            'phone' => 'call me',
            'buyer_type' => 'hacker',
            'category' => 'shoes',
            'timeline' => 'yesterday',
            'customisation' => ['Free samples'],
            'message' => 'too short',
            'consent' => null,
        ]))->assertRedirect('/contact')
            ->assertSessionHasErrors(['name', 'email', 'phone', 'buyer_type', 'category', 'timeline', 'customisation.0', 'message', 'consent'])
            ->assertSessionHasInput('company', 'North Label');

        $this->assertSame(0, Inquiry::query()->count());

        $this->get('/contact')->assertSee('Please enter your name.')->assertSee('aria-invalid="true"', false);
    }

    public function test_honeypot_submissions_are_silently_dropped(): void
    {
        $this->post('/contact', $this->valid(['website' => 'https://spam.example']))->assertSessionHas('inquiry_sent');
        $this->assertSame(0, Inquiry::query()->count());
    }

    public function test_rate_limit_after_three_per_minute(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/contact', $this->valid())->assertSessionHas('inquiry_sent');
        }

        $this->post('/contact', $this->valid())->assertSessionHasErrors('form');
        $this->assertSame(3, Inquiry::query()->count());
    }
}
