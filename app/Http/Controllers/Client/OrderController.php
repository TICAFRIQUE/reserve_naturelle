<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\StockService;
use App\Events\OrderValidated;
use Illuminate\Support\Str;

class OrderController extends Controller 
{
    public function __construct(protected StockService $stockService) {}

    public function index(){
        $orders = Order::where('user_id', auth()->id())->where('statut', '!=', 'panier_converti')
                ->latest('date_order')->paginate(10);
        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order){
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.variant');
        return view('client.orders.show', compact('order'));
    }

    public function store(Request $request){
        $cart = Cart::where('user_id', auth()->id())->with('items.variant')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

    // Vérifier le stock AVANT toute modification
    foreach ($cart->items as $item) {
        if ($item->qte > $item->variant->qte_dispo) {
            $message = $item->variant->sous_seuil
                ? "Stock critique pour {$item->variant->libelle} : seulement {$item->variant->qte_dispo} disponible(s) (seuil d'alerte atteint)."
                : "Stock insuffisant pour {$item->variant->libelle}.";
            return back()->with('error', $message);
        }
    }

    $order = DB::transaction(function () use ($cart) {
        // Chercher une commande en cours
        $order = Order::where('user_id', auth()->id())->where('statut', 'panier_converti')->first();

        // Si pas de commande en cours → en créer une
        if (!$order) {
            $order = Order::create([
                'num_order'  => 'CMD-' . strtoupper(Str::random(8)),
                'date_order' => now(),
                'mt_total'   => 0,
                'statut'     => 'panier_converti',
                'user_id'    => auth()->id(),
            ]);
        }

        // Fusionner les articles du panier dans la commande
        foreach ($cart->items as $item) {
            $existing = $order->items()->where('product_variant_id', $item->product_variant_id)->first();
            if ($existing) {
                $existing->increment('qte', $item->qte);
            } else {
                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'qte'                => $item->qte,
                ]);
            }
        }

        // Recalculer le total de la commande
        $order->load('items.variant');
        $order->update([
            'mt_total' => $order->items->sum(fn($i) => $i->qte * $i->variant->prix_vente),
        ]);

        // Vider le panier
        $cart->items()->delete();
        return $order;
    });
    session()->put('panier_converti_order_id', $order->id);
    return redirect()->route('client.checkout.show', $order)->with('success', 'Articles ajoutés à votre commande en cours.');
}
        /**
     * Abandonner le panier converti en cours pour repartir sur un panier vide.
     */
    // public function abandon(Order $order){
    //     abort_if($order->user_id !== auth()->id(), 403);
    //     abort_if($order->statut !== 'panier_converti', 403, 'Cette commande ne peut plus être abandonnée.');

    //     $order->delete(); // cascade sur order_items, aucun stock à restaurer (jamais décrémenté à ce stade)

    //     session()->forget('panier_converti_order_id');

    //     return redirect()->route('client.cart.index')
    //         ->with('success', 'Panier précédent abandonné. Vous pouvez composer un nouveau panier.');
    // }

    public function downloadTicket(Order $order){
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->ticket_path, 404);
        return Storage::disk('local')->download($order->ticket_path);
    }
}