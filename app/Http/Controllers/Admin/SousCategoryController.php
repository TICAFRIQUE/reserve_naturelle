<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SousCategory;
use App\Models\Category;
use Illuminate\Validation\Rule;

class SousCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $query = SousCategory::with('category');
        
        // Filtre par catégorie parente
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Recherche par nom ou description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $sousCategories = $query->orderBy('ordre')->orderBy('nom')->paginate(10);
        
        // Récupérer toutes les catégories pour le filtre
        $categories = Category::orderBy('nom')->get();
        
        return view('admin.sous-categories.index', compact('sousCategories', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        $categories = Category::orderBy('nom')->get();
        return view('admin.sous-categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'statut' => ['required', Rule::in(['actif', 'inactif'])],
            'ordre' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        SousCategory::create($data);

        return redirect()->route('admin.sous-categories.index')
            ->with('success', 'Sous-catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        $sousCategory = SousCategory::with('category')->findOrFail($id);
        return view('admin.sous-categories.show', compact('sousCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){
        $sousCategory = SousCategory::findOrFail($id);
        $categories = Category::orderBy('nom')->get();
        return view('admin.sous-categories.edit', compact('sousCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){
        $sousCategory = SousCategory::findOrFail($id);

        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'statut' => ['required', Rule::in(['actif', 'inactif'])],
            'ordre' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $sousCategory->update($data);

        return redirect()->route('admin.sous-categories.index')
            ->with('success', 'Sous-catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        $sousCategory = SousCategory::findOrFail($id);

        // Vérifier si la sous-catégorie a des produits ou articles associés
        // Adaptez cette vérification selon vos relations
        if ($sousCategory->products()->exists()) {
            return redirect()->route('admin.sous-categories.index')
                ->with('error', 'Impossible de supprimer cette sous-catégorie car elle contient des produits.');
        }
        $sousCategory->delete();
        return redirect()->route('admin.sous-categories.index')->with('success', 'Sous-catégorie supprimée avec succès.');
    }
}