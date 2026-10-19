@extends('layouts.auth')

@section('title', 'Changer mot de passe')

@section('content')
<h2>
    <i class="fas fa-key" style="color: #b8860b;"></i> Changer mot de passe
</h2>
<p class="sub">Mettez à jour votre mot de passe en toute sécurité</p>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px 15px; border-radius: 8px; margin-bottom: 18px; font-size: 14px;">
        ✅ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="error-box">
        <ul style="margin: 0; padding-left: 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="current_password">Mot de passe actuel</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>

    <div class="form-group">
        <label for="password">Nouveau mot de passe</label>
        <input type="password" id="password" name="password" required minlength="6">
        <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 4px;">Minimum 6 caractères</small>
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6">
    </div>

    <button type="submit">
        Mettre à jour le mot de passe
    </button>
</form>
@endsection