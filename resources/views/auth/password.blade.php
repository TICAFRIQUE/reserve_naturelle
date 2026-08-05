@extends('layouts.app')

@section('title', 'Changer mot de passe')

@section('content')
<div style="max-width: 500px; margin: 60px auto; padding: 0 20px;">
    <div style="background: white; padding: 35px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border: 1px solid #e8e0d5;">
        <h2 style="color: #2d5a27; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-key"></i> Changer mot de passe
        </h2>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px;">
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

            <div style="margin-bottom: 18px;">
                <label for="current_password" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                    Mot de passe actuel
                </label>
                <input type="password" id="current_password" name="current_password" required
                       style="width: 100%; padding: 10px 14px; border: 2px solid #e8e0d5; border-radius: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 18px;">
                <label for="password" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                    Nouveau mot de passe
                </label>
                <input type="password" id="password" name="password" required minlength="6"
                       style="width: 100%; padding: 10px 14px; border: 2px solid #e8e0d5; border-radius: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 25px;">
                <label for="password_confirmation" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                    Confirmer le nouveau mot de passe
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                       style="width: 100%; padding: 10px 14px; border: 2px solid #e8e0d5; border-radius: 8px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background: #2d5a27; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem;">
                Mettre à jour le mot de passe
            </button>
        </form>
    </div>
</div>
@endsection