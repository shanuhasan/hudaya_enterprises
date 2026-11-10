<?php

namespace App\Providers;

use App\Services\SettingsService;
use App\View\Composers\SiteComposer;
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
    }
}
