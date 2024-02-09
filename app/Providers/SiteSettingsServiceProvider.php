<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;

class SiteSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        view()->composer('*', function ($view) {
            $siteSettings = SiteSetting::first() ?? new SiteSetting();
            $socialMedias = SiteSetting::first()->social_media->toArray();
            $view->with([
                'siteSettings' => $siteSettings,
                'socialMedias' => $socialMedias
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
