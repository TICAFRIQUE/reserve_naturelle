<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;


class ShareCartCount
{
    public function __construct(private CartService $cartService) {}

    public function handle(Request $request, Closure $next)
    {
        View::share('cartCount', $this->cartService->getOrCreateCart()->items->sum('qte'));
        return $next($request);
    }
}
