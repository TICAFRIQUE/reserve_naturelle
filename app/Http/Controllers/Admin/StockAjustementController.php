<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAjustementController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function create(){
        $variants = ProductVariant::where('actif', true)->orderBy('reference_prod')->get();
        return view('admin.stock-ajustements.create', compact('variants'));
    }

    public function store(Request $request){
        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'type' => 'required|in:ajustement,perte_casse',
            'sens' => 'required_if:type,ajustement|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($data) {

                // Verrouiller la variante pendant toute l'opération
                $variant = ProductVariant::whereKey($data['product_variant_id'])->lockForUpdate()->firstOrFail();
                $sens = $data['type'] === 'perte_casse'
                    ? 'sortie'
                    : $data['sens'];

                if ($sens === 'entree') {
                    $this->stockService->entreeStock(
                        variant: $variant,
                        quantite: $data['quantite'],
                        type: $data['type'],
                        source: null,
                        notes: $data['notes'],
                    );
                } else {
                    $this->stockService->sortieStock(
                        variant: $variant,
                        quantite: $data['quantite'],
                        type: $data['type'],
                        source: null,
                        notes: $data['notes'],
                    );
                }
            });
            return redirect()->route('admin.stock-mouvements.index')->with('success', 'Ajustement de stock enregistré.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['quantite' => $e->getMessage()]);
        }
    }
}