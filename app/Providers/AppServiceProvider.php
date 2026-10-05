<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\CartService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\{Fournisseur, Achat, Category, SousCategory, Product, Order, User, Livreur};

class AppServiceProvider extends ServiceProvider
{
    public function register(): void{
        $this->app->singleton(CartService::class);
    }

    public function boot(): void{
        Paginator::useBootstrapFive();

          // Limitation des tentatives de connexion
        RateLimiter::for('login', function (Request $request) {
            $email = Str::transliterate(Str::lower($request->input('email')));
            return Limit::perMinute(5)->by($email . '|' . $request->ip());
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('sidebarCounts', [
                'fournisseurs'    => Fournisseur::count(),
                'achats'          => Achat::count(),
                'categories'      => Category::count(),
                'sousCategories'  => SousCategory::count(),
                'produits'        => Product::count(),
                'orders'          => Cache::remember('sidebar_orders_count', 60, fn () =>
                                Order::where('statut', '!=', 'panier_converti')->count()),
                'users'           => User::count(),
                'livreurs'        => Livreur::count(),
            ]);
        });
    }
}