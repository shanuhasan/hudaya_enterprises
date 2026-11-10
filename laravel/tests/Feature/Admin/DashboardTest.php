<?php

namespace Tests\Feature\Admin;

use App\Enums\InquiryStatus;

class DashboardTest extends AdminTestCase
{
    public function test_dashboard_shows_statistics_and_recent_inquiries(): void
    {
        $this->makeInquiry();
        $this->makeInquiry(['name' => 'Ravi Shah', 'status' => InquiryStatus::Replied]);

        $this->get('/admin')
            ->assertOk()
            ->assertSeeInOrder(['Total products', '16', 'Total categories', '2', 'Total inquiries', '2', 'Pending inquiries', '1'])
            ->assertSee('Recent inquiries')
            ->assertSee('Asha Verma')
            ->assertSee('Ravi Shah')
            ->assertSee('8 featured');
    }

    public function test_dashboard_without_inquiries_shows_an_empty_state(): void
    {
        $this->get('/admin')->assertOk()->assertSee('No inquiries yet');
    }

    public function test_sidebar_shows_the_unread_count(): void
    {
        $this->makeInquiry();
        $this->makeInquiry();

        $this->get('/admin')->assertSee('<span class="adm-nav__count" title="2 new">2</span>', false);
    }
}
