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
            $siteSetting = SiteSetting::first();
            $socialMedias = [];
        
            if ($siteSetting !== null) {
                $socialMedias = $siteSetting->social_media->toArray();
            } else {
                $siteSetting = new SiteSetting();
            }
        
            $view->with([
                'siteSettings' => $siteSetting,
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
