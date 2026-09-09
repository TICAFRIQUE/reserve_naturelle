<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Events\OrderValidated;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::where('user_id', auth()->id())->where('statut', '!=', 'panier_converti')
                ->latest('date_order')->paginate(10);
        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order){
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.product');
        return view('client.orders.show', compact('order'));
    }

    public function store(Request $request){
        $existing = Order::where('user_id', auth()->id())->where('statut', 'panier_converti')->first();

        if ($existing) {
            session()->put('panier_converti_order_id', $existing->id);
            return redirect()->route('client.checkout.show', $existing)
            ->with('success', 'Vous avez déjà une commande en cours, reprenez-la.');
        }

        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        foreach ($cart->items as $item) {
            if ($item->qte > $item->product->qte_dispo) {
                $message = $item->product->sous_seuil
                    ? "Stock critique pour {$item->product->designation} : seulement {$item->product->qte_dispo} disponible(s) (seuil d'alerte atteint)."
                    : "Stock insuffisant pour {$item->product->designation}.";
                return back()->with('error', $message);
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
            }
            $cart->items()->delete();
            return $order;
        });

        session()->put('panier_converti_order_id', $order->id);

        return redirect()->route('client.checkout.show', $order)
            ->with('success', 'Commande créée avec succès ! Veuillez renseigner vos informations de livraison.');
    }

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

        $order->load('items.product');

        // Re-vérifier le stock hors transaction (lecture seule)
        foreach ($order->items as $item) {
            if ($item->qte > $item->product->qte_dispo) {
                $message = $item->product->sous_seuil
                    ? "Stock critique pour {$item->product->designation} : seulement {$item->product->qte_dispo} disponible(s)."
                    : "Stock insuffisant pour {$item->product->designation}. Disponible : {$item->product->qte_dispo}.";
                return back()->with('error', $message);
            }
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($item->qte > $product->qte_dispo) {
                    throw new \Exception("Stock insuffisant pour {$product->designation}.");
                }
                $product->decrement('qte_dispo', $item->qte);
            }
            $order->update(['statut' => 'en_attente']);
        });

        session()->forget('panier_converti_order_id'); // clé corrigée
        OrderValidated::dispatch($order);

        return redirect()->route('client.orders.index')
            ->with('success', 'Commande validée avec succès ! Elle est en attente de traitement.');
    }

    public function downloadTicket(Order $order){
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->ticket_path, 404);
        return Storage::disk('local')->download($order->ticket_path);
    }
}