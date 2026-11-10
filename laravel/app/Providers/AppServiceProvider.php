<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\SettingsService;
use App\View\Composers\SiteComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Company details: settings table (cached), with config/site.php as defaults for missing keys.
        $this->app->singleton('site', fn ($app) => SiteComposer::build(
            array_merge(config('site'), $app->make(SettingsService::class)->all())
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Company details ($site) for every view.
        View::composer('*', SiteComposer::class);
        // Footer product links: the active categories.
        View::composer('components.footer', fn (\Illuminate\View\View $view) => $view->with(
            'footerCategories',
            Category::query()->active()->ordered()->get(['id', 'slug', 'title', 'title_em']),
        ));

        // Quote form: 3 per minute and 20 per day per IP. Over the limit, the visitor sees a message instead of an error page.
        RateLimiter::for('inquiries', function (Request $request) {
            $tooMany = fn () => redirect()->to(route('contact').'#quote')->withInput()
                ->withErrors(['form' => 'You have sent several inquiries in a short time. Please wait a few minutes, or contact us by phone or WhatsApp.']);

            return [
                Limit::perMinute(3)->by('inq-min:'.$request->ip())->response($tooMany),
                Limit::perDay(20)->by('inq-day:'.$request->ip())->response($tooMany),
            ];
        });

        // HTTPS terminated by a proxy: trust it so URLs, secure cookies and visitor IPs are correct.
        if ($proxies = config('app.trusted_proxies')) {
            TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }

        // Production behind HTTPS: always generate https:// URLs.
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Catch N+1 queries during development and tests (never in production).
        Model::preventLazyLoading(! $this->app->isProduction());
    }
}
