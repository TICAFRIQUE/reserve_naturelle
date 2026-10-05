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
            $input = $request->input('email');
            $email = is_string($input) ? Str::transliterate(Str::lower($input)) : '';

            return [
                // 5 essais/min par couple email+IP, et plafond par IP contre la rotation d'emails
                // (nécessite TrustProxies correct derrière un proxy, sinon tous les visiteurs partagent l'IP)
                Limit::perMinute(5)->by($email . '|' . $request->ip()),
                Limit::perMinute(30)->by($request->ip()),
            ];
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