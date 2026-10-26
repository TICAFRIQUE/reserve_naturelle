<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Services\StockService;
use App\Events\OrderValidated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403, 'Vous n\'êtes pas autorisé à accéder à cette commande.');

        $order->load('items.variant.product');

        if ($order->items->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Votre commande est vide.');
        }

        $sousTotal = $order->items->sum(fn($item) => $item->qte * $item->variant->prix_vente);

        return view('client.checkout.show', compact('order', 'sousTotal'));
    }

    public function store(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403, 'Vous n\'êtes pas autorisé à modifier cette commande.');

        if ($order->statut !== 'panier_converti') {
            return redirect()->route('client.orders.index')->with('error', 'Cette commande a déjà été traitée.');
        }

        if ($order->items()->doesntExist()) {
            return redirect()->route('client.cart.index')->with('error', 'Votre commande est vide.');
        }

        try {
            $commande = DB::transaction(function () use ($order) {
                $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

                if ($locked->statut !== 'panier_converti') {
                    return null;
                }

                $items = $locked->items()->get();
                if ($items->isEmpty()) {
                    throw new \RuntimeException('Votre commande est vide.');
                }

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

                    // Prix figé au moment de la validation
                    $ligne = $item->qte * $variant->prix_vente;
                    $item->update([
                        'prix_unitaire' => $variant->prix_vente,
                        'sous_total'    => $ligne,
                    ]);
                    $sousTotal += $ligne;

                    $this->stockService->sortieStock(
                        variant: $variant,
                        quantite: $item->qte,
                        type: 'sortie_commande',
                        source: $locked,
                        notes: "Commande {$locked->num_order}",
                    );
                }

                $locked->update([
                    'mode_livraison'   => null,
                    'adresse_precise'  => null,
                    'zone_id'          => null,
                    'ville_expedition' => null,
                    'tarif_livraison'  => 0,
                    'mt_total'         => $sousTotal,
                    'montant_ttc'      => $sousTotal,
                    'statut'           => 'en_attente',
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