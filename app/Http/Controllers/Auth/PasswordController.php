<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit(){
        return view('auth.password');
    }

    public function update(Request $request){
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required','string',Password::min(8)->letters()->numbers(),'different:current_password','confirmed',],
        ]);

        if (!Hash::check($validated['current_password'], Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Mot de passe actuel incorrect.',
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        Auth::logoutOtherDevices($validated['password']);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Mot de passe modifié avec succès.');
    }
}