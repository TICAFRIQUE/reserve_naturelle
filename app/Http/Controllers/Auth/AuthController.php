<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class AuthController extends Controller
{

    public function create(Request $request){
        if ($request->has('redirect')) {
            $redirectUrl = $request->redirect;
            if (str_starts_with($redirectUrl, url('/'))) {
                session()->put('url.intended', $redirectUrl);
            }
        }
        return view('auth.login');
    }

    public function store(Request $request){
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required", "string"],
        ]);
        
        $remember = $request->boolean("remember");

        if (Auth::attempt([
            "email" => $credentials["email"],
            "password" => $credentials["password"],
        ], $remember)) {

            // Sauvegarder avant de regenerate()
            $pending = $request->session()->get("reservation_pending");
            $intended = $request->session()->get("url.intended");

            $request->session()->regenerate();  

            // Restaurer après avoir regénéré
            if ($pending) {
                $request->session()->put("reservation_pending", $pending);
            }
            if ($intended) {
                $request->session()->put("url.intended", $intended);
            }

            $user = Auth::user();

            // Fusionner le panier invité avec le panier de l'utilisateur connecté
            if (session()->has('cart_session_id')) {
                $guestCart = Cart::where('session_id', session('cart_session_id'))->whereNull('user_id')->first();

                if ($guestCart) {
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

            // Si admin alors dashboard sinon page client
            if ($user->isAdmin()) {
                return redirect()->intended(route("admin.dashboard"));
            }
            return redirect()->intended(route('client.products.index'));  
        }

        return back()->withErrors(["email" => "Identifiants invalides ou compte inactif"])->withInput($request->only("email"));      
    }

    public function destroy(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/");
    }
}