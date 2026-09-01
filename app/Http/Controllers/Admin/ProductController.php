<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SousCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request){
        $query = Product::with(['category', 'sousCategory']);
        
        if ($request->filled('category_id')) {
            $query->where(function($q) use ($request) {
                $q->where('category_id', $request->category_id)
                  ->orWhere('sous_category_id', $request->category_id);
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('stock_filter')) {
            if ($request->stock_filter === 'in_stock') {
                $query->where('qte_dispo', '>', 0);
            } elseif ($request->stock_filter === 'out_of_stock') {
                $query->where('qte_dispo', '=', 0);
            }
        }
        
        if ($request->filled('price_min')) {
            $query->where('prix_vente', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('prix_vente', '<=', $request->price_max);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        $products = $query->paginate(15)->withQueryString();
        
        $categories = Category::with('sousCategories')->orderBy('nom')->get();
        return view('admin.produits.index', compact('products', 'categories'));
    }

    public function create(){
        $categories = Category::orderBy('nom')->get();
        return view('admin.produits.create', compact('categories'));
    }

    public function store(Request $request){
        $data = $request->validate([
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_vente' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'qte_dispo' => 'required|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sous_category_id' => 'nullable|exists:sous_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'sometimes|in:draft,published,out_of_stock',
        ]);

        $data['reference_prod'] = $this->generateUniqueReference();

        if ($request->filled('sous_category_id')) {
            $sousCategory = SousCategory::find($request->sous_category_id);
            $data['category_id'] = $sousCategory->category_id;
            $data['sous_category_id'] = $request->sous_category_id;
        } else {
            $data['sous_category_id'] = null;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }

        Product::create($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit créé avec succès.');
    }

    private function generateUniqueReference(): string{
        do {
            $reference = 'REF-' . strtoupper(Str::random(8));
        } while (Product::where('reference_prod', $reference)->exists());

        return $reference;
    }

    public function show(string $id){
        $product = Product::with(['category', 'sousCategory', 'orderItems', 'achatProducts'])->findOrFail($id);
        $totalVendu = $product->orderItems->sum('quantity');
        $stockAlerte = $product->qte_dispo <= 5 ? true : false;
        return view('admin.produits.show', compact('product', 'totalVendu', 'stockAlerte'));
    }

    public function edit(string $id){
        $product = Product::with(['category', 'sousCategory'])->findOrFail($id);
        $categories = Category::orderBy('nom')->get();
        return view('admin.produits.edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id){
        $product = Product::findOrFail($id);
        
        $data = $request->validate([
            'reference_prod' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_vente' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'qte_dispo' => 'required|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sous_category_id' => 'nullable|exists:sous_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|in:draft,published,out_of_stock',
        ]);

        if ($request->filled('sous_category_id')) {
            $sousCategory = SousCategory::find($request->sous_category_id);
            $data['category_id'] = $sousCategory->category_id;
            $data['sous_category_id'] = $request->sous_category_id;
        } else {
            $data['sous_category_id'] = null;
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }
        $product->update($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(string $id){
        $product = Product::findOrFail($id);
        
        if ($product->orderItems()->exists()) {
            return redirect()->route('admin.produits.index')
                ->with('error', 'Impossible de supprimer ce produit car il a des commandes associées.');
        }
        
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function getSousCategories($categoryId){
        $category = Category::findOrFail($categoryId);
        $sousCategories = $category->sousCategories()->where('statut', 'actif')->orderBy('ordre')->orderBy('nom')->get(['id', 'nom']);
        return response()->json($sousCategories);
    }
}