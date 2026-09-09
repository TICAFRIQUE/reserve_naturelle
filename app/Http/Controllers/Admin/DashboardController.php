<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Achat;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMouvement;

class DashboardController extends Controller
{
    public function index(Request $request){
        $hasFilter = $request->filled('date_from') || $request->filled('date_to');

        $dateFrom = $hasFilter && $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->startOfMonth();

        $dateTo = $hasFilter && $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : ($hasFilter ? today()->endOfDay() : now()->endOfMonth());

        $ordersQuery = Order::whereBetween('created_at', [$dateFrom, $dateTo]);

        $products = Product::with('category')->get();
        $lowStock   = $products->filter(fn ($p) => $p->qte_dispo > 0 && $p->qte_dispo <= $p->stock_minimum);
        $outOfStock = $products->where('qte_dispo', '<=', 0);

        $stats = [
            'products'      => $products->count(),
            'orders_period' => (clone $ordersQuery)->count(),
            'revenue'       => (clone $ordersQuery)->where('statut', 'livree')->sum('montant_ttc'),
            'expenses'      => Achat::whereBetween('date_achat', [$dateFrom, $dateTo])->sum('mt_total'),
            'current_stock' => $products->sum('qte_dispo'),
            'stock_value'   => $products->sum(fn ($p) => $p->qte_dispo * $p->cmp),
            'entries'       => StockMouvement::where('sens', 'entree')->whereMonth('created_at', now()->month)->sum('quantite'),
            'exits'         => StockMouvement::where('sens', 'sortie')->whereMonth('created_at', now()->month)->sum('quantite'),
            'available'     => $products->count() - $lowStock->count() - $outOfStock->count(),
            'low_stock'     => $lowStock->count(),
            'out_of_stock'  => $outOfStock->count(),
        ];

        $revenueChart = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'label' => $date->format('d/m'),
                'value' => Order::whereDate('created_at', $date)->where('statut', 'livree')->sum('montant_ttc'),
            ];
        });

        $recent_orders = (clone $ordersQuery)->with('user')->latest()->limit(5)->get();
        $alertesStock = $products->filter(fn ($p) => $p->qte_dispo > 0 && $p->qte_dispo <= $p->stock_minimum)
            ->sortBy('qte_dispo')->take(10);
        $productsPreview = $products->sortByDesc('created_at')->take(10);

        return view('admin.dashboard', compact('stats', 'recent_orders', 'alertesStock', 'revenueChart'))
            ->with('products', $productsPreview)
            ->with('dateFrom', $dateFrom->format('Y-m-d'))
            ->with('dateTo', $dateTo->format('Y-m-d'))
            ->with('hasFilter', $hasFilter);
    }
}