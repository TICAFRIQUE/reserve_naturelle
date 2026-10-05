<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
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

        $sousTotal = $order->items->sum(fn($item) => $item->qte * $item->variant->prix_vente);
        $zones = Zone::where('est_expedition', false)->get();
        $zoneExpedition = Zone::where('est_expedition', true)->first();

        return view('client.checkout.show', compact('order', 'zones', 'sousTotal', 'zoneExpedition'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette commande.');
        }

        if ($order->statut !== 'panier_converti') {
            return redirect()->route('client.orders.index')->with('error', 'Cette commande a déjà été traitée.');
        }

        if ($order->items()->doesntExist()) {
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
            // En livraison à domicile, la zone d'expédition n'est pas un choix valide
            $zone = Zone::where('est_expedition', false)->findOrFail($validated['zone_id']);
            $villeExpedition = null;
        }

        try {
            $commande = DB::transaction(function () use ($order, $validated, $zone, $villeExpedition) {
                // Verrou sur la commande : un double clic ou deux onglets ne peuvent pas décrémenter deux fois
                $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

                if ($locked->statut !== 'panier_converti') {
                    return null;
                }

                $items = $locked->items()->get();
                if ($items->isEmpty()) {
                    throw new \RuntimeException('Votre commande est vide.');
                }

                // Verrou de toutes les variantes dans un ordre fixe (évite les deadlocks)
                $variants = ProductVariant::whereIn('id', $items->pluck('product_variant_id'))
                    ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

                $sousTotal = 0;
                foreach ($items as $item) {
                    $variant = $variants->get($item->product_variant_id);

                    if (!$variant || !$variant->actif) {
                        throw new \RuntimeException('Un article de votre commande n\'est plus disponible.');
                    }

                    if ($item->qte > $variant->qte_dispo) {
                        $message = $variant->sous_seuil
                            ? "Stock critique pour {$variant->libelle} : seulement {$variant->qte_dispo} disponible(s)."
                            : "Stock insuffisant pour {$variant->libelle}. Disponible : {$variant->qte_dispo}.";
                        throw new \RuntimeException($message);
                    }

                    // Prix lu sur la variante verrouillée, au moment de la validation
                    $sousTotal += $item->qte * $variant->prix_vente;

                    $this->stockService->sortieStock(
                        variant: $variant,
                        quantite: $item->qte,
                        type: 'sortie_commande',
                        source: $locked,
                        notes: "Commande {$locked->num_order}",
                    );
                }

                $locked->update([
                    'mode_livraison'    => $validated['mode_livraison'],
                    'adresse_precise'   => $validated['adresse_precise'],
                    'zone_id'           => $zone->id,
                    'ville_expedition'  => $villeExpedition,
                    'tarif_livraison'   => $zone->tarif,
                    'montant_ttc'       => $sousTotal + $zone->tarif,
                    'mt_total'          => $sousTotal,
                    'statut'            => 'en_attente',
                ]);

                return $locked;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($commande === null) {
            return redirect()->route('client.orders.index')->with('error', 'Cette commande a déjà été traitée.');
        }

        session()->forget('panier_converti_order_id');
        OrderValidated::dispatch($commande);

        return redirect()->route('client.orders.index')
            ->with('success', 'Commande validée avec succès ! Elle est en attente de traitement.');
    }
}