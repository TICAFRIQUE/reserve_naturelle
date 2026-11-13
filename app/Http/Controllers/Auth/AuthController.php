<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create(Request $request){
        if ($redirectUrl = $this->safeIntendedUrl($request->query('redirect'))) {
            session()->put('url.intended', $redirectUrl);
        }
        return view('auth.login');
    }

    public function store(Request $request, CartService $carts){
        $request->validate([
            'tel'      => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Même normalisation qu'à l'inscription : chiffres uniquement
        $tel      = preg_replace('/\D/', '', $request->tel);
        $remember = $request->boolean('remember');

        if (! Auth::attempt(['tel' => $tel, 'password' => $request->password], $remember)) {
            return back()
                ->withErrors(['tel' => 'Téléphone ou mot de passe incorrect.'])
                ->withInput($request->only('tel'));
        }

        // Sauvegarder avant regenerate()
        $pending       = $request->session()->get('reservation_pending');
        $intended      = $request->session()->get('url.intended');
        $cartSessionId = $request->cookie('cart_uuid');

        $request->session()->regenerate();

        // Restaurer après regenerate()
        if ($pending) {
            $request->session()->put('reservation_pending', $pending);
        }
        if ($intended) {
            $request->session()->put('url.intended', $intended);
        }

        $user = Auth::user();
        $carts->mergeGuestCart($user, $cartSessionId);

        return $user->isAdmin()
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(route('client.products.index'));
    }

    public function destroy(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}