<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request){
        $query = Product::query();

        if ($request->boolean('sous_seuil')) {
            $query->whereColumn('qte_dispo', '<=', 'stock_minimum');
        }

        $products = $query->orderBy('designation')->get();

        $stats = [
            'pieces_stock'   => $products->sum('qte_dispo'),
            'valeur_stock'   => $products->sum(fn ($p) => $p->qte_dispo * $p->cmp),
            'produits_sous_seuil' => $products->filter(fn ($p) => $p->qte_dispo <= $p->stock_minimum)->count(),
        ];

        return view('admin.stock.index', compact('products', 'stats'));
    }
}