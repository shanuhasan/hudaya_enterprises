<?php

namespace Tests\Feature\Admin;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;

class InquiryTest extends AdminTestCase
{
    public function test_index_lists_filters_searches_and_paginates(): void
    {
        $this->makeInquiry();
        $this->makeInquiry(['name' => 'Ravi Shah', 'company' => 'Shah Sports', 'category' => 'jackets', 'status' => InquiryStatus::Replied]);
        for ($i = 0; $i < 20; $i++) {
            $this->makeInquiry(['name' => "Buyer $i", 'status' => InquiryStatus::Archived]);
        }

        $this->get('/admin/inquiries')->assertOk()->assertSee('Showing 1–20 of 22');
        $this->get('/admin/inquiries?status=replied')->assertOk()->assertSee('Ravi Shah')->assertDontSee('Asha Verma');
        $this->get('/admin/inquiries?category=jackets')->assertOk()->assertSee('Ravi Shah')->assertDontSee('Asha Verma');
        $this->get('/admin/inquiries?q=shah+sports')->assertOk()->assertSee('Ravi Shah')->assertDontSee('Asha Verma')->assertDontSee('Buyer 1');
    }

    public function test_empty_state(): void
    {
        $this->get('/admin/inquiries')->assertOk()->assertSee('No inquiries yet');
    }

    public function test_viewing_an_inquiry_shows_details_and_marks_it_read(): void
    {
        $inquiry = $this->makeInquiry();

        $this->get("/admin/inquiries/{$inquiry->id}")
            ->assertOk()
            ->assertSee('North Label')
            ->assertSee('600 pieces')
            ->assertSee('Custom fabric')
            ->assertSee('We need joggers in our own fleece')
            ->assertSee('mailto:asha@example.com', false)
            ->assertSee('https://wa.me/919800000000', false);

        $this->assertSame(InquiryStatus::Read, $inquiry->fresh()->status);
        $this->assertNotNull($inquiry->fresh()->read_at);
    }

    public function test_update_status_and_notes(): void
    {
        $inquiry = $this->makeInquiry();

        $this->put("/admin/inquiries/{$inquiry->id}", ['status' => 'replied', 'admin_notes' => 'Sent sampling options by email.'])
            ->assertSessionHas('status', 'Inquiry updated.');
        $this->assertSame(InquiryStatus::Replied, $inquiry->fresh()->status);
        $this->assertSame('Sent sampling options by email.', $inquiry->fresh()->admin_notes);

        $this->put("/admin/inquiries/{$inquiry->id}", ['status' => 'spam'])->assertSessionHasErrors('status');
    }

    public function test_message_text_is_escaped(): void
    {
        $inquiry = $this->makeInquiry(['message' => '<script>alert(1)</script> please quote']);

        $this->get("/admin/inquiries/{$inquiry->id}")->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_delete_inquiry(): void
    {
        $inquiry = $this->makeInquiry();

        $this->delete("/admin/inquiries/{$inquiry->id}")->assertRedirect('/admin/inquiries');
        $this->assertNull(Inquiry::query()->find($inquiry->id));
    }
}
