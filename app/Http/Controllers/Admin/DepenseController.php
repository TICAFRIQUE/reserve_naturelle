<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieDepense;
use App\Models\Depense;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'date_from'             => 'nullable|date',
            'date_to'               => 'nullable|date|after_or_equal:date_from',
            'categorie_depense_id'  => 'nullable|exists:categorie_depenses,id',
        ]);

        $query = Depense::with('categorie')
            ->entre($request->date_from, $request->date_to)
            ->when($request->filled('categorie_depense_id'),
                fn ($q) => $q->where('categorie_depense_id', $request->categorie_depense_id));

        $totalPeriode = (clone $query)->sum('montant');
        $nombreDepenses = (clone $query)->count();
        $depenseMoyenne = $nombreDepenses > 0 ? intdiv($totalPeriode, $nombreDepenses) : 0;

        $depenses = $query->latest('date_depense')->paginate(15)->withQueryString();
        $categories = CategorieDepense::orderBy('nom')->get();

        return view('admin.depenses.index', compact(
            'depenses', 'categories', 'totalPeriode', 'nombreDepenses', 'depenseMoyenne'
        ));
    }

    public function create(){
        $categories = CategorieDepense::orderBy('nom')->get();
        return view('admin.depenses.create', compact('categories'));
    }

    public function store(Request $request){
        $data = $this->validateData($request);
        $data['created_by'] = auth()->id();

        Depense::create($data);
        return redirect()->route('admin.depenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function edit(Depense $depense){
        $categories = CategorieDepense::orderBy('nom')->get();
        return view('admin.depenses.edit', compact('depense', 'categories'));
    }

    public function update(Request $request, Depense $depense){
        $depense->update($this->validateData($request));
        return redirect()->route('admin.depenses.index')->with('success', 'Dépense mise à jour.');
    }

    public function destroy(Depense $depense){
        $depense->delete();
        return redirect()->route('admin.depenses.index')->with('success', 'Dépense supprimée.');
    }

    private function validateData(Request $request): array{
        return $request->validate([
            'categorie_depense_id' => 'required|exists:categorie_depenses,id',
            'libelle'              => 'required|string|max:255',
            'description'          => 'nullable|string',
            'montant'              => 'required|integer|min:1',
            'date_depense'         => 'required|date',
            'mode_paiement'        => 'required|in:especes,cheque,virement,mobile_money',
            'reference'            => 'nullable|string|max:100',
        ]);
    }
}