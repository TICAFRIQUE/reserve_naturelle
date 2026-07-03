<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fournisseur;
use Illuminate\Validation\Rule;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $query = Fournisseur::query();
        
        // Filtre par ville
        if ($request->filled('ville')) {
            $query->where('ville', $request->ville);
        }
        
        // Recherche par nom, prénom, téléphone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('tel', 'LIKE', "%{$search}%")
                  ->orWhere('ville', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtre par date d'ajout
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_ajout', [$request->date_debut, $request->date_fin]);
        }
        
        $fournisseurs = $query->orderBy('nom')
                             ->orderBy('prenom')
                             ->paginate(10)
                             ->withQueryString(); // Garde les filtres dans la pagination
        
        // Pour le filtre ville (liste des villes uniques)
        $villes = Fournisseur::distinct()->pluck('ville')->sort();
        
        return view('admin.fournisseurs.index', compact('fournisseurs', 'villes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        return view('admin.fournisseurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => 'required|string|max:20|unique:fournisseurs,tel',
            'adress' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'date_ajout' => 'nullable|date|before_or_equal:today',
        ]);

        // Si date_ajout non fournie, on met la date du jour
        if (empty($data['date_ajout'])) {
            $data['date_ajout'] = now()->toDateString();
        }

        Fournisseur::create($data);

        return redirect()->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        // Récupérer le fournisseur avec ses achats
        $fournisseur = Fournisseur::with('achats')->findOrFail($id);
        
        return view('admin.fournisseurs.show', compact('fournisseur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){
        $fournisseur = Fournisseur::findOrFail($id);
        return view('admin.fournisseurs.edit', compact('fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){
        $fournisseur = Fournisseur::findOrFail($id);

        // Validation avec unique pour le téléphone
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => ['required', 'string', 'max:20', Rule::unique('fournisseurs')->ignore($fournisseur->id)],
            'adress' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'date_ajout' => 'nullable|date|before_or_equal:today',
        ]);

        $fournisseur->update($data);

        return redirect()->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        $fournisseur = Fournisseur::findOrFail($id);

        // Vérifier si le fournisseur a des achats
        if ($fournisseur->achats()->exists()) {
            return redirect()->route('admin.fournisseurs.index')
                ->with('error', 'Impossible de supprimer ce fournisseur car il a des achats associés.');
        }

        $fournisseur->delete();

        return redirect()->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
    
    /**
     * Restore a soft-deleted resource.
     */
    public function restore(string $id)
    {
        $fournisseur = Fournisseur::withTrashed()->findOrFail($id);
        $fournisseur->restore();
        
        return redirect()->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur restauré avec succès.');
    }
}