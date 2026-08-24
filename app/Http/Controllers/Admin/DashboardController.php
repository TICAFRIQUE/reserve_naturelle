<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMouvement;

class DashboardController extends Controller
{
    public function index(){
        $products = Product::with('category')->get();

        $lowStock   = $products->filter(fn ($p) => $p->qte_dispo > 0 && $p->qte_dispo <= $p->stock_minimum);
        $outOfStock = $products->where('qte_dispo', '<=', 0);

        $stats = [
            'products'      => $products->count(),
            'orders_today'  => Order::whereDate('created_at', today())->count(),
            'revenue'       => Order::where('statut', 'livre')->sum('montant_ttc'),
            'expenses'      => 0, // à brancher sur les achats si besoin plus tard

            'current_stock' => $products->sum('qte_dispo'),
            'stock_value'   => $products->sum(fn ($p) => $p->qte_dispo * $p->cmp),
            'entries'       => StockMouvement::where('sens', 'entree')
                ->whereMonth('created_at', now()->month)->sum('quantite'),
            'exits'         => StockMouvement::where('sens', 'sortie')
                ->whereMonth('created_at', now()->month)->sum('quantite'),

            'available'     => $products->count() - $lowStock->count() - $outOfStock->count(),
            'low_stock'     => $lowStock->count(),
            'out_of_stock'  => $outOfStock->count(),
        ];

        $recent_orders = Order::with('user')->latest()->limit(5)->get();

        // Produits sous le seuil, pour l'encart "Alertes stock"
        $alertesStock = $products
            ->filter(fn ($p) => $p->sous_seuil)
            ->sortBy('qte_dispo')
            ->take(10);

        // Aperçu limité pour le tableau "Inventaire Produits" du dashboard
        $productsPreview = $products->sortByDesc('created_at')->take(10);

        return view('admin.dashboard', compact('stats', 'recent_orders', 'alertesStock'))
            ->with('products', $productsPreview);
    }
}