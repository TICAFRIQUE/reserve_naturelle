<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request, CartService $carts){
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
            $cartSessionId = session('cart_session_id');
            $request->session()->regenerate();

            // Restaurer après avoir regénéré
            if ($pending) {
                $request->session()->put("reservation_pending", $pending);
            }
            if ($intended) {
                $request->session()->put("url.intended", $intended);
            }

            $user = Auth::user();

            $carts->mergeGuestCart($user, $cartSessionId);

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