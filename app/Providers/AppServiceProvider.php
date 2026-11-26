<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\CartService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Models\{Fournisseur, Achat, Category, SousCategory, Product, Order, User, Livreur, Reservation};

class AppServiceProvider extends ServiceProvider
{
    public function register(): void{
        $this->app->singleton(CartService::class);
    }

    public function boot(): void{
        Paginator::useBootstrapFive();

        Gate::before(fn ($user) => $user->hasRole('admin') ? true : null);

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
            $u = auth()->user();
            $n = fn (string $perm, callable $count) => $u?->can($perm) ? $count() : 0;

            $view->with('sidebarCounts', [
                'fournisseurs'   => $n('fournisseurs.voir',     fn () => Fournisseur::count()),
                'achats'         => $n('achats.voir',           fn () => Achat::count()),
                'categories'     => $n('categories.voir',       fn () => Category::count()),
                'sousCategories' => $n('sous-categories.voir',  fn () => SousCategory::count()),
                'produits'       => $n('produits.voir',         fn () => Product::count()),
                'orders'         => $n('orders.voir',           fn () => Cache::remember('sidebar_orders_count', 60, fn () =>
                                        Order::where('statut', '!=', 'panier_converti')->count())),
                'users'          => $n('users.voir',            fn () => User::count()),
                'livreurs'       => $n('livreurs.voir',         fn () => Livreur::count()),
                'reservations'   => $n('reservations.voir',     fn () => Reservation::count()),
            ]);
        });
    }
}