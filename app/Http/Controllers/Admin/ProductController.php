<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request){
        $query = Product::with('category'); // Eager loading pour éviter les N+1
        
        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filtre par stock (en stock / rupture)
        if ($request->filled('stock_filter')) {
            if ($request->stock_filter === 'in_stock') {
                $query->where('qte_dispo', '>', 0);
            } elseif ($request->stock_filter === 'out_of_stock') {
                $query->where('qte_dispo', '=', 0);
            }
        }
        
        // Filtre par prix
        if ($request->filled('price_min')) {
            $query->where('prix_vente', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('prix_vente', '<=', $request->price_max);
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $products = $query->paginate(15)->withQueryString();
        
        // Pour les filtres
        $categories = Category::orderBy('nom')->pluck('nom', 'id');
        
        return view('admin.produits.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(){
        $categories = Category::orderBy('nom')->get();
        return view('admin.produits.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request){
        $data = $request->validate([
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_vente' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'qte_dispo' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'sometimes|in:draft,published,out_of_stock',
        ]);

        $data['reference_prod'] = $this->generateUniqueReference();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    private function generateUniqueReference(): string{
        do{
            $reference = 'REF-' . strtoupper(Str::random(8));
        } while (Product::where('reference_prod', $reference)->exists());

        return $reference;
    }
    /**
     * Display the specified product.
     */
    public function show(string $id){
        $product = Product::with(['category', 'orderItems', 'achats'])->findOrFail($id);
        
        // Statistiques supplémentaires
        $totalVendu = $product->orderItems->sum('quantity');
        $stockAlerte = $product->qte_dispo <= 5 ? true : false;
        
        return view('admin.produits.show', compact('product', 'totalVendu', 'stockAlerte'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(string $id){
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('nom')->get();
        
        return view('admin.produits.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, string $id){
        $product = Product::findOrFail($id);
        
        $data = $request->validate([
            'reference_prod' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_vente' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'qte_dispo' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|in:draft,published,out_of_stock',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id){
        $product = Product::findOrFail($id);
        
        // Vérifier si le produit est dans des commandes
        if ($product->orderItems()->exists()) {
            return redirect()->route('admin.produits.index')
                ->with('error', 'Impossible de supprimer ce produit car il a des commandes associées.');
        }
        
        // Supprimer l'image
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
    
    /**
     * Restore a soft-deleted product.
     */
    public function restore(string $id){
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        
        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit restauré avec succès.');
    }
    
    /**
     * Update stock of a product.
     */
    public function updateStock(Request $request, string $id){
        $product = Product::findOrFail($id);
        
        $data = $request->validate([
            'qte_dispo' => 'required|integer|min:0',
        ]);
        
        $product->update($data);
        
        return redirect()->route('admin.produits.show', $product)
            ->with('success', 'Stock mis à jour avec succès.');
    }
}