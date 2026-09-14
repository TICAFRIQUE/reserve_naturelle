<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;

class CartService
{
    /**
     * Fusionne le panier invité (identifié par session_id) dans le panier
     * de l'utilisateur qui vient de se connecter ou de s'inscrire.
     *
     * Appelée depuis AuthController::store() et RegisterController::store()
     * juste après Auth::login() / session()->regenerate().
     */
    public function mergeGuestCart(User $user, ?string $sessionId): void
    {
        if (!$sessionId) {
            return;
        }

        $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();

        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['date_creation' => now()]
        );

        foreach ($guestCart->items as $item) {
            $existing = $userCart->items()->where('product_id', $item->product_id)->first();

            $existing
                ? $existing->increment('qte', $item->qte)
                : $userCart->items()->create(['product_id' => $item->product_id, 'qte' => $item->qte]);
        }

        $guestCart->delete();
        session()->forget('cart_session_id');
    }
}