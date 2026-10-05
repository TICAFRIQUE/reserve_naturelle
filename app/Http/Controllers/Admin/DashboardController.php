<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Achat;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
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

        // Le stock vit sur les VARIANTES (products.qte_dispo / cmp ne sont plus mis à jour)
        $variants = ProductVariant::with('product.category')->get();   // tout le stock physique
        $actives  = $variants->where('actif', true);                   // ce qui est vendable

        $lowStock   = $actives->filter(fn ($v) => $v->qte_dispo > 0 && $v->qte_dispo <= $v->stock_minimum);
        $outOfStock = $actives->filter(fn ($v) => $v->qte_dispo <= 0);

        $stats = [
            'products'      => Product::count(),
            'orders_period' => (clone $ordersQuery)->count(),
            'revenue'       => (clone $ordersQuery)->where('statut', 'livree')->sum('montant_ttc'),
            'expenses'      => Achat::whereBetween('date_achat', [$dateFrom, $dateTo])->sum('mt_total'),
            'current_stock' => $variants->sum('qte_dispo'),
            'stock_value'   => $variants->sum(fn ($v) => $v->qte_dispo * $v->cmp),
            'entries'       => StockMouvement::where('sens', 'entree')->whereMonth('created_at', now()->month)->sum('quantite'),
            'exits'         => StockMouvement::where('sens', 'sortie')->whereMonth('created_at', now()->month)->sum('quantite'),
            // Compteurs par variante active (même logique que la page Stock)
            'available'     => $actives->count() - $lowStock->count() - $outOfStock->count(),
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

        // Alertes : une ligne par variante sous son seuil (objets simples, mêmes champs que la vue lit)
        $alertesStock = $lowStock->sortBy('qte_dispo')->take(10)->map(fn ($v) => (object) [
            'id'             => $v->product_id,
            'designation'    => $v->libelle,                 // "Produit (conditionnement)"
            'reference_prod' => $v->reference_prod,
            'qte_dispo'      => $v->qte_dispo,
            'stock_minimum'  => $v->stock_minimum,
        ])->values();

        // Produits récents : stock total et prix mini des variantes actives
        $productsPreview = Product::with(['category', 'variants'])->latest()->take(10)->get()
            ->map(fn ($p) => (object) [
                'designation'   => $p->designation,
                'category'      => $p->category,
                'qte_dispo'     => (int) $p->variants->sum('qte_dispo'),
                'prix_vente'    => (int) ($p->variants->min('prix_vente') ?? 0),
                'stock_minimum' => (int) $p->variants->sum('stock_minimum'),
            ]);

        return view('admin.dashboard', compact('stats', 'recent_orders', 'alertesStock', 'revenueChart'))
            ->with('products', $productsPreview)
            ->with('dateFrom', $dateFrom->format('Y-m-d'))
            ->with('dateTo', $dateTo->format('Y-m-d'))
            ->with('hasFilter', $hasFilter);
    }
}