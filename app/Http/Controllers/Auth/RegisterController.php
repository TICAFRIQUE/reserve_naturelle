<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create(Request $request){
        if ($request->has('redirect')) {
            $redirectUrl = $request->redirect;
            if (str_starts_with($redirectUrl, url('/'))) {
                session()->put('url.intended', $redirectUrl);
            }
        }
        return view('auth.register');
    }

    public function store(Request $request, CartService $carts){
        // 1. Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'tel' => ['required', 'regex:/^[0-9]{8,15}$/'],
        ],
        [
            'tel.regex' => 'Le numéro de téléphone doit contenir uniquement des chiffres (8 à 15 chiffres).'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Création de l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tel' => $request->tel,
            'role' => 'user',
        ]);

        // 3. Sauvegarder avant regenerate()
        $intended = $request->session()->get('url.intended');
        $cartSessionId = session('cart_session_id');

        // 4. Connexion + régénération de session
        Auth::login($user);
        $request->session()->regenerate();

        if ($intended) {
            $request->session()->put('url.intended', $intended);
        }

        // 5. Fusion du panier invité
        $carts->mergeGuestCart($user, $cartSessionId);

        // 6. Redirection après inscription
        if ($intended) {
            return redirect()->intended($intended)->with('success', 'Bienvenue ' . $user->nom . ' ' . $user->prenom . ' !');
        }

        return redirect()->route('client.products.index')->with('success', 'Bienvenue ' . $user->nom . ' ' . $user->prenom . ' !');
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);
        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }
}