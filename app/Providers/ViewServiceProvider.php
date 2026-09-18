<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Banner;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer([
            'client.products.index',
            'client.products.catalogue',
            'client.about',
            'contact',
        ], function ($view) {
            $bannerData = Cache::remember('banner.active', 3600, function () {
                return Banner::where('actif', true)->first()?->toArray();
            });

            $view->with('banner', $bannerData ? (object) $bannerData : null);
        });
    }
}