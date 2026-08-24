<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockAjustementController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function create(){
        $produits = Product::orderBy('designation')->get(['id', 'designation', 'reference_prod', 'qte_dispo']);
        return view('admin.stock-ajustements.create', compact('produits'));
    }

    public function store(Request $request){
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:ajustement,perte_casse',
            'sens' => 'required_if:type,ajustement|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $sens = $request->type === 'perte_casse' ? 'sortie' : $request->sens;

        if ($sens === 'entree') {
            $this->stockService->entreeStock(
                product: $product,
                quantite: $request->quantite,
                type: $request->type,
                source: null,
                notes: $request->notes,
            );
        } else {
            $this->stockService->sortieStock(
                product: $product,
                quantite: $request->quantite,
                type: $request->type,
                source: null,
                notes: $request->notes,
            );
        }

        return redirect()->route('admin.stock-mouvements.index')
            ->with('success', 'Ajustement de stock enregistré.');
    }
}