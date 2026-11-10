<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Sends one plain test email to check the SMTP settings after deployment:
 *   php artisan hudaya:mail-test you@your-domain.com
 */
class MailTest extends Command
{
    protected $signature = 'hudaya:mail-test {to : Address that should receive the test email}';

    protected $description = 'Send a test email using the configured mail settings';

    public function handle(): int
    {
        $to = (string) $this->argument('to');

        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please give a valid email address.');

            return self::FAILURE;
        }

        try {
            Mail::raw(
                'This is a test email from '.config('app.name').' ('.config('app.url').'). If you can read this, inquiry notifications will be delivered.',
                fn ($message) => $message->to($to)->subject('Test email — '.config('app.name')),
            );
        } catch (Throwable $e) {
            $this->error('Sending failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Sent via "'.config('mail.default')."\" to $to. Check the inbox (and spam folder).");

        return self::SUCCESS;
    }
}
