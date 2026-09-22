<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\CategorieDepense;
use Illuminate\Http\Request;

class CategorieDepenseController extends Controller
{
    public function index(){
        $categories = CategorieDepense::withCount('depenses')->orderBy('nom')->get();
        return view('admin.categorie-depenses.index', compact('categories'));
    }

    public function create(){
        return view('admin.categorie-depenses.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'nom' => 'required|string|max:255|unique:categorie_depenses,nom',
        ]);
        CategorieDepense::create($data);
        return redirect()->route('admin.categorie-depenses.index')->with('success', 'Catégorie créée.');
    }

    public function edit(CategorieDepense $categorieDepense){
        return view('admin.categorie-depenses.edit', compact('categorieDepense'));
    }

    public function update(Request $request, CategorieDepense $categorieDepense){
        $data = $request->validate([
            'nom' => 'required|string|max:255|unique:categorie_depenses,nom,' . $categorieDepense->id,
        ]);
        $categorieDepense->update($data);
        return redirect()->route('admin.categorie-depenses.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(CategorieDepense $categorieDepense){
        if ($categorieDepense->depenses()->exists()) {
            return back()->with('error', 'Catégorie utilisée par des dépenses, suppression impossible.');
        }

        $categorieDepense->delete();

        return redirect()->route('admin.categorie-depenses.index')->with('success', 'Catégorie supprimée.');
    }
}