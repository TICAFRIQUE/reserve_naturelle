@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div style="max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 30px;">🔐 Connexion</h2>

    @if ($errors->any())
        <div style="background: #ffebee; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li style="color: red;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" data-recaptcha-action="login">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="tel" style="display: block; margin-bottom: 5px; font-weight: bold;">Téléphone</label>
            <input type="tel" id="tel" name="tel" value="{{ old('tel') }}"
                   inputmode="tel" autocomplete="username"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
                   required autofocus>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Mot de passe</label>
            <input type="password" id="password" name="password" autocomplete="current-password"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
                   required>
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
            Se connecter
        </button>
        <a href="{{ route('register') }}" style="display: block; text-align: center; margin-top: 15px; color: #4CAF50; text-decoration: none; font-weight: bold;">
            S'inscrire
        </a>
    </form>
</div>
@endsection