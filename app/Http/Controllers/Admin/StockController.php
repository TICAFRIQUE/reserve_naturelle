<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request){
        $query = ProductVariant::with('product');

        if ($request->boolean('sous_seuil')) {
            $query->whereColumn('qte_dispo', '<=', 'stock_minimum');
        }

        $variants = $query->orderBy('reference_prod')->get();

        $stats = [
            'pieces_stock'   => $variants->sum('qte_dispo'),
            'valeur_stock'   => $variants->sum(fn ($v) => $v->qte_dispo * $v->cmp),
            'produits_sous_seuil' => $variants->filter(fn ($v) => $v->qte_dispo <= $v->stock_minimum)->count(),
        ];

        return view('admin.stock.index', compact('variants', 'stats'));
    }
}