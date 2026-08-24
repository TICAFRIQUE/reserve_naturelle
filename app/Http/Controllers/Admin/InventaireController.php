<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaire;
use App\Models\InventaireProduct;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventaireController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request){
        $query = Inventaire::with('user')->latest('date_inventaire');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('du')) {
            $query->whereDate('date_inventaire', '>=', $request->du);
        }
        if ($request->filled('au')) {
            $query->whereDate('date_inventaire', '<=', $request->au);
        }
        if ($request->filled('sort')) {
            $query->reorder($request->sort, $request->get('direction', 'asc'));
        }

        $inventaires = $query->paginate(15)->withQueryString();
        return view('admin.inventaires.index', compact('inventaires'));
    }

    // Formulaire de création : snapshot du stock théorique
    public function create(){
        $produits = Product::orderBy('designation')->get(['id', 'designation', 'reference_prod', 'qte_dispo']);
        return view('admin.inventaires.create', compact('produits'));
    }

    // Crée l'inventaire + fige qte_theorique pour chaque produit
    public function store(Request $request){
        $request->validate([
            'date_inventaire' => 'required|date',
            'notes' => 'nullable|string',
            'product_ids' => 'nullable|array', // si vide -> tous les produits
        ]);

        $inventaire = DB::transaction(function () use ($request) {
            $inventaire = Inventaire::create([
                'reference' => $this->genererReference(),
                'user_id' => Auth::id(),
                'statut' => 'en_cours',
                'date_inventaire' => $request->date_inventaire,
                'notes' => $request->notes,
            ]);

            $produits = $request->filled('product_ids')
                ? Product::whereIn('id', $request->product_ids)->get()
                : Product::all();

            foreach ($produits as $produit) {
                InventaireProduct::create([
                    'inventaire_id' => $inventaire->id,
                    'product_id' => $produit->id,
                    'qte_theorique' => $produit->qte_dispo,
                    'qte_reelle' => $produit->qte_dispo, // pré-rempli, ajusté à la saisie
                    'ecart' => 0,
                ]);
            }

            return $inventaire;
        });

        return redirect()->route('admin.inventaires.show', $inventaire)
               ->with('success', 'Inventaire créé. Saisissez les quantités comptées.');
    }

    // show() — affichage live sans persister
    public function show(Inventaire $inventaire){
       
        $inventaire->load('produits.product', 'user');
        // Calcul live pour affichage uniquement (pas de save)
        foreach ($inventaire->produits as $ligne) {
            $ligne->qte_theorique_live = $ligne->product->qte_dispo;
            $ligne->ecart_live = $ligne->qte_reelle - $ligne->product->qte_dispo;
        }

        return view('admin.inventaires.show', compact('inventaire'));
    }
    // Saisie des quantités comptées (peut être appelé plusieurs fois avant validation)
    public function update(Request $request, Inventaire $inventaire){
        if ($inventaire->statut !== 'en_cours') {
            return back()->with('error', 'Cet inventaire n\'est plus modifiable.');
        }

        $request->validate([
            'lignes' => 'required|array',
            'lignes.*.id' => 'required|exists:inventaire_produits,id',
            'lignes.*.qte_reelle' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $inventaire) {
            foreach ($request->lignes as $ligne) {
                $ligneInventaire = InventaireProduct::where('id', $ligne['id'])
                    ->where('inventaire_id', $inventaire->id)
                    ->firstOrFail();

                $ligneInventaire->update([
                    'qte_reelle' => $ligne['qte_reelle'],
                    'ecart' => $ligne['qte_reelle'] - $ligneInventaire->qte_theorique,
                ]);
            }
        });
        return back()->with('success', 'Quantités enregistrées.');
    }

    // Valide l'inventaire : applique les écarts au stock réel via VraiStockService
    public function valider(Inventaire $inventaire){
        if ($inventaire->statut !== 'en_cours') {
            return back()->with('error', 'Cet inventaire a déjà été traité.');
        }

        DB::transaction(function () use ($inventaire) {
            $inventaire->load('produits.product');

            foreach ($inventaire->produits as $ligne) {
                $stockActuel = $ligne->product->qte_dispo; // stock live, pas qte_theorique figé
                $ecartReel   = $ligne->qte_reelle - $stockActuel;

                // Trace le décalage entre théorique figé et stock live (mouvements concurrents)
                if ($stockActuel !== $ligne->qte_theorique) {
                    $ligne->update(['notes' => "Stock modifié depuis la création (théorique: {$ligne->qte_theorique}, live: {$stockActuel})"]);
                }

                if ($ecartReel === 0) {
                    continue;
                }

                $this->stockService->ajustementStock(
                    product: $ligne->product,
                    qteReelle: $ligne->qte_reelle,
                    type: 'inventaire',
                    source: $inventaire,
                    notes: "Inventaire {$inventaire->reference}",
                );
                $ligne->update(['ecart' => $ecartReel]);
            }

            $inventaire->update(['statut' => 'valide']);
        });

        return redirect()->route('admin.inventaires.show', $inventaire)
               ->with('success', 'Inventaire validé, stock ajusté.');
    }

    public function annuler(Inventaire $inventaire){
        if ($inventaire->statut !== 'en_cours') {
            return back()->with('error', 'Seul un inventaire en cours peut être annulé.');
        }

        $inventaire->update(['statut' => 'annule']);
        return back()->with('success', 'Inventaire annulé.');
    }
    
    public function destroy(Inventaire $inventaire){
        if ($inventaire->statut === 'valide') {
            return back()->with('error', 'Impossible de supprimer un inventaire validé.');
        }

        $inventaire->delete();
        return redirect()->route('admin.inventaires.index')
               ->with('success', 'Inventaire supprimé.');
    }

    private function genererReference(): string{
        $prefix = 'INV-' . now()->format('Y-m-d');
        $dernier = Inventaire::where('reference', 'like', $prefix . '-%')
                   ->orderByDesc('reference')->value('reference');

        $sequence = $dernier ? (int) substr($dernier, -3) + 1 : 1;
        return $prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}