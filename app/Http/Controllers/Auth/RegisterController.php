<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // 1. Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'tel' => ['required', 'string', 'regex:/^[0-9]{8,15}$/'],
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

        // 3. Connexion automatique
        Auth::login($user);

        // 4. Redirection après inscription
        $intended = session()->get('url.intended');
        if ($intended) {
            return redirect()->intended($intended);
        }

        // Message de bienvenue
        return redirect()->route('client.products.index')->with('success', 'Bienvenue '  . $user->nom  . ' ' . $user->prenom. ' !');
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