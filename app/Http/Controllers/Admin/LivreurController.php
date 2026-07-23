<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livreur;
use Illuminate\Http\Request;

class LivreurController extends Controller
{
    /**
     * Afficher la liste des livreurs
     */
    public function index(Request $request){
        $query = Livreur::query();

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('tel', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $livreurs = $query->orderBy('nom')->paginate(10);
        return view('admin.livreurs.index', compact('livreurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(){
        return view('admin.livreurs.create');
    }

    /**
     * Enregistrer un nouveau livreur
     */
    public function store(Request $request){
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'ville' => 'nullable|string|max:255',
            'quartier' => 'nullable|string|max:255',
            'statut' => 'required|in:disponible,indisponible',
        ]);

        $livreur = Livreur::create($validated);
        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur créé avec succès.');
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit($id){
        $livreur = Livreur::findOrFail($id);
        return view('admin.livreurs.edit', compact('livreur'));
    }

    /**
     * Mettre à jour un livreur
     */
    public function update(Request $request, $id){
        $livreur = Livreur::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'ville' => 'nullable|string|max:255',
            'quartier' => 'nullable|string|max:255',
            'statut' => 'required|in:disponible,indisponible',
        ]);

        $livreur->update($validated);
        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur mis à jour avec succès.');
    }
    /**
     * Supprimer un livreur
     */
    public function destroy($id){
        $livreur = Livreur::findOrFail($id);
        $livreur->delete();

        return redirect()->route('admin.livreurs.index')
            ->with('success', 'Livreur supprimé avec succès.');
    }
}