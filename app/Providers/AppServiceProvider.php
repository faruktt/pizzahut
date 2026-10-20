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
        // Share website branding, address, map, categories and pages with all frontend pages
        View::composer(['layouts.app', 'auth.*'], function ($view) {
            $view->with('siteLogo', Setting::getValue('site_logo'));
            $view->with('siteFavicon', Setting::getValue('site_favicon'));
            $view->with('siteAddress', Setting::getValue('site_address', 'House 14, Road 11, Block D, Gulshan-2, Dhaka'));
            $view->with('sitePhone', Setting::getValue('site_phone', '+880 1711-223344'));
            $view->with('siteEmail', Setting::getValue('site_email', 'contact@italianpizza.com'));
            $view->with('openingHours', Setting::getValue('opening_hours', 'Daily: 11:00 AM – 11:30 PM'));
            $view->with('googleMapEmbed', Setting::getValue('google_map_embed'));
            $view->with('footerCategories', \App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(6)->get());
            $view->with('footerPages', \App\Models\Page::where('is_active', true)->orderBy('sort_order')->get());
        });
    }
}
