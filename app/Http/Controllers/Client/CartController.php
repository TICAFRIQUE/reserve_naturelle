<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(){
        // Restaure le panier_converti s'il existe, AVANT d'afficher le panier
        $this->restorePanierConvertiIfExists();

        $cart = $this->cartService->getOrCreateCart();
        $cart->load('items.variant');

        $total = $cart->items->sum(fn($item) => $item->variant ? $item->qte * $item->variant->prix_vente : 0);
        $hasIssues = $cart->items->contains(fn($item) => !$item->variant || !$item->variant->actif || $item->qte > $item->variant->qte_dispo);

        return view('client.cart.index', compact('cart', 'total', 'hasIssues'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'product_variant_id' => ['required', Rule::exists('product_variants', 'id')->where('actif', true)],
            'qte' => ['required', 'integer', 'min:1'],
        ], [
            'product_variant_id.exists' => 'Ce produit n\'est plus disponible.',
        ]);

        // Restaure le panier_converti s'il existe, AVANT d'ajouter le nouvel article
        $this->restorePanierConvertiIfExists();

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);
        $cart = $this->cartService->getOrCreateCart();
        $item = $cart->items()->where('product_variant_id', $variant->id)->first();
        $newQte = $item ? $item->qte + $validated['qte'] : $validated['qte'];

        if ($newQte > $variant->qte_dispo) {
            $message = $variant->sous_seuil
                ? "Stock critique pour {$variant->libelle} : seulement {$variant->qte_dispo} disponible(s) (seuil d'alerte atteint). Quantité demandée : {$newQte}."
                : "Stock insuffisant pour {$variant->libelle}. Maximum disponible : {$variant->qte_dispo}.";

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        $item
            ? $item->update(['qte' => $newQte])
            : $cart->items()->create([
                'product_id'         => $variant->product_id,
                'product_variant_id' => $variant->id,
                'qte'                => $newQte,
            ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => "Produit « {$variant->libelle} » ajouté au panier avec succès.",
                'cart_count' => $cart->items->sum('qte'),
                'cart_total' => $cart->items->sum(fn($item) => $item->qte * $item->variant->prix_vente),
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, CartItem $item){
        $this->authorizeItem($item);
        $validated = $request->validate(['qte' => ['required', 'integer', 'min:1']]);
        $variant = $item->variant;

        if (!$variant || !$variant->actif) {
            return back()->with('error', 'Ce produit n\'est plus disponible. Retirez-le de votre panier.');
        }

        if ($validated['qte'] > $variant->qte_dispo) {
            $message = $variant->sous_seuil
                ? "Stock critique pour {$variant->libelle} : seulement {$variant->qte_dispo} disponible(s) (seuil d'alerte atteint). Quantité demandée : {$validated['qte']}."
                : "Stock insuffisant pour {$variant->libelle}. Maximum disponible : {$variant->qte_dispo}.";
            return back()->with('error', $message);
        }

        $item->update(['qte' => $validated['qte']]);

        return back()->with('success', "Quantité de « {$variant->libelle} » mise à jour avec succès.");
    }

    public function destroy(CartItem $item){
        $this->authorizeItem($item);
        $item->delete();
        return back()->with('success', 'Produit retiré du panier.');
    }

    public function clear(){
        $this->cartService->getOrCreateCart()->items()->delete();
        return back()->with('success', 'Panier vidé avec succès.');
    }

    private function authorizeItem(CartItem $item): void{
        if (auth()->check()) {
            abort_unless($item->cart->user_id === auth()->id(), 403);
            return;
        }
        abort_unless($item->cart->session_id === $this->cartService->guestCartId(), 403);
    }

    /**
     * Restaure les articles d'une commande en attente (statut "panier_converti")
     * dans le panier du client, puis supprime cette commande.
     *
     * Appelée automatiquement :
     * - quand le client va sur son panier (index)
     * - quand le client ajoute un nouvel article (store)
     */
    private function restorePanierConvertiIfExists(): void
    {
        if (!auth()->check()) {
            return;
        }

        $order = Order::where('user_id', auth()->id())
            ->where('statut', 'panier_converti')
            ->with('items')
            ->first();

        if (!$order) {
            return;
        }

        $cart = $this->cartService->getOrCreateCart();

        DB::transaction(function () use ($order, $cart) {
            foreach ($order->items as $item) {
                $existing = $cart->items()
                    ->where('product_variant_id', $item->product_variant_id)
                    ->first();

                if ($existing) {
                    $existing->increment('qte', $item->qte);
                } else {
                    $cart->items()->create([
                        'product_id'         => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'qte'                => $item->qte,
                    ]);
                }
            }

            $order->delete();
        });

        session()->forget('panier_converti_order_id');
    }
}