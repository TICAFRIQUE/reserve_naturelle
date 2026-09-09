<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Achat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Gestion de la période
        |--------------------------------------------------------------------------
        | Même logique que le DashboardController :
        |
        | - Aucun filtre :
        |      début du mois -> fin du mois
        |
        | - date_from uniquement :
        |      date_from -> aujourd'hui
        |
        | - date_to uniquement :
        |      début du mois -> date_to
        |
        | - date_from + date_to :
        |      date_from -> date_to
        |--------------------------------------------------------------------------
        */

        $hasFilter = $request->filled('date_from') || $request->filled('date_to');

        $dateFrom = $hasFilter && $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->startOfMonth();

        $dateTo = $hasFilter && $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : ($hasFilter ? today()->endOfDay() : now()->endOfMonth());


        /*
        |--------------------------------------------------------------------------
        | Requête des ventes
        |--------------------------------------------------------------------------
        |
        | On considère uniquement les commandes livrées comme chiffre
        | d'affaires réel.
        |
        */

        $ordersQuery = Order::where('statut', 'livree')
            ->whereBetween('created_at', [$dateFrom, $dateTo]);


        /*
        |--------------------------------------------------------------------------
        | Requête des achats
        |--------------------------------------------------------------------------
        |
        | Les achats annulés sont exclus.
        |
        */

        $achatsQuery = Achat::where('statut', '!=', 'annule')
            ->whereBetween('date_achat', [$dateFrom, $dateTo]);


        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $ca = (clone $ordersQuery)->sum('montant_ttc');

        $depenses = (clone $achatsQuery)->sum('mt_total');

        $marge = $ca - $depenses;
        $ventes = (clone $ordersQuery)->with('user')->latest('created_at')->paginate(15, ['*'], 'ventes_page');
        $achats = (clone $achatsQuery)->with('fournisseur')->latest('date_achat')->paginate(15, ['*'], 'achats_page');
        $nombreJours = $dateFrom->diffInDays($dateTo) + 1;

        if ($nombreJours <= 31) {

            $evolution = collect();

            for ($date = $dateFrom->copy()->startOfDay();
                 $date->lte($dateTo);
                 $date->addDay()) {

                $caJour = Order::where('statut', 'livree')
                    ->whereBetween('created_at', [
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    ])
                    ->sum('montant_ttc');

                $depensesJour = Achat::where('statut', '!=', 'annule')
                    ->whereBetween('date_achat', [
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    ])
                    ->sum('mt_total');

                $evolution->push([
                    'label'    => $date->format('d/m'),
                    'ca'       => $caJour,
                    'depenses' => $depensesJour,
                    'marge'    => $caJour - $depensesJour,
                ]);
            }

        } else {
            $evolution = collect();

            $date = $dateFrom->copy()->startOfMonth();
            $fin = $dateTo->copy()->startOfMonth();

            while ($date->lte($fin)) {
                $debutMois = $date->copy()->startOfMonth();
                $finMois = $date->copy()->endOfMonth();

                if ($debutMois->lt($dateFrom)) {
                    $debutMois = $dateFrom->copy();
                }

                if ($finMois->gt($dateTo)) {
                    $finMois = $dateTo->copy();
                }

                $caMois = Order::where('statut', 'livree')->whereBetween('created_at', [$debutMois, $finMois])->sum('montant_ttc');
                $depensesMois = Achat::where('statut', '!=', 'annule')->whereBetween('date_achat', [$debutMois, $finMois])->sum('mt_total');
                $evolution->push([
                    'label'    => $date->format('m/Y'),
                    'ca'       => $caMois,
                    'depenses' => $depensesMois,
                    'marge'    => $caMois - $depensesMois,
                ]);
                $date->addMonth();
            }
        }
        return view('admin.rapports.index', compact('ca','depenses','marge','ventes','achats','evolution','dateFrom','dateTo','hasFilter'
        ));
    }
}