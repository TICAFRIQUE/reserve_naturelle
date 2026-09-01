<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::where('user_id', auth()->id())->latest('date_order')->paginate(10);
        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order){
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.product');
        return view('client.orders.show', compact('order'));
    }

    /**
     * Étape 1 : Créer une commande depuis le panier (statut initial: panier_converti)
     */
    public function store(Request $request){
        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        foreach ($cart->items as $item) {
            if ($item->qte > $item->product->qte_dispo) {
                return back()->with('error', "Stock insuffisant pour {$item->product->designation}.");
            }
        }

        $order = DB::transaction(function () use ($cart) {
            $mtTotal = $cart->items->sum(fn($item) => $item->qte * $item->product->prix_vente);

            $order = Order::create([
                'num_order'  => 'CMD-' . strtoupper(Str::random(8)),
                'date_order' => now(),
                'mt_total'   => $mtTotal,
                'statut'     => 'panier_converti',
                'user_id'    => auth()->id(),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'qte'        => $item->qte,
                ]);

                $item->product->decrement('qte_dispo', $item->qte);
            }
            $cart->items()->delete();
            return $order;
        });

        return redirect()->route('client.checkout.show', $order)
            ->with('success', 'Commande créée avec succès ! Veuillez renseigner vos informations de livraison.');
    }

    /**
     * Étape 2 : Afficher le récapitulatif avant validation
     */
    public function pay(Order $order){
        abort_if($order->user_id !== auth()->id(), 403);

        if (!$order->zone_id || !$order->adresse_precise) {
            return redirect()->route('client.checkout.show', $order)
                ->with('error', 'Veuillez d\'abord renseigner vos informations de livraison.');
        }

        abort_if($order->statut !== 'panier_converti', 403, 'Commande déjà traitée.');
        return view('client.orders.payment', compact('order'));
    }

    /**
     * Étape 3 : Valider la commande (statut final: en_attente)
     */
    public function confirm(Order $order){
        abort_if($order->user_id !== auth()->id(), 403);
        abort_if($order->statut !== 'panier_converti', 403, 'Commande déjà traitée.');

        if (!$order->zone_id || !$order->adresse_precise) {
            return redirect()->route('client.checkout.show', $order)
                ->with('error', 'Veuillez d\'abord renseigner vos informations de livraison.');
        }

        $order->update(['statut' => 'en_attente']);
        return redirect()->route('client.orders.index')
            ->with('success', 'Commande validée avec succès ! Elle est en attente de traitement.');
    }
}