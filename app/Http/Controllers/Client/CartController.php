<?php

namespace App\Http\Controllers\Client;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(){
        $cart = $this->cartService->getOrCreateCart();
        $cart->load('items.product');

        $total = $cart->items->sum(fn($item) => $item->qte * $item->product->prix_vente);
        $hasIssues = $cart->items->contains(fn($item) => !$item->product || $item->qte > $item->product->qte_dispo);
        return view('client.cart.index', compact('cart', 'total', 'hasIssues'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qte' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = $this->cartService->getOrCreateCart();
        $item = $cart->items()->where('product_id', $product->id)->first();
        $newQte = $item ? $item->qte + $validated['qte'] : $validated['qte'];

        if ($newQte > $product->qte_dispo) {
            $message = $product->sous_seuil
                ? "Stock critique pour {$product->designation} : seulement {$product->qte_dispo} disponible(s) (seuil d'alerte atteint). Quantité demandée : {$newQte}."
                : "Stock insuffisant pour {$product->designation}. Maximum disponible : {$product->qte_dispo}.";

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        $item
            ? $item->update(['qte' => $newQte])
            : $cart->items()->create(['product_id' => $product->id, 'qte' => $newQte]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Produit « {$product->designation} » ajouté au panier avec succès.",
                'cart_count' => $cart->items->sum('qte'),
                'cart_total' => $cart->items->sum(fn($item) => $item->qte * $item->product->prix_vente),
            ]);
        }
        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, CartItem $item){
        $this->authorizeItem($item);
        $validated = $request->validate(['qte' => ['required', 'integer', 'min:1']]);
        $product = $item->product;

        if ($validated['qte'] > $product->qte_dispo) {
            $message = $product->sous_seuil
                ? "Stock critique pour {$product->designation} : seulement {$product->qte_dispo} disponible(s) (seuil d'alerte atteint). Quantité demandée : {$validated['qte']}."
                : "Stock insuffisant pour {$product->designation}. Maximum disponible : {$product->qte_dispo}.";
            return back()->with('error', $message);
        }
        $item->update(['qte' => $validated['qte']]);
        return back()->with('success', "Quantité de « {$product->designation} » mise à jour avec succès.");
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
}