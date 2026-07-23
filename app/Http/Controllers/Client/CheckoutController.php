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
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette commande.');
        }

        // Vérifier que la commande a des items
        if ($order->items->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre commande est vide.');
        }

        // Calculer le sous-total depuis les items de la commande
        $sousTotal = $order->items->sum(function($item) {
            return $item->qte * $item->product->prix_vente;
        });
        $zones = Zone::all();
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
        'zone_id' => 'required|exists:zones,id',
        'ville_expedition' => 'nullable|string|max:255',
        'adresse_precise' => 'required|string|max:1000',
    ]);

    $zone = Zone::findOrFail($validated['zone_id']);
    $villeExpedition = $zone->est_expedition ? $validated['ville_expedition'] : null;

    $sousTotal = $order->items->sum(fn($item) => $item->qte * $item->product->prix_vente);
    $tarifLivraison = $zone->tarif;
    $montantTtc = $sousTotal + $tarifLivraison;

    $order->update([
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