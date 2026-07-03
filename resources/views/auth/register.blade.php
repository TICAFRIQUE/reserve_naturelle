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

    <form method="POST" action="{{ route('register') }}">
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
            <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" 
                   required>
            <small id="email-feedback" style="color: #666;"></small>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="tel" style="display: block; margin-bottom: 5px; font-weight: bold;">Téléphone</label>
            <input type="text" id="tel" name="tel" value="{{ old('tel') }}" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" 
                   required>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Mot de passe</label>
            <input type="password" id="password" name="password" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" 
                   required>
            <small style="color: #666;">Minimum 8 caractères</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password_confirmation" style="display: block; margin-bottom: 5px; font-weight: bold;">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" 
                   required>
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
            S'inscrire
        </button>
    </form>
</div>

@push('scripts')
<script>
    // Vérification en temps réel de l'email (AJAX)
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        const feedback = document.getElementById('email-feedback');
        
        if (email.length > 0) {
            fetch('{{ route("check.email") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    feedback.style.color = 'green';
                    feedback.textContent = '✅ Email disponible';
                } else {
                    feedback.style.color = 'red';
                    feedback.textContent = '❌ Email déjà utilisé';
                }
            });
        }
    });
</script>
@endpush
@endsection