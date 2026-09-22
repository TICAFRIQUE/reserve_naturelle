<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achat;
use App\Models\Depense;
use App\Models\Fournisseur;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMouvement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    private const ONGLETS = ['vue-ensemble', 'mouvements', 'produits', 'achats', 'fournisseurs', 'users', 'ventes'];

    public function index(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date|after_or_equal:date_from',
            'onglet'    => 'nullable|in:' . implode(',', self::ONGLETS),
            'search'    => 'nullable|string|max:255',
        ]);

        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->filled('date_to') ? $request->date_to : now()->format('Y-m-d');
        $onglet   = $request->input('onglet', 'vue-ensemble');

        // Période précédente de même durée, pour les % de variation
        $nbJours = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        $prevFrom = Carbon::parse($dateFrom)->subDays($nbJours)->format('Y-m-d');
        $prevTo   = Carbon::parse($dateFrom)->subDay()->format('Y-m-d');

        $cartes = $this->calculerCartes($dateFrom, $dateTo, $prevFrom, $prevTo);

        $data = match ($onglet) {
            'mouvements'   => $this->ongletMouvements($request, $dateFrom, $dateTo),
            'produits'     => $this->ongletProduits($request),
            'achats'       => $this->ongletAchats($dateFrom, $dateTo),
            'fournisseurs' => $this->ongletFournisseurs($dateFrom, $dateTo),
            'users'        => $this->ongletUsers($dateFrom, $dateTo),
            'ventes'       => $this->ongletVentes($dateFrom, $dateTo),
            default        => $this->ongletMouvements($request, $dateFrom, $dateTo, 8),
        };

        return view('admin.rapports.stock', array_merge($cartes, $data, [
            'onglet' => $onglet, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo,
        ]));
    }

    /**
     * Export PDF du rapport complet (données non paginées) pour l'onglet demandé.
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date|after_or_equal:date_from',
            'onglet'    => 'nullable|in:' . implode(',', self::ONGLETS),
        ]);

        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->filled('date_to') ? $request->date_to : now()->format('Y-m-d');
        $onglet   = $request->input('onglet', 'vue-ensemble');

        $nbJours = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        $prevFrom = Carbon::parse($dateFrom)->subDays($nbJours)->format('Y-m-d');
        $prevTo   = Carbon::parse($dateFrom)->subDay()->format('Y-m-d');

        $cartes = $this->calculerCartes($dateFrom, $dateTo, $prevFrom, $prevTo);

        // forPdf = true → get() complet, pas de pagination
        $data = match ($onglet) {
            'mouvements'   => $this->ongletMouvements($request, $dateFrom, $dateTo, null, true),
            'produits'     => $this->ongletProduits($request, true),
            'achats'       => $this->ongletAchats($dateFrom, $dateTo, true),
            'fournisseurs' => $this->ongletFournisseurs($dateFrom, $dateTo, true),
            'users'        => $this->ongletUsers($dateFrom, $dateTo, true),
            'ventes'       => $this->ongletVentes($dateFrom, $dateTo, true),
            default        => $this->ongletMouvements($request, $dateFrom, $dateTo, null, true),
        };

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.rapports.stock_pdf', array_merge($cartes, $data, [
            'onglet' => $onglet, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo,
        ]))->setPaper('a4', 'landscape');

        $nomFichier = 'rapport-stock-' . $onglet . '-' . $dateFrom . '-au-' . $dateTo . '.pdf';

        return $pdf->download($nomFichier);
    }

    private function calculerCartes($dateFrom, $dateTo, $prevFrom, $prevTo): array
    {
        $valeurStock = (float) Product::sum(DB::raw('qte_dispo * cmp'));

        $entreesPeriode = Achat::whereDate('date_achat', '>=', $dateFrom)->whereDate('date_achat', '<=', $dateTo)->sum('mt_total');
        $entreesPrec    = Achat::whereDate('date_achat', '>=', $prevFrom)->whereDate('date_achat', '<=', $prevTo)->sum('mt_total');

        $sortiesPeriode = Order::where('statut', 'livree')->whereDate('date_order', '>=', $dateFrom)->whereDate('date_order', '<=', $dateTo)->sum('montant_ttc');
        $sortiesPrec    = Order::where('statut', 'livree')->whereDate('date_order', '>=', $prevFrom)->whereDate('date_order', '<=', $prevTo)->sum('montant_ttc');

        $depensesPeriode = Depense::entre($dateFrom, $dateTo)->sum('montant');
        $depensesPrec    = Depense::entre($prevFrom, $prevTo)->sum('montant');

        $produitsAlerte = Product::whereColumn('qte_dispo', '<=', 'stock_minimum')->count();
        $produitsRupture = Product::where('qte_dispo', '<=', 0)->count();

        return [
            'valeur_stock'     => $valeurStock,
            'entrees'          => $entreesPeriode,
            'entrees_var'      => $this->variation($entreesPeriode, $entreesPrec),
            'sorties'          => $sortiesPeriode,
            'sorties_var'      => $this->variation($sortiesPeriode, $sortiesPrec),
            'depenses'         => $depensesPeriode,
            'depenses_var'     => $this->variation($depensesPeriode, $depensesPrec),
            'produits_alerte'  => $produitsAlerte,
            'produits_rupture' => $produitsRupture,
        ];
    }

    private function variation($actuel, $precedent): ?float
    {
        if ($precedent <= 0) {
            return null;
        }
        return round((($actuel - $precedent) / $precedent) * 100, 1);
    }

    private function ongletMouvements(Request $request, $dateFrom, $dateTo, ?int $limit = null, bool $forPdf = false): array
    {
        $baseQuery = StockMouvement::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->when($request->filled('search'), fn ($q) => $q->whereHas('product', fn ($p) => $p->search($request->search)));

        $mouvementsTotal = (float) (clone $baseQuery)->sum(DB::raw('quantite * cmp_apres'));

        $query = (clone $baseQuery)->with(['product', 'user', 'source'])->latest();

        $mouvements = match (true) {
            $forPdf => $query->get(),
            (bool) $limit => $query->limit($limit)->get(),
            default => $query->paginate(15)->withQueryString(),
        };

        return compact('mouvements', 'mouvementsTotal');
    }

    private function ongletProduits(Request $request, bool $forPdf = false): array
    {
        $baseQuery = Product::when($request->filled('search'), fn ($q) => $q->search($request->search));

        $produitsTotal = (float) (clone $baseQuery)->sum(DB::raw('qte_dispo * cmp'));

        $query = (clone $baseQuery)->with('category')->orderByDesc(DB::raw('qte_dispo * cmp'));

        $produits = $forPdf ? $query->get() : $query->paginate(15)->withQueryString();

        return compact('produits', 'produitsTotal');
    }

    private function ongletAchats($dateFrom, $dateTo, bool $forPdf = false): array
    {
        $baseQuery = Achat::whereDate('date_achat', '>=', $dateFrom)->whereDate('date_achat', '<=', $dateTo);

        $achatsTotal = (clone $baseQuery)->sum('mt_total');

        $query = (clone $baseQuery)->with('fournisseur')->latest('date_achat');

        $achats = $forPdf ? $query->get() : $query->paginate(15)->withQueryString();

        return compact('achats', 'achatsTotal');
    }

    private function ongletFournisseurs($dateFrom, $dateTo, bool $forPdf = false): array
    {
        $query = Fournisseur::withCount(['achats' => fn ($q) => $q->whereDate('date_achat', '>=', $dateFrom)->whereDate('date_achat', '<=', $dateTo)])
            ->withSum(['achats' => fn ($q) => $q->whereDate('date_achat', '>=', $dateFrom)->whereDate('date_achat', '<=', $dateTo)], 'mt_total')
            ->orderByDesc('achats_sum_mt_total');

        $fournisseurs = $forPdf ? $query->get() : $query->paginate(15)->withQueryString();

        $fournisseursTotal = $fournisseurs->sum('achats_sum_mt_total');

        return compact('fournisseurs', 'fournisseursTotal');
    }

    /**
     * Liste des clients (User) avec le nombre de commandes et le total dépensé sur la période.
     * Nécessite la relation User::orders() (hasMany Order) — à ajouter sur le model si absente.
     */
    private function ongletUsers($dateFrom, $dateTo, bool $forPdf = false): array
    {
        $query = User::withCount(['orders' => fn ($q) => $q->whereDate('date_order', '>=', $dateFrom)->whereDate('date_order', '<=', $dateTo)])
            ->withSum(['orders' => fn ($q) => $q->whereDate('date_order', '>=', $dateFrom)->whereDate('date_order', '<=', $dateTo)], 'montant_ttc')
            ->orderByDesc('orders_sum_montant_ttc');

        $users = $forPdf ? $query->get() : $query->paginate(15)->withQueryString();

        $usersTotal = $users->sum('orders_sum_montant_ttc');

        return compact('users', 'usersTotal');
    }

    private function ongletVentes($dateFrom, $dateTo, bool $forPdf = false): array
    {
        $baseQuery = Order::where('statut', '!=', 'panier_converti')
            ->whereDate('date_order', '>=', $dateFrom)
            ->whereDate('date_order', '<=', $dateTo);

        $ventesTotal = (clone $baseQuery)->sum('montant_ttc');

        $query = (clone $baseQuery)->with(['user', 'zone'])->latest('date_order');

        $ventes = $forPdf ? $query->get() : $query->paginate(15)->withQueryString();

        return compact('ventes', 'ventesTotal');
    }
}