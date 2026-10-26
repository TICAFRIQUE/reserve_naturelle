<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\StockService;
use App\Services\RecueCommandeService;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(){
        $orders = Order::where('user_id', auth()->id())
            ->where('statut', '!=', 'panier_converti')
            ->latest('date_order')
            ->paginate(10);
        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order){
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.variant', 'items.product');
        return view('client.orders.show', compact('order'));
    }

    public function store(Request $request){
        $cart = Cart::where('user_id', auth()->id())->with('items.variant')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        // Vérifier le stock AVANT toute modification
        foreach ($cart->items as $item) {
            if (!$item->variant || !$item->variant->actif) {
                return back()->with('error', 'Un article de votre panier n\'est plus disponible. Retirez-le pour continuer.');
            }
            if ($item->qte > $item->variant->qte_dispo) {
                $message = $item->variant->sous_seuil
                    ? "Stock critique pour {$item->variant->libelle} : seulement {$item->variant->qte_dispo} disponible(s) (seuil d'alerte atteint)."
                    : "Stock insuffisant pour {$item->variant->libelle}.";
                return back()->with('error', $message);
            }
        }
        $order = DB::transaction(function () use ($cart) {
            $order = Order::where('user_id', auth()->id())
                ->where('statut', 'panier_converti')
                ->first();

            if (!$order) {
                $order = Order::create([
                    'num_order'  => 'CMD-' . strtoupper(Str::random(8)),
                    'date_order' => now(),
                    'mt_total'   => 0,
                    'statut'     => 'panier_converti',
                    'user_id'    => auth()->id(),
                ]);
            }

            // Fusionner les articles du panier en figeant le prix
            foreach ($cart->items as $item) {
                $pu = $item->variant->prix_vente;
                $existing = $order->items()
                    ->where('product_variant_id', $item->product_variant_id)
                    ->first();

                if ($existing) {
                    $qte = $existing->qte + $item->qte;
                    $existing->update([
                        'qte'           => $qte,
                        'prix_unitaire' => $pu,
                        'sous_total'    => $pu * $qte,
                    ]);
                } else {
                    $order->items()->create([
                        'product_id'         => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'qte'                => $item->qte,
                        'prix_unitaire'      => $pu,
                        'sous_total'         => $pu * $item->qte,
                    ]);
                }
            }

            $order->load('items');
            $order->update(['mt_total' => $order->items->sum('sous_total')]);

            $cart->items()->delete();

            return $order;
        });

        session()->put('panier_converti_order_id', $order->id);
        return redirect()
            ->route('client.checkout.show', $order)->with('success', 'Articles ajoutés à votre commande en cours.');
    }

    public function downloadTicket(Order $order, RecueCommandeService $tickets){
        abort_unless($order->user_id === auth()->id(), 403);
        abort_if(in_array($order->statut, ['panier_converti', 'en_attente', 'annulee']), 404);

        try {
            // Régénération systématique (temporaire, voir note ci-dessous)
            $path = $tickets->generate($order);
        } catch (\Throwable $e) {
            report($e);
            abort(503, 'Le ticket est momentanément indisponible. Réessayez plus tard.');
        }
        return Storage::disk('local')->download($path, "recu-{$order->num_order}.pdf");
    }
}