<?php

namespace App\Http\Middleware;

use App\Services\CartService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareCartCount
{
    public function __construct(private CartService $cartService) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Sécurité : ne pas exécuter sur les routes bots (au cas où)
        if ($request->is('sitemap.xml', 'robots.txt', 'favicon.ico')) {
            return $next($request);
        }

        View::share('cartCount', $this->cartService->getOrCreateCart()->items->sum('qte'));

        return $next($request);
    }
}