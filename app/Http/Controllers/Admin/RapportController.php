<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Achat;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    /**
     * Vue d'ensemble des rapports
     */
    public function index(Request $request)
    {
        return view(
            'admin.rapports.index',
            $this->calculerDonnees($request, paginer: true)
        );
    }


    /**
     * Rapport des ventes
     */
    public function ventes(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getPeriode($request);

        $ventes = Order::where('statut', 'livree')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('user')
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $chiffreAffaires = (clone $ventes->getCollection())
            ->sum('montant_ttc');

        $nombreVentes = $ventes->total();

        $nombreClients = Order::where('statut', 'livree')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.rapports.ventes', compact(
            'ventes',
            'chiffreAffaires',
            'nombreVentes',
            'nombreClients',
            'dateFrom',
            'dateTo'
        ));
    }


    /**
     * Rapport des achats
     */
    public function achats(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getPeriode($request);

        $achats = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo])
            ->with('fournisseur')
            ->latest('date_achat')
            ->paginate(15)
            ->withQueryString();

        $totalAchats = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo])
            ->sum('mt_total');

        $nombreAchats = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo])
            ->count();

        return view('admin.rapports.achats', compact(
            'achats',
            'totalAchats',
            'nombreAchats',
            'dateFrom',
            'dateTo'
        ));
    }


    /**
     * Rapport des dépenses
     */
    public function depenses(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getPeriode($request);

        /*
         * Pour le moment, les dépenses correspondent
         * aux achats fournisseurs.
         *
         * Si tu crées plus tard une table "depenses",
         * cette méthode pourra être remplacée par
         * une requête sur le modèle Depense.
         */

        $depenses = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo])
            ->with('fournisseur')
            ->latest('date_achat')
            ->paginate(15)
            ->withQueryString();

        $totalDepenses = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo])
            ->sum('mt_total');

        $nombreDepenses = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo])
            ->count();

        return view('admin.rapports.depenses', compact(
            'depenses',
            'totalDepenses',
            'nombreDepenses',
            'dateFrom',
            'dateTo'
        ));
    }


    /**
     * Impression
     */
    public function print(Request $request)
    {
        return view(
            'admin.rapports.print',
            $this->calculerDonnees($request, paginer: false)
        );
    }


    /**
     * Période utilisée par les différents rapports
     */
    private function getPeriode(Request $request): array
    {
        $hasFilter = $request->filled('date_from')
            || $request->filled('date_to');

        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->startOfMonth();

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : (
                $hasFilter
                    ? today()->endOfDay()
                    : now()->endOfMonth()
            );

        if ($dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [
                $dateTo->copy()->startOfDay(),
                $dateFrom->copy()->endOfDay()
            ];
        }

        return [$dateFrom, $dateTo];
    }


    /**
     * Calcul des données de la vue d'ensemble
     */
    private function calculerDonnees(
        Request $request,
        bool $paginer
    ): array {

        $hasFilter = $request->filled('date_from')
            || $request->filled('date_to');

        [$dateFrom, $dateTo] = $this->getPeriode($request);


        // =============================
        // VENTES
        // =============================

        $ordersQuery = Order::where('statut', 'livree')
            ->whereBetween('created_at', [$dateFrom, $dateTo]);


        // =============================
        // ACHATS
        // =============================

        $achatsQuery = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo]);


        // =============================
        // INDICATEURS
        // =============================

        $ca = (clone $ordersQuery)->sum('montant_ttc');

        $depenses = (clone $achatsQuery)->sum('mt_total');

        $marge = $ca - $depenses;

        $nombreCommandes = (clone $ordersQuery)->count();

        $nombreClients = (clone $ordersQuery)
            ->distinct('user_id')
            ->count('user_id');


        // =============================
        // PÉRIODE PRÉCÉDENTE
        // =============================

        $dureeSecondes = $dateFrom->diffInSeconds($dateTo);

        $dateFromPrec = $dateFrom
            ->copy()
            ->subSeconds($dureeSecondes + 1);

        $dateToPrec = $dateFrom
            ->copy()
            ->subSecond();


        $ordersPrec = Order::where('statut', 'livree')
            ->whereBetween(
                'created_at',
                [$dateFromPrec, $dateToPrec]
            );

        $achatsPrec = Achat::where('statut', '!=', 'annule')
            ->whereBetween(
                'date_achat',
                [$dateFromPrec, $dateToPrec]
            );


        $caPrec = (clone $ordersPrec)->sum('montant_ttc');

        $depensesPrec = (clone $achatsPrec)->sum('mt_total');

        $margePrec = $caPrec - $depensesPrec;

        $nombreCommandesPrec = (clone $ordersPrec)->count();

        $nombreClientsPrec = (clone $ordersPrec)
            ->distinct('user_id')
            ->count('user_id');


        // =============================
        // VARIATIONS
        // =============================

        $variation = function ($actuel, $precedent) {

            return $precedent > 0
                ? round(
                    (($actuel - $precedent) / $precedent) * 100,
                    1
                )
                : ($actuel > 0 ? 100.0 : 0.0);
        };


        $variations = [

            'ca' => $variation(
                $ca,
                $caPrec
            ),

            'commandes' => $variation(
                $nombreCommandes,
                $nombreCommandesPrec
            ),

            'clients' => $variation(
                $nombreClients,
                $nombreClientsPrec
            ),

            'marge' => $variation(
                $marge,
                $margePrec
            ),

        ];


        // =============================
        // LISTES
        // =============================

        if ($paginer) {

            $ventes = (clone $ordersQuery)
                ->with('user')
                ->latest('created_at')
                ->paginate(
                    15,
                    ['*'],
                    'ventes_page'
                );

            $achats = (clone $achatsQuery)
                ->with('fournisseur')
                ->latest('date_achat')
                ->paginate(
                    15,
                    ['*'],
                    'achats_page'
                );

        } else {

            $ventes = (clone $ordersQuery)
                ->with('user')
                ->latest('created_at')
                ->get();

            $achats = (clone $achatsQuery)
                ->with('fournisseur')
                ->latest('date_achat')
                ->get();
        }


        // =============================
        // CATÉGORIE LA PLUS VENDUE
        // =============================

        $categorieTop = OrderItem::join(
                'orders',
                'order_items.order_id',
                '=',
                'orders.id'
            )
            ->join(
                'products',
                'order_items.product_id',
                '=',
                'products.id'
            )
            ->join(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )
            ->where('orders.statut', 'livree')
            ->whereBetween(
                'orders.created_at',
                [$dateFrom, $dateTo]
            )
            ->select(
                'categories.nom',
                DB::raw(
                    'SUM(order_items.qte) as total_qte'
                )
            )
            ->groupBy(
                'categories.id',
                'categories.nom'
            )
            ->orderByDesc('total_qte')
            ->first();


        // =============================
        // MEILLEUR CLIENT
        // =============================

        $clientTop = (clone $ordersQuery)
            ->select(
                'user_id',
                DB::raw(
                    'SUM(montant_ttc) as total_achete'
                )
            )
            ->groupBy('user_id')
            ->orderByDesc('total_achete')
            ->with('user')
            ->first();


        return compact(
            'ca',
            'depenses',
            'marge',
            'nombreCommandes',
            'nombreClients',
            'variations',
            'ventes',
            'achats',
            'dateFrom',
            'dateTo',
            'hasFilter',
            'categorieTop',
            'clientTop'
        );
    }
}