<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Models\AchatProduct;
use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\Achat;

class AchatController extends Controller
{
    public function __construct(protected StockService $stockservice){}

    //Liste des achats
    public function index(Request $request){
        $query = Achat::with(['fournisseur', 'user','produits'])->latest();

        if($request->filled('statut')){
            $query->where('statut', $request->statut);
        }
        if($request->filled('fournisseur_id')){
            $query->where('fournisseur_id', $request->fournisseur_id);
        }
        if($request->filled('du')){
            $query->whereDate('date_achat', '>=', $request->du);
        }
        if ($request->filled('au')) {
            $query->whereDate('date_achat', '<=', $request->au);
        }

        $achats  = $query->paginate(15)->withQueryString();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        return view('admin.achats.index', compact('achats','fournisseurs'));
    }

    //Formulaire de creation d'un achat
    public function create(){
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $produits = Product::orderBy('designation')->get();
        return view('admin.achats.create', compact('fournisseurs', 'produits'));
    }

    //Enregistrement de l'achat (Brouillon ou validation)
    public function store(Request $request){
        $request->validate([
            'fournisseur_id'              => 'required|exists:fournisseurs,id',
            'date_achat'                  => 'required|date',
            'date_reception_prevue'       => 'nullable|date|after_or_equal:date_achat',
            'notes'                       => 'nullable|string',
            'lignes'                      => 'required|array|min:1',
            'lignes.*.product_id'         => 'required|exists:products,id',
            'lignes.*.qte_commandee'      => 'required|integer|min:1',
            'lignes.*.prix_unitaire'      => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $achat = Achat::create([
                'numero'                => $this->genererNumero(),
                'fournisseur_id'        => $request->fournisseur_id,
                'user_id'               => Auth::id(),
                'statut'                => 'confirme',
                'date_achat'            => $request->date_achat,
                'date_reception_prevue' => $request->date_reception_prevue,
                'mt_total'              => 0,
                'notes'                 => $request->notes,
            ]);

            $total = 0;
            foreach ($request->lignes as $ligne) {
                AchatProduct::create([
                    'achat_id'       => $achat->id,
                    'product_id'     => $ligne['product_id'],
                    'qte_commandee'  => $ligne['qte_commandee'],
                    'qte_recue'      => 0,
                    'prix_unitaire'  => $ligne['prix_unitaire'],
                ]);
                $total += $ligne['qte_commandee'] * $ligne['prix_unitaire'];
            }
            $achat->update(['mt_total' => $total]);
        });
        return redirect()->route('admin.achats.index')->with('success', 'Achat enregistré avec succès.');
    }

    // Détail d'un achat
    public function show(Achat $achat){
        $achat->load([
            'fournisseur',
            'user',
            'produits.product',
            'stockMouvements.user',
        ]);
        return view('admin.achats.show', compact('achat'));
    }

    // Formulaire de réception
    public function receptionForm(Achat $achat){
        if (!in_array($achat->statut, ['confirme', 'recu_partiel'])) {
            return redirect()->route('admin.achats.show', $achat)->with('error', 'Cet achat ne peut pas être réceptionné.');
        }
        $achat->load('produits.product');
        return view('admin.achats.reception', compact('achat'));
    }

    // Traitement de la réception
    public function receptionner(Request $request, Achat $achat){
        if (!in_array($achat->statut, ['confirme', 'recu_partiel'])) {
            return redirect()->route('admin.achats.show', $achat)
                ->with('error', 'Cet achat ne peut pas être réceptionné.');
        }

        $request->validate([
            'lignes'                     => 'required|array',
            'lignes.*.achat_product_id'  => 'required|exists:achat_product,id',
            'lignes.*.qte_recue'         => 'required|integer|min:0',
            'date_reception'             => 'required|date',
            'mt_paye'                    => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $achat) {
            foreach ($request->lignes as $ligne) {

                // Vérifie que la ligne appartient bien à cet achat
                $achatProduit = AchatProduct::where('id', $ligne['achat_product_id'])->where('achat_id', $achat->id)->firstOrFail();
                // Calcul du reliquat
                $reliquat = $achatProduit->qte_commandee - $achatProduit->qte_recue;

                // Quantité réellement réceptionnée (bornée par le reliquat)
                $qteAReceptionner = min((int) $ligne['qte_recue'], $reliquat);

                if ($qteAReceptionner <= 0) {
                    continue;
                }

                $achatProduit->update([
                    'qte_recue' => $achatProduit->qte_recue + $qteAReceptionner,
                ]);

                $this->stockservice->entreeStock(
                    product: $achatProduit->product,
                    quantite: $qteAReceptionner,
                    type: 'entree_achat',
                    source: $achat,
                    notes: "Réception achat {$achat->numero}",
                );
            }

            // Recalcule le statut global à partir des lignes à jour
            $achat->refresh()->load('produits');
            $achat->update([
                'date_reception' => $request->date_reception,
                'mt_paye'        => $request->mt_paye,
                'date_paiement'  => $request->mt_paye > 0 ? now()->toDateString() : $achat->date_paiement,
                'statut'         => $achat->estTotalementRecu()
                    ? 'recu_total'
                    : ($achat->estPartiellementRecu() ? 'recu_partiel' : $achat->statut),
            ]);
        });
        return redirect()->route('admin.achats.show', $achat)->with('success', 'Réception de l’achat enregistrée avec succès.');
    }
    // Annuler un achat
    public function annuler(Achat $achat){
        if ($achat->statut !== 'confirme') {
            return back()->with('error', 'Seul un achat confirmé peut être annulé.');
        }
        $achat->update(['statut' => 'annule']);
        return back()->with('success', 'Achat annulé.');
    }

    // Génération du numéro d'achat : ACH-2026-08-05-001
    private function genererNumero(): string{
        $prefix = 'ACH-' . now()->format('Y-m-d');
        $dernier = Achat::where('numero', 'like', $prefix . '-%')->orderByDesc('numero')->value('numero');
        $sequence = $dernier ? (int) substr($dernier, -3) + 1 : 1;
        return $prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}