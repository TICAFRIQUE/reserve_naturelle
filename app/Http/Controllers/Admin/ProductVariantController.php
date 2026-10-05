<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->orderBy('conditionnement')->get();
        return view('admin.produits.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.produits.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'conditionnement' => 'required|string|max:255',
            'prix_vente'      => 'required|integer|min:0',
            'qte_dispo'       => 'nullable|integer|min:0',
            'stock_minimum'   => 'nullable|integer|min:0',
            'cmp'             => 'nullable|integer|min:0',
            'actif'           => 'sometimes|boolean',
        ]);

        $data['product_id']     = $product->id;
        $data['reference_prod'] = $this->generateReference($product->designation, $data['conditionnement']);
        $data['qte_dispo']      = $data['qte_dispo'] ?? 0;
        $data['stock_minimum']  = $data['stock_minimum'] ?? 0;
        $data['cmp']            = $data['cmp'] ?? 0;
        $data['actif']          = $request->boolean('actif', true);

        $exists = ProductVariant::where('product_id', $product->id)
            ->where('conditionnement', $data['conditionnement'])
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->withErrors(['conditionnement' => 'Ce conditionnement existe déjà pour ce produit.']);
        }

        ProductVariant::create($data);

        return redirect()
            ->route('admin.produits.variants.index', $product)
            ->with('success', 'Variante créée avec succès.');
    }

    public function edit(ProductVariant $variant)
    {
        $product = $variant->product;
        return view('admin.produits.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'conditionnement' => 'required|string|max:255',
            'prix_vente'      => 'required|integer|min:0',
            'stock_minimum'   => 'nullable|integer|min:0',
            'actif'           => 'sometimes|boolean',
        ]);

        $data['stock_minimum'] = $data['stock_minimum'] ?? 0;
        $data['actif']         = $request->boolean('actif', true);

        if ($variant->conditionnement !== $data['conditionnement']) {
            $data['reference_prod'] = $this->generateReference($variant->product->designation, $data['conditionnement']);
        }

        $exists = ProductVariant::where('product_id', $variant->product_id)
            ->where('conditionnement', $data['conditionnement'])
            ->where('id', '!=', $variant->id)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->withErrors(['conditionnement' => 'Ce conditionnement existe déjà pour ce produit.']);
        }

        $variant->update($data);

        return redirect()
            ->route('admin.produits.variants.index', $variant->product_id)
            ->with('success', 'Variante mise à jour.');
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->stockMouvements()->exists()) {
            return back()->with('error', 'Impossible de supprimer : des mouvements de stock existent.');
        }

        if ($variant->orderItems()->exists()) {
            return back()->with('error', 'Impossible de supprimer : des commandes utilisent cette variante.');
        }

        $product = $variant->product;
        $variant->delete();

        return redirect()
            ->route('admin.produits.variants.index', $product)
            ->with('success', 'Variante supprimée.');
    }

    private function generateReference(string $designation, string $conditionnement): string
    {
        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $designation), 0, 4));
        $cond = strtoupper(preg_replace('/\s+/', '', $conditionnement));

        $reference = $base . '-' . $cond;
        $original  = $reference;
        $counter   = 1;

        while (ProductVariant::where('reference_prod', $reference)->exists()) {
            $reference = $original . '-' . $counter;
            $counter++;
        }

        return $reference;
    }
}