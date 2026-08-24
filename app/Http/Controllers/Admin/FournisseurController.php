<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fournisseur;
use Illuminate\Validation\Rule;

class FournisseurController extends Controller
{
    public function index(Request $request){
        $query = Fournisseur::query();

        if ($request->filled('ville')) {
            $query->where('ville', $request->ville);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('tel', 'LIKE', "%{$search}%")
                  ->orWhere('ville', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_ajout', [$request->date_debut, $request->date_fin]);
        }

        $fournisseurs = $query->orderBy('nom')->orderBy('prenom')->paginate(10)->withQueryString();
        $villes = Fournisseur::distinct()->pluck('ville')->sort();

        return view('admin.fournisseurs.index', compact('fournisseurs', 'villes'));
    }

    public function create(){
        return view('admin.fournisseurs.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'nom'        => 'required|string|max:255',
            'prenom'     => 'required|string|max:255',
            'tel'        => 'required|string|max:20|unique:fournisseurs,tel',
            'adress'     => 'required|string|max:255',
            'ville'      => 'required|string|max:255',
            'date_ajout' => 'nullable|date|before_or_equal:today',
        ]);

        if (empty($data['date_ajout'])) {
            $data['date_ajout'] = now()->toDateString();
        }
        Fournisseur::create($data);
        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur créé avec succès.');
    }

    public function show(string $id){
        $fournisseur = Fournisseur::with('achats')->findOrFail($id);
        return view('admin.fournisseurs.show', compact('fournisseur'));
    }

    public function edit(string $id){
        $fournisseur = Fournisseur::findOrFail($id);
        return view('admin.fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, string $id){
        $fournisseur = Fournisseur::findOrFail($id);
        $data = $request->validate([
            'nom'        => 'required|string|max:255',
            'prenom'     => 'required|string|max:255',
            'tel'        => ['required', 'string', 'max:20', Rule::unique('fournisseurs')->ignore($fournisseur->id)],
            'adress'     => 'required|string|max:255',
            'ville'      => 'required|string|max:255',
            'date_ajout' => 'nullable|date|before_or_equal:today',
        ]);

        $fournisseur->update($data);
        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy(string $id){
        $fournisseur = Fournisseur::findOrFail($id);

        if ($fournisseur->achats()->exists()) {
            return redirect()->route('admin.fournisseurs.index')->with('error', 'Impossible de supprimer ce fournisseur car il a des achats associés.');
        }
        
        $fournisseur->delete();
        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur supprimé avec succès.');
    }
}