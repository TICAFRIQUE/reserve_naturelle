<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index(){
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');

        $total = $cart->items->sum(fn($item) => $item->qte * $item->product->prix_vente);
        $hasIssues = $cart->items->contains(function ($item) {
            $product = $item->product;
            if (!$product) {
                return true;
            }
            return $item->qte > $product->qte_dispo;
        });
        return view('client.cart.index', compact('cart', 'total', 'hasIssues'));      
    }

    public function store(Request $request){
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qte' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->where('product_id', $product->id)->first();
        $newQte = $item ? $item->qte + $validated['qte'] : $validated['qte'];

        if ($newQte > $product->qte_dispo) {
            $message = $product->sous_seuil
                ? "Stock critique pour {$product->designation} : seulement {$product->qte_dispo} disponible(s) (seuil d'alerte atteint). Quantité demandée : {$newQte}."
                   : "Stock insuffisant pour {$product->designation}. Maximum disponible : {$product->qte_dispo}.";

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        $item
            ? $item->update(['qte' => $newQte])
            : $cart->items()->create(['product_id' => $product->id, 'qte' => $newQte]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier.',
                'cart_count' => $cart->items->sum('qte'),
                'cart_total' => $cart->items->sum(fn($item) => $item->qte * $item->product->prix_vente)
            ]);
        }
        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, CartItem $item){
        $this->authorizeItem($item);
        $validated = $request->validate(['qte' => ['required', 'integer', 'min:1']]);

        if ($validated['qte'] > $item->product->qte_dispo) {
            $product = $item->product;
            $message = $product->sous_seuil
                ? "Stock critique pour {$product->designation} : seulement {$product->qte_dispo} disponible(s) (seuil d'alerte atteint). Quantité demandée : {$validated['qte']}."
                : "Stock insuffisant pour {$product->designation}. Maximum disponible : {$product->qte_dispo}.";
            return back()->with('error', $message);
        }

        $item->update(['qte' => $validated['qte']]);
        return back()->with('success', 'Panier mis à jour.');
    }

    public function destroy(CartItem $item){
        $this->authorizeItem($item);
        $item->delete();
        return back()->with('success', 'Produit retiré du panier.');
    }

    public function clear(){
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
        return back()->with('success', 'Panier vidé avec succès.');
    }

    private function getOrCreateCart(): Cart{
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['date_creation' => now()]
            );
        }

        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', (string) Str::uuid());
        }

        return Cart::firstOrCreate(
            ['session_id' => session('cart_session_id'), 'user_id' => null],
            ['date_creation' => now()]
        );
    }

    private function authorizeItem(CartItem $item): void{
        if (auth()->check()) {
            abort_unless($item->cart->user_id === auth()->id(), 403);
            return;
        }
        abort_unless($item->cart->session_id === session('cart_session_id'), 403);
    }
}