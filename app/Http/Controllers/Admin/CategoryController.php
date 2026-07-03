<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; // 👈 IMPORTANT : ajoutez cette ligne

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        // Liste des catégories triées par nom
        $categories = Category::orderBy('nom')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'statut' => 'required|string', // ou 'boolean' selon votre base
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0', // ajouté si vous voulez
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        // Optionnel : afficher une catégorie seule
        $category = Category::with(['sousCategories', 'products'])->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){
        // Récupérer la catégorie à modifier
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){
        // Récupérer la catégorie
        $category = Category::findOrFail($id);

        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'statut' => 'required|string',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
        ]);
        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        // Récupérer la catégorie
        $category = Category::findOrFail($id);

        // Vérifier s'il y a des produits ou sous-catégories
        if ($category->products()->exists() || $category->sousCategories()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits ou sous-catégories.');
        }
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}