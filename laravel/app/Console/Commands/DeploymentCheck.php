<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Post-deployment check. Run on the server after every deployment:
 *   php artisan hudaya:check
 * Prints PASS / WARN / FAIL per item and exits with an error code if anything FAILs.
 * It never prints passwords, keys or other secrets.
 */
class DeploymentCheck extends Command
{
    protected $signature = 'hudaya:check';

    protected $description = 'Check that this installation is configured correctly for production';

    private int $failures = 0;

    public function handle(SettingsService $settings): int
    {
        $this->line('Hudaya Enterprises — deployment check');
        $this->newLine();

        $this->check(app()->isProduction(), 'APP_ENV is production', 'APP_ENV is "'.app()->environment().'"');
        $this->check(! config('app.debug'), 'APP_DEBUG is off', 'APP_DEBUG is ON — visitors would see error details');
        $this->check(filled(config('app.key')), 'APP_KEY is set', 'APP_KEY is empty — run php artisan key:generate');
        $this->check(str_starts_with((string) config('app.url'), 'https://'), 'APP_URL uses https', 'APP_URL is not https ('.config('app.url').')', warnOnly: true);
        $this->check(config('session.secure') === true, 'Secure session cookies', 'SESSION_SECURE_COOKIE is not true', warnOnly: true);

        try {
            DB::connection()->getPdo();
            $this->check(true, 'Database connection ('.config('database.default').')');
            $pending = collect(Artisan::call('migrate:status', ['--pending' => true]) === 0 ? explode("\n", Artisan::output()) : [])
                ->filter(fn ($l) => str_contains($l, 'Pending'))->count();
            $this->check($pending === 0, 'All migrations have run', "$pending migration(s) pending — run php artisan migrate --force");
            $this->check(User::query()->where('is_admin', true)->exists(), 'An admin account exists', 'No admin — run php artisan admin:create');
            $this->check(filled($settings->get('email')), 'Company email is set (inquiry notifications)', 'Company email is empty — inquiries are saved but not emailed', warnOnly: true);
        } catch (Throwable $e) {
            $this->check(false, '', 'Database connection failed: '.$e->getMessage());
        }

        $publicLink = public_path('storage');
        $customRoot = env('PUBLIC_STORAGE_ROOT');
        // file_exists() also sees Windows junctions, which is_link()/is_dir() report as false.
        $this->check($customRoot ? is_dir($customRoot) : file_exists($publicLink),
            'Uploads are reachable from the web (public/storage)', 'public/storage is missing — run php artisan storage:link');

        try {
            $probe = 'deploy-check-'.uniqid().'.txt';
            Storage::disk('public')->put($probe, 'ok');
            Storage::disk('public')->delete($probe);
            $this->check(true, 'Upload folder is writable');
        } catch (Throwable) {
            $this->check(false, '', 'Upload folder is not writable — check permissions of storage/');
        }

        $this->check(is_writable(storage_path('logs')) && is_writable(storage_path('framework')), 'storage/logs and storage/framework are writable', 'storage/ is not writable by PHP');
        $this->check(is_writable(base_path('bootstrap/cache')), 'bootstrap/cache is writable', 'bootstrap/cache is not writable by PHP');
        $this->check(is_file(public_path('build/manifest.json')), 'Front-end build present (public/build)', 'public/build/manifest.json missing — run npm run build and upload public/build');
        $this->check(app()->configurationIsCached(), 'Configuration is cached', 'Config not cached — run php artisan optimize', warnOnly: true);
        $this->check(config('mail.default') !== 'log', 'Mail is configured ('.config('mail.default').')', 'MAIL_MAILER is "log" — emails are only written to the log', warnOnly: true);
        $this->check(! is_file(public_path('.env')) && ! is_file(public_path('../public_html/.env')), '.env is not inside the web root', '.env found inside the web root — move it out immediately');

        $this->newLine();
        if ($this->failures) {
            $this->error("{$this->failures} problem(s) must be fixed before going live.");

            return self::FAILURE;
        }
        $this->info('No blocking problems found. Now test the live site in a browser (see docs/DEPLOYMENT.md §C).');

        return self::SUCCESS;
    }

    private function check(bool $ok, string $pass, string $fail = '', bool $warnOnly = false): void
    {
        if ($ok) {
            $this->line("  <fg=green>PASS</>  $pass");
        } elseif ($warnOnly) {
            $this->line("  <fg=yellow>WARN</>  $fail");
        } else {
            $this->failures++;
            $this->line("  <fg=red>FAIL</>  $fail");
        }
    }
}
