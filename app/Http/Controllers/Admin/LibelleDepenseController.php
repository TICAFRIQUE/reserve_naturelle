<?php
// app/Http/Controllers/Admin/LibelleDepenseController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieDepense;
use App\Models\LibelleDepense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LibelleDepenseController extends Controller
{
    public function index(Request $request){
        $libelles = LibelleDepense::with('categorie')
            ->when($request->categorie_depense_id, fn ($q, $id) => $q->where('categorie_depense_id', $id))
            ->orderBy('categorie_depense_id')->orderBy('nom')
            ->paginate(20)->withQueryString();

        return view('admin.libelle-depenses.index', [
            'libelles'   => $libelles,
            'categories' => CategorieDepense::orderBy('nom')->get(),
        ]);
    }

    public function create(){
        return view('admin.libelle-depenses.create', [
            'categories' => CategorieDepense::orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request){
        LibelleDepense::create($this->validated($request));
        return redirect()->route('admin.libelle-depenses.index')->with('success', 'Libellé créé.');
    }

    public function edit(LibelleDepense $libelleDepense){
        return view('admin.libelle-depenses.edit', [
            'libelle'    => $libelleDepense,
            'categories' => CategorieDepense::orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, LibelleDepense $libelleDepense){
        $libelleDepense->update($this->validated($request, $libelleDepense));
        return redirect()->route('admin.libelle-depenses.index')->with('success', 'Libellé modifié.');
    }

    public function destroy(LibelleDepense $libelleDepense){
        // À activer quand depenses référencera libelle_depense_id :
        // if ($libelleDepense->depenses()->exists()) { return back()->with('error', '...'); }

        $libelleDepense->delete();
        return redirect()->route('admin.libelle-depenses.index')->with('success', 'Libellé supprimé.');
    }

    protected function validated(Request $request, ?LibelleDepense $libelle = null): array{
        return $request->validate([
            'categorie_depense_id' => ['required', 'exists:categorie_depenses,id'],
            'nom' => [
                'required', 'string', 'max:255',
                Rule::unique('libelle_depenses', 'nom')
                    ->where('categorie_depense_id', $request->categorie_depense_id)
                    ->ignore($libelle?->id),
            ],
        ]);
    }
}