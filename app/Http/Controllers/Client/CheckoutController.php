<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Zone;
use App\Services\StockService;
use App\Events\OrderValidated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette commande.');
        }

        if ($order->items->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Votre commande est vide.');
        }

        $sousTotal = $order->items->sum(fn($item) => $item->qte * $item->product->prix_vente);
        $zones = Zone::where('est_expedition', false)->get();
        $zoneExpedition = Zone::where('est_expedition', true)->first();

        return view('client.checkout.show', compact('order', 'zones', 'sousTotal', 'zoneExpedition'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette commande.');
        }
        abort_if($order->statut !== 'panier_converti', 403, 'Commande déjà traitée.');

        if ($order->items->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Votre commande est vide.');
        }

        $validated = $request->validate([
            'mode_livraison' => 'required|in:domicile,expedition',
            'zone_id' => 'required_if:mode_livraison,domicile|nullable|exists:zones,id',
            'ville_expedition' => 'required_if:mode_livraison,expedition|nullable|string|max:255',
            'adresse_precise' => 'required|string|max:1000',
        ]);

        if ($validated['mode_livraison'] === 'expedition') {
            $zone = Zone::where('est_expedition', true)->first();
            if (!$zone) {
                return back()->with('error', 'La zone d\'expédition n\'est pas configurée.');
            }
            $villeExpedition = $validated['ville_expedition'];
        } else {
            $zone = Zone::findOrFail($validated['zone_id']);
            $villeExpedition = null;
        }

        $sousTotal = $order->items->sum(fn($item) => $item->qte * $item->product->prix_vente);
        $tarifLivraison = $zone->tarif;
        $montantTtc = $sousTotal + $tarifLivraison;

        $order->load('items.product');

        try {
            DB::transaction(function () use ($order, $validated, $zone, $villeExpedition, $sousTotal, $tarifLivraison, $montantTtc) {
                foreach ($order->items as $item) {
                    $product = $item->product()->lockForUpdate()->first();

                    if ($item->qte > $product->qte_dispo) {
                        $message = $product->sous_seuil
                            ? "Stock critique pour {$product->designation} : seulement {$product->qte_dispo} disponible(s)."
                            : "Stock insuffisant pour {$product->designation}. Disponible : {$product->qte_dispo}.";
                        throw new \RuntimeException($message);
                    }

                    $this->stockService->sortieStock(
                        product: $product,
                        quantite: $item->qte,
                        type: 'sortie_commande',
                        source: $order,
                        notes: "Commande {$order->num_order}",
                    );
                }

                $order->update([
                    'mode_livraison'    => $validated['mode_livraison'],
                    'adresse_precise'   => $validated['adresse_precise'],
                    'zone_id'           => $zone->id,
                    'ville_expedition'  => $villeExpedition,
                    'tarif_livraison'   => $tarifLivraison,
                    'montant_ttc'       => $montantTtc,
                    'mt_total'          => $sousTotal,
                    'statut'            => 'en_attente',
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        session()->forget('panier_converti_order_id');
        OrderValidated::dispatch($order);

        return redirect()->route('client.orders.index')
            ->with('success', 'Commande validée avec succès ! Elle est en attente de traitement.');
    }
}