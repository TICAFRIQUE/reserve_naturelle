<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Zone;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Affiche le formulaire de checkout pour une commande existante
     */
    public function show(Order $order){
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette commande.');
        }

        if ($order->items->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre commande est vide.');
        }

        $sousTotal = $order->items->sum(function($item) {
            return $item->qte * $item->product->prix_vente;
        });

        // Uniquement les zones "domicile" (la zone expédition est gérée automatiquement)
        $zones = Zone::where('est_expedition', false)->get();

        return view('client.checkout.show', compact('order', 'zones', 'sousTotal'));
    }

    /**
     * Met à jour la commande avec les informations de livraison
     */
    public function store(Request $request, Order $order){
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette commande.');
        }

        abort_if($order->statut !== 'panier_converti', 403, 'Commande déjà traitée.');

        if ($order->items->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre commande est vide.');
        }

        $validated = $request->validate([
            'mode_livraison' => 'required|in:domicile,expedition',
            'zone_id' => 'required_if:mode_livraison,domicile|nullable|exists:zones,id',
            'ville_expedition' => 'required_if:mode_livraison,expedition|nullable|string|max:255',
            'adresse_precise' => 'required|string|max:1000',
        ]);

        if ($validated['mode_livraison'] === 'expedition') {
            $zone = Zone::where('est_expedition', true)->firstOrFail();
            $villeExpedition = $validated['ville_expedition'];
        } else {
            $zone = Zone::findOrFail($validated['zone_id']);
            $villeExpedition = null;
        }

        $sousTotal = $order->items->sum(fn($item) => $item->qte * $item->product->prix_vente);
        $tarifLivraison = $zone->tarif;
        $montantTtc = $sousTotal + $tarifLivraison;

        $order->update([
            'mode_livraison' => $validated['mode_livraison'],
            'adresse_precise' => $validated['adresse_precise'],
            'zone_id' => $zone->id,
            'ville_expedition' => $villeExpedition,
            'tarif_livraison' => $tarifLivraison,
            'montant_ttc' => $montantTtc,
            'mt_total' => $sousTotal,
        ]);

        return redirect()->route('client.orders.pay', $order)
            ->with('success', 'Informations de livraison enregistrées avec succès !');
    }
}