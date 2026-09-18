<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    private ?string $resolvedGuestCartId = null;

    public function getOrCreateCart(): Cart{
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['date_creation' => now()]
            );
        }

        return Cart::firstOrCreate(
            ['session_id' => $this->guestCartId(), 'user_id' => null],
            ['date_creation' => now()]
        );
    }

    public function guestCartId(): string{
        // Cache mémoire : évite de générer 2 UUID différents si appelé
        // depuis le middleware ET le controller sur la même requête
        // (un cookie fraîchement Cookie::queue() n'est pas relisible via request()->cookie() avant le prochain aller-retour HTTP)
        if ($this->resolvedGuestCartId) {
            return $this->resolvedGuestCartId;
        }

        $id = request()->cookie('cart_uuid');

        if (!$id) {
            $id = (string) Str::uuid();
            Cookie::queue('cart_uuid', $id, 60 * 24 * 365); // 1 an
        }
        return $this->resolvedGuestCartId = $id;
    }

    public function mergeGuestCart(User $user, ?string $guestCartId): void{
        if (!$guestCartId) {
            return;
        }
        $guestCart = Cart::where('session_id', $guestCartId)->whereNull('user_id')->first();
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
        Cookie::queue(Cookie::forget('cart_uuid'));
    }
}