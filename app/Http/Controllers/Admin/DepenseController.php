<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieDepense;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepenseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date_from'            => 'nullable|date',
            'date_to'              => 'nullable|date|after_or_equal:date_from',
            'categorie_depense_id' => 'nullable|exists:categorie_depenses,id',
        ]);

        $query = Depense::with('libelle.categorie')
            ->entre($request->date_from, $request->date_to)
            ->when($request->filled('categorie_depense_id'),
                fn ($q) => $q->whereHas('libelle',
                    fn ($l) => $l->where('categorie_depense_id', $request->categorie_depense_id)));

        $totalPeriode   = (clone $query)->sum('montant');
        $nombreDepenses = (clone $query)->count();
        $depenseMoyenne = $nombreDepenses > 0 ? intdiv($totalPeriode, $nombreDepenses) : 0;

        $depenses   = $query->latest('date_depense')->latest('id')->paginate(15)->withQueryString();
        $categories = CategorieDepense::orderBy('nom')->get();

        return view('admin.depenses.index', compact(
            'depenses', 'categories', 'totalPeriode', 'nombreDepenses', 'depenseMoyenne'
        ));
    }

    public function create()
    {
        return view('admin.depenses.create', $this->formData());
    }

    public function store(Request $request)
    {
        Depense::create($this->validateData($request) + [
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.depenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function edit(Depense $depense)
    {
        $depense->load('libelle.categorie');

        return view('admin.depenses.edit', $this->formData() + ['depense' => $depense]);
    }

    public function update(Request $request, Depense $depense)
    {
        $depense->update($this->validateData($request));

        return redirect()->route('admin.depenses.index')->with('success', 'Dépense mise à jour.');
    }

    public function destroy(Depense $depense)
    {
        $depense->delete();

        return redirect()->route('admin.depenses.index')->with('success', 'Dépense supprimée.');
    }

    /**
     * Familles avec leurs libellés, et map famille_id => libellés pour le select dynamique.
     */
    private function formData(): array
    {
        $categories = CategorieDepense::with(['libelles' => fn ($q) => $q->orderBy('nom')])
            ->orderBy('nom')
            ->get();

        $libellesParFamille = $categories->mapWithKeys(fn ($c) => [
            $c->id => $c->libelles->map(fn ($l) => ['id' => $l->id, 'nom' => $l->nom])->values(),
        ]);

        return compact('categories', 'libellesParFamille');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'categorie_depense_id' => 'required|exists:categorie_depenses,id',
            'libelle_depense_id'   => [
                'required',
                // le libellé doit appartenir à la famille choisie
                Rule::exists('libelle_depenses', 'id')
                    ->where('categorie_depense_id', $request->categorie_depense_id),
            ],
            'montant'      => 'required|integer|min:1',
            'date_depense' => 'required|date|before_or_equal:today',
        ]);

        // la famille n'est pas stockée : elle se déduit du libellé
        return [
            'libelle_depense_id' => $data['libelle_depense_id'],
            'montant'            => $data['montant'],
            'date_depense'       => $data['date_depense'],
        ];
    }
}