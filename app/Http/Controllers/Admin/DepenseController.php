<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achat;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DepenseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date_from'      => 'nullable|date',
            'date_to'        => 'nullable|date|after_or_equal:date_from',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
        ]);

        $query = Achat::with('fournisseur')->where('mt_paye', '>', 0);

        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date_paiement', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_paiement', '<=', $request->date_to);
        }

        $totalPeriode = (clone $query)->sum('mt_paye');
        $nombrePaiements = (clone $query)->count();
        $paiementMoyen = $nombrePaiements > 0 ? intdiv((int) $totalPeriode, $nombrePaiements) : 0;

        // Récupération des données brutes groupées par date
        $rawData = (clone $query)
            ->orderBy('date_paiement')
            ->get(['date_paiement', 'mt_paye'])
            ->groupBy(fn ($a) => $a->date_paiement->format('Y-m-d'))
            ->map(fn ($groupe, $date) => [
                'date'    => $date,
                'montant' => $groupe->sum('mt_paye'),
                'nombre'  => $groupe->count(),
            ]);

        // Création d'une plage de dates continue pour le graphique
        $evolution = collect();
        if ($rawData->isNotEmpty()) {
            $start = Carbon::parse($rawData->keys()->first());
            $end = Carbon::parse($rawData->keys()->last());
            
            // Si une seule date, on affiche juste ce point
            if ($start->eq($end)) {
                $evolution->push([
                    'date' => $start->format('d/m/Y'),
                    'montant' => $rawData->first()['montant'],
                    'nombre' => $rawData->first()['nombre'],
                ]);
            } else {
                // Boucle sur chaque jour entre le début et la fin
                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    $key = $date->format('Y-m-d');
                    if ($rawData->has($key)) {
                        $evolution->push([
                            'date' => $date->format('d/m/Y'),
                            'montant' => $rawData[$key]['montant'],
                            'nombre' => $rawData[$key]['nombre'],
                        ]);
                    } else {
                        // Jour sans paiement : on met 0
                        $evolution->push([
                            'date' => $date->format('d/m/Y'),
                            'montant' => 0, 
                            'nombre' => 0,
                        ]);
                    }
                }
            }
        }

        $depenses = $query->latest('date_paiement')->paginate(15)->withQueryString();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('admin.depenses.index', compact(
            'depenses', 'fournisseurs', 'totalPeriode', 'nombrePaiements', 'paiementMoyen', 'evolution'
        ));
    }
}