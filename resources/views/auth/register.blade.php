@extends('layouts.auth')

@section('title', 'Inscription')

@section('content')
<div style="max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 30px;">📝 Inscription</h2>

    @if ($errors->any())
        <div style="background: #ffebee; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li style="color: red;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="register-form">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div style="margin-bottom: 15px;">
                <label for="nom" style="display: block; margin-bottom: 5px; font-weight: bold;">Nom</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
                       required>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="prenom" style="display: block; margin-bottom: 5px; font-weight: bold;">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
                       required>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="tel" style="display: block; margin-bottom: 5px; font-weight: bold;">Téléphone</label>
            <input type="tel" id="tel" name="tel" value="{{ old('tel') }}"
                   inputmode="tel" autocomplete="tel"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
                   required>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Email <small style="font-weight: normal; color: #666;">(optionnel)</small>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   autocomplete="email"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
            <small id="email-feedback" style="color: #666;"></small>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Mot de passe</label>
            <input type="password" id="password" name="password" autocomplete="new-password"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
                   required>
            <small style="color: #666;">Minimum 8 caractères</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password_confirmation" style="display: block; margin-bottom: 5px; font-weight: bold;">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
                   required>
        </div>
        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        <button type="submit" style="width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
            S'inscrire
        </button>
        <a href="{{ route('login') }}" style="display: block; text-align: center; margin-top: 15px; color: #4CAF50; text-decoration: none; font-weight: bold;">
            Se connecter
        </a>
    </form>
</div>

@push('scripts')
<script>
    // Vérification en temps réel de l'email (uniquement s'il est renseigné)
    document.getElementById('email').addEventListener('blur', function () {
        const email = this.value.trim();
        const feedback = document.getElementById('email-feedback');

        if (!email) { feedback.textContent = ''; return; }

        fetch('{{ route("check.email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email })
        })
        .then(r => r.json())
        .then(data => {
            feedback.style.color = data.available ? 'green' : 'red';
            feedback.textContent = data.available ? '✅ Email disponible' : '❌ Email déjà utilisé';
        })
        .catch(() => { feedback.textContent = ''; });
    });
</script>
@endpush
@endsection