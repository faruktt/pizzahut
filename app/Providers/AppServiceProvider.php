<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share website branding (logo & favicon) with all frontend pages
        View::composer('layouts.app', function ($view) {
            $view->with('siteLogo', Setting::getValue('site_logo'));
            $view->with('siteFavicon', Setting::getValue('site_favicon'));
        });
    }
}
