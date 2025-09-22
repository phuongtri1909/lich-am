<?php

namespace App\Providers;

use App\Models\Social;
use App\Models\MetaTag;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // Load assets paths
        $faviconPath = asset('images/logo/favicon.ico');
        $logoPath = asset('images/logo/logo-site.png');
        
        // Load social links (only active ones, ordered by sort_order)
        $socials = Social::active()->orderBy('sort_order')->get();
        
        // Load meta tags (only active ones, keyed by name for easy access)
        $metaTags = MetaTag::active()->get()->keyBy('name');
        
        // Share with all views
        view()->share('faviconPath', $faviconPath);
        view()->share('logoPath', $logoPath);
        view()->share('socials', $socials);
        view()->share('metaTags', $metaTags);
        
        // Share specific meta tags for easier access
        view()->share('headerMetaTags', $metaTags->get('header'));
        view()->share('footerMetaTags', $metaTags->get('footer'));
    }
}
