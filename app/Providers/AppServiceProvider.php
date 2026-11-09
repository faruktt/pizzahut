<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
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
        // Set default application timezone to Bangladesh (Asia/Dhaka)
        date_default_timezone_set(config('app.timezone', 'Asia/Dhaka'));

        // Use custom clean pagination view for all admin and system links()
        Paginator::defaultView('vendor.pagination.admin');
        Paginator::defaultSimpleView('vendor.pagination.admin');

        // Share website branding, address, map, categories and pages with all frontend pages
        View::composer(['layouts.app', 'auth.*'], function ($view) {
            $siteLogo = Setting::getValue('site_logo');
            $siteFavicon = Setting::getValue('site_favicon');
            $siteName = Setting::getValue('site_name', 'Italian Pizza Hut');

            $ogBanner = \App\Models\Banner::where('is_active', true)->orderBy('sort_order')->first();
            $defaultOgImage = $ogBanner && $ogBanner->image
                ? asset('uploads/' . $ogBanner->image)
                : (!empty($siteLogo) ? asset('uploads/' . $siteLogo) : asset('uploads/brand/1792057294_logo-2.png'));

            $view->with('siteName', $siteName);
            $view->with('siteLogo', $siteLogo);
            $view->with('siteFavicon', $siteFavicon);
            $view->with('defaultOgImage', $defaultOgImage);
            $view->with('siteAddress', Setting::getValue('site_address', 'House 14, Road 11, Block D, Gulshan-2, Dhaka'));
            $view->with('sitePhone', Setting::getValue('site_phone', '+880 1711-223344'));
            $view->with('siteEmail', Setting::getValue('site_email', 'contact@italianpizzahut01@gmail.com'));
            $view->with('openingHours', Setting::getValue('opening_hours', 'Daily: 11:00 AM – 11:30 PM'));
            $view->with('googleMapEmbed', Setting::getValue('google_map_embed'));
            $view->with('footerCategories', \App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(6)->get());
            $view->with('footerPages', \App\Models\Page::where('is_active', true)->orderBy('sort_order')->get());
        });
    }
}
