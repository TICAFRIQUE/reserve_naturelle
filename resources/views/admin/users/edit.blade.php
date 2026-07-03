@extends('layouts.admin')

@section('title', 'Modifier un utilisateur - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton retour -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-user-edit" style="color: #b8860b; margin-right: 10px;"></i>
                        Modifier l'utilisateur
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-user" style="color: #b8860b;"></i>
                        Modification de : <strong style="color: #2d5a27;">{{ $user->full_name }}</strong>
                    </p>
                </div>
                <a href="{{ route('admin.users.index') }}" style="
                    background: #e8e0d5;
                    color: #2d5a27;
                    padding: 12px 24px;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <!-- Messages d'erreur -->
            @if(session('error'))
                <div style="
                    background: #f8d7da;
                    color: #721c24;
                    padding: 12px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #dc3545;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="
                    background: #f8d7da;
                    color: #721c24;
                    padding: 15px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #dc3545;
                    margin-bottom: 20px;
                ">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-exclamation-circle" style="font-size: 20px; margin-top: 2px;"></i>
                        <div>
                            <strong>Des erreurs sont survenues :</strong>
                            <ul style="margin: 5px 0 0 20px; padding: 0;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Information sur l'utilisateur -->
            <div style="
                background: #f8f5f0;
                padding: 15px 20px;
                border-radius: 8px;
                margin-bottom: 25px;
                border-left: 4px solid #b8860b;
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                align-items: center;
            ">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="
                        width: 45px;
                        height: 45px;
                        border-radius: 50%;
                        background: {{ $user->role === 'admin' ? '#2d5a27' : ($user->role === 'fournisseur' ? '#b8860b' : '#6c757d') }};
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: bold;
                        font-size: 16px;
                    ">
                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #2d5a27; font-size: 1.05rem;">{{ $user->full_name }}</div>
                        <div style="color: #6c757d; font-size: 0.85rem;">
                            <i class="fas fa-envelope" style="color: #b8860b;"></i> {{ $user->email }}
                            @if($user->email_verified_at)
                                <span style="
                                    background: #d4edda;
                                    color: #155724;
                                    padding: 2px 10px;
                                    border-radius: 12px;
                                    font-size: 0.7rem;
                                    margin-left: 8px;
                                ">
                                    <i class="fas fa-check-circle"></i> Vérifié
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div style="margin-left: auto; display: flex; gap: 15px;">
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #2d5a27;">Rôle</div>
                        <span style="
                            background: {{ $user->role === 'admin' ? '#2d5a27' : ($user->role === 'fournisseur' ? '#b8860b' : '#e8e0d5') }};
                            color: {{ $user->role === 'user' ? '#2d5a27' : 'white' }};
                            padding: 4px 16px;
                            border-radius: 20px;
                            font-size: 0.85rem;
                            display: inline-block;
                            margin-top: 2px;
                        ">
                            <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'fournisseur' ? 'truck' : 'user') }}"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #2d5a27;">Inscrit le</div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-top: 2px;">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                overflow: hidden;
                border: 1px solid #e8e0d5;
                padding: 40px;
            ">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                        <!-- Nom -->
                        <div>
                            <label for="nom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                Nom <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $user->nom) }}"
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('nom') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('nom') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   required>
                            @error('nom')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Prénom -->
                        <div>
                            <label for="prenom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                Prénom <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="text" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="{{ old('prenom', $user->prenom) }}"
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('prenom') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('prenom') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   required>
                            @error('prenom')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                        <!-- Email -->
                        <div>
                            <label for="email" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-envelope" style="color: #b8860b; margin-right: 5px;"></i>
                                Email <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('email') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('email') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   required>
                            @error('email')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="tel" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-phone" style="color: #b8860b; margin-right: 5px;"></i>
                                Téléphone
                            </label>
                            <input type="text" 
                                   id="tel" 
                                   name="tel" 
                                   value="{{ old('tel', $user->tel) }}"
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('tel') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('tel') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   placeholder="Ex: 0555688480">
                            @error('tel')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                        <!-- Mot de passe -->
                        <div>
                            <label for="password" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-lock" style="color: #b8860b; margin-right: 5px;"></i>
                                Nouveau mot de passe
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('password') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('password') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   placeholder="Laisser vide pour conserver l'actuel">
                            <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Minimum 8 caractères - Laissez vide pour ne pas changer
                            </div>
                            @error('password')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div>
                            <label for="password_confirmation" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-check-circle" style="color: #b8860b; margin-right: 5px;"></i>
                                Confirmer le mot de passe
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid #e8e0d5;
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='#e8e0d5'; this.style.background='#faf8f5'"
                                   placeholder="Confirmer le nouveau mot de passe">
                        </div>
                    </div>

                    <!-- Rôle -->
                    <div style="margin-bottom: 35px;">
                        <label for="role" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-tag" style="color: #b8860b; margin-right: 5px;"></i>
                            Rôle <span style="color: #dc3545;">*</span>
                        </label>
                        <select id="role" 
                                name="role" 
                                style="
                                    width: 100%;
                                    max-width: 400px;
                                    padding: 12px 16px;
                                    border: 2px solid {{ $errors->has('role') ? '#dc3545' : '#e8e0d5' }};
                                    border-radius: 8px;
                                    font-size: 1rem;
                                    transition: border-color 0.3s;
                                    outline: none;
                                    background: #faf8f5;
                                    cursor: pointer;
                                "
                                onfocus="this.style.borderColor='#2d5a27'"
                                onblur="this.style.borderColor='{{ $errors->has('role') ? '#dc3545' : '#e8e0d5' }}'"
                                required>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="fournisseur" {{ old('role', $user->role) === 'fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Utilisateur</option>
                        </select>
                        @if($user->role === 'admin' && \App\Models\User::where('role', 'admin')->count() <= 1)
                            <div style="color: #b8860b; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-triangle"></i> Vous ne pouvez pas changer le rôle du dernier administrateur.
                            </div>
                        @endif
                        @error('role')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
                        <a href="{{ route('admin.users.index') }}" style="
                            background: #e8e0d5;
                            color: #2d5a27;
                            padding: 12px 30px;
                            border-radius: 30px;
                            text-decoration: none;
                            font-weight: 500;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" style="
                            background: #2d5a27;
                            color: white;
                            padding: 12px 35px;
                            border-radius: 30px;
                            border: none;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            font-size: 1rem;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 20px 15px !important;
        }
        .container > div > div > div:last-child {
            padding: 25px 20px !important;
        }
        form > div:first-child,
        form > div:nth-child(2),
        form > div:nth-child(3) {
            grid-template-columns: 1fr !important;
            gap: 15px !important;
        }
        select {
            max-width: 100% !important;
        }
        .container > div > div:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .container > div > div:first-child > div:last-child {
            width: 100%;
        }
        .container > div > div:first-child > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        .container > div > div:nth-child(3) {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .container > div > div:nth-child(3) > div:last-child {
            margin-left: 0 !important;
            flex-wrap: wrap;
        }
        form > div:last-child {
            flex-direction: column !important;
        }
        form > div:last-child a,
        form > div:last-child button {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush