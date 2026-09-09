<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\View;

class ShareCartPendingOrder
{
    public function handle(Request $request, Closure $next){
        
        if (auth()->check() && session()->has('panier_converti_order_id')) {
            $pendingOrder = Order::find(session('panier_converti_order_id'));

            if ($pendingOrder && $pendingOrder->statut === 'panier_converti') {
                View::share('pendingOrder', $pendingOrder);
            } else {
                session()->forget('panier_converti_order_id');
            }
        }

        return $next($request);
    }
}
