<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\StockMouvement;
use App\Models\Product;
use Illuminate\Http\Request;

class StockMouvementController extends Controller
{
    //
    public function index(Request $request){
        $query = StockMouvement::with(['source','product','user'])->latest();
         if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('sens')) {
            $query->where('sens', $request->sens);
        }
        if ($request->filled('du')) {
            $query->whereDate('created_at', '>=', $request->du);
        }
        if ($request->filled('au')) {
            $query->whereDate('created_at', '<=', $request->au);
        }
        $mouvements = $query->paginate(10)->withQueryString();
        $produits = Product::orderBy('designation')->get(['id','designation']);

        return view('admin.stock-mouvements.index', compact('mouvements', 'produits'));
    }
}
