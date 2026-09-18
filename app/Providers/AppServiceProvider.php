<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\CartService;
use App\Models\{Fournisseur, Achat, Category, SousCategory, Product, Order, User, Livreur};

class AppServiceProvider extends ServiceProvider
{
    public function register(): void{
        $this->app->singleton(CartService::class);
    }

    public function boot(): void{
        Paginator::useBootstrapFive();

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