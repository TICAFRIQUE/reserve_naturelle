<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaire;
use App\Models\InventaireProduct;
use App\Models\ProductVariant;
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
        $variants = ProductVariant::where('actif', true)->orderBy('reference_prod')->get();
        return view('admin.inventaires.create', compact('variants'));
    }

    // Crée l'inventaire + fige qte_theorique pour chaque variante
    public function store(Request $request){
        $request->validate([
            'date_inventaire' => 'required|date',
            'notes' => 'nullable|string',
            'product_variant_ids' => 'nullable|array',
        ]);

        $inventaire = DB::transaction(function () use ($request) {
            $inventaire = Inventaire::create([
                'reference' => $this->genererReference(),
                'user_id' => Auth::id(),
                'statut' => 'en_cours',
                'date_inventaire' => $request->date_inventaire,
                'notes' => $request->notes,
            ]);

            $variants = $request->filled('product_variant_ids')
                ? ProductVariant::whereIn('id', $request->product_variant_ids)->get()
                : ProductVariant::where('actif', true)->get();

            foreach ($variants as $variant) {
                InventaireProduct::create([
                    'inventaire_id' => $inventaire->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'qte_theorique' => $variant->qte_dispo,
                    'qte_reelle' => $variant->qte_dispo,
                    'ecart' => 0,
                ]);
            }

            return $inventaire;
        });
        return redirect()->route('admin.inventaires.show', $inventaire)->with('success', 'Inventaire créé. Saisissez les quantités comptées.');
    }

    // show() — affichage live sans persister
    public function show(Inventaire $inventaire){
       
        $inventaire->load('produits.product', 'produits.variant', 'user');
        // Calcul live pour affichage uniquement (pas de save)
        foreach ($inventaire->produits as $ligne) {
            $variant = $ligne->variant
                ?? ProductVariant::where('product_id', $ligne->product_id)->first();
            $ligne->qte_theorique_live = $variant?->qte_dispo ?? 0;
            $ligne->ecart_live = $ligne->qte_reelle - ($variant?->qte_dispo ?? 0);
        }
        return view('admin.inventaires.show', compact('inventaire'));
    }

    // Valide l'inventaire : applique les écarts au stock réel via StockService
    public function valider(Request $request, Inventaire $inventaire){
        
        if ($inventaire->statut !== 'en_cours') {
            return back()->with('error', 'Cet inventaire a déjà été traité.');
        }
        $request->validate([
            'lignes' => 'required|array',
            'lignes.*.id' => 'required|exists:inventaire_produits,id',
            'lignes.*.qte_reelle' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $inventaire) {
            $inventaire->load('produits.product', 'produits.variant');

            foreach ($request->lignes as $ligneData) {
                $ligne = $inventaire->produits->firstWhere('id', $ligneData['id']);

                if (!$ligne) {
                    throw new \Exception("Ligne d'inventaire introuvable.");
                }

                $variant = $ligne->variant
                    ?? ProductVariant::where('product_id', $ligne->product_id)->first();

                if (!$variant) {
                    throw new \Exception("Variante introuvable pour la ligne #{$ligne->id}.");
                }

                $qteReelle  = (int) $ligneData['qte_reelle'];
                $stockActuel = $variant->qte_dispo;
                $ecartReel   = $qteReelle - $stockActuel;
                $notes = ($stockActuel !== $ligne->qte_theorique)
                    ? "Stock modifié depuis la création (théorique: {$ligne->qte_theorique}, live: {$stockActuel})"
                    : null;

                $ligne->update([
                    'qte_reelle' => $qteReelle,
                    'ecart'      => $ecartReel,
                    'notes'      => $notes,
                ]);
                if ($ecartReel === 0) {
                    continue;
                }

                $this->stockService->ajustementStock(
                    variant: $variant,
                    qteReelle: $qteReelle,
                    type: 'inventaire',
                    source: $inventaire,
                    notes: "Inventaire {$inventaire->reference}",
                );
            }
            $inventaire->update(['statut' => 'valide']);
        });
        return redirect()->route('admin.inventaires.show', $inventaire)->with('success', 'Inventaire validé, stock ajusté.');
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
        return redirect()->route('admin.inventaires.index')->with('success', 'Inventaire supprimé.');
    }

    private function genererReference(): string{
        $prefix = 'INV-' . now()->format('Y-m-d');
        $dernier = Inventaire::where('reference', 'like', $prefix . '-%')->orderByDesc('reference')->value('reference');
        $sequence = $dernier ? (int) substr($dernier, -3) + 1 : 1;
        return $prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}