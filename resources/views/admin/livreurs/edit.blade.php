@extends('layouts.admin')

@section('title', 'Modifier ' . $livreur->nom . ' - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-user-edit" style="color: #2d5a27; margin-right: 10px;"></i>
                        Modifier {{ $livreur->nom }}
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-motorcycle" style="color: #2d5a27; margin-right: 5px;"></i>
                        Modification des informations du livreur
                    </p>
                </div>
                <a href="{{ route('admin.livreurs.index') }}" style="
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

            <!-- Information sur le livreur -->
            <div style="
                background: #f8f5f0;
                padding: 15px 20px;
                border-radius: 8px;
                margin-bottom: 25px;
                border-left: 4px solid #2d5a27;
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
                        background: #2d5a27;
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: bold;
                        font-size: 16px;
                    ">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #2d5a27; font-size: 1.05rem;">{{ $livreur->nom }}</div>
                        <div style="font-weight: 600; color: #2d5a27; font-size: 1.05rem;">{{ $livreur->prenom }}</div>
                        <div style="color: #6c757d; font-size: 0.85rem;">
                            <i class="fas fa-phone" style="color: #2d5a27;"></i> {{ $livreur->tel }}
                        </div>
                    </div>
                </div>
                <div style="margin-left: auto; display: flex; gap: 20px;">
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #2d5a27; font-size: 0.85rem;">Ville</div>
                        <span style="
                            background: #e8f5e9;
                            color: #2d5a27;
                            padding: 4px 14px;
                            border-radius: 20px;
                            font-size: 0.8rem;
                            display: inline-block;
                            margin-top: 2px;
                        ">
                            <i class="fas fa-city"></i> {{ $livreur->ville ?? '—' }}
                        </span>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #2d5a27; font-size: 0.85rem;">Statut</div>
                        <span style="
                            background: {{ $livreur->statut === 'disponible' ? '#d4edda' : '#f8d7da' }};
                            color: {{ $livreur->statut === 'disponible' ? '#155724' : '#721c24' }};
                            padding: 4px 14px;
                            border-radius: 20px;
                            font-size: 0.8rem;
                            display: inline-block;
                            margin-top: 2px;
                        ">
                            <i class="fas {{ $livreur->statut === 'disponible' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $livreur->statut === 'disponible' ? 'Disponible' : 'Indisponible' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                padding: 40px;
                max-width: 700px;
                margin: 0 auto;
            ">
                <form action="{{ route('admin.livreurs.update', $livreur->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nom -->
                    <div style="margin-bottom: 25px;">
                        <label for="nom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-user" style="color: #2d5a27; margin-right: 5px;"></i>
                            Nom <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               name="nom" 
                               id="nom" 
                               value="{{ old('nom', $livreur->nom) }}" 
                               required 
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
                               placeholder="Ex: Jean Kouadio">
                        @error('nom')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!-- prenom -->
                    <div style="margin-bottom: 25px;">
                        <label for="nom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-user" style="color: #2d5a27; margin-right: 5px;"></i>
                            Prenom <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               name="prenom" 
                               id="prenom" 
                               value="{{ old('prenom', $livreur->prenom) }}" 
                               required 
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
                               placeholder="Ex: Jean Kouadio">
                        @error('nom')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div style="margin-bottom: 25px;">
                        <label for="tel" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-phone" style="color: #2d5a27; margin-right: 5px;"></i>
                            Téléphone <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               name="tel" 
                               id="tel" 
                               value="{{ old('tel', $livreur->tel) }}" 
                               required 
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
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Numéro de téléphone du livreur
                        </div>
                        @error('tel')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Ville et Quartier -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                        <!-- Ville -->
                        <div>
                            <label for="ville" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-city" style="color: #2d5a27; margin-right: 5px;"></i>
                                Ville
                            </label>
                            <input type="text" 
                                   name="ville" 
                                   id="ville" 
                                   value="{{ old('ville', $livreur->ville) }}" 
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('ville') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('ville') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   placeholder="Ex: Abidjan">
                            @error('ville')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Quartier -->
                        <div>
                            <label for="quartier" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-map-marker-alt" style="color: #2d5a27; margin-right: 5px;"></i>
                                Quartier
                            </label>
                            <input type="text" 
                                   name="quartier" 
                                   id="quartier" 
                                   value="{{ old('quartier', $livreur->quartier) }}" 
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('quartier') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('quartier') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   placeholder="Ex: Cocody">
                            @error('quartier')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Statut -->
                    <div style="margin-bottom: 30px;">
                        <label for="statut" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-toggle-on" style="color: #2d5a27; margin-right: 5px;"></i>
                            Statut <span style="color: #dc3545;">*</span>
                        </label>
                        <select name="statut" 
                                id="statut" 
                                required
                                style="
                                    width: 100%;
                                    max-width: 400px;
                                    padding: 12px 16px;
                                    border: 2px solid {{ $errors->has('statut') ? '#dc3545' : '#e8e0d5' }};
                                    border-radius: 8px;
                                    font-size: 1rem;
                                    transition: border-color 0.3s;
                                    outline: none;
                                    background: #faf8f5;
                                    cursor: pointer;
                                "
                                onfocus="this.style.borderColor='#2d5a27'"
                                onblur="this.style.borderColor='{{ $errors->has('statut') ? '#dc3545' : '#e8e0d5' }}'">
                            <option value="disponible" {{ old('statut', $livreur->statut) == 'disponible' ? 'selected' : '' }}>
                                ✅ Disponible
                            </option>
                            <option value="indisponible" {{ old('statut', $livreur->statut) == 'indisponible' ? 'selected' : '' }}>
                                ❌ Indisponible
                            </option>
                        </select>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Détermine si le livreur peut être assigné à des livraisons
                        </div>
                        @error('statut')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
                        <a href="{{ route('admin.livreurs.index') }}" style="
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
            justify-content: center !important;
        }
        .container > div > div:last-child {
            padding: 25px 20px !important;
            margin: 0 !important;
        }
        .container > div > div:last-child > form > div:nth-child(3) {
            grid-template-columns: 1fr !important;
            gap: 15px !important;
        }
        .container > div > div:last-child > form > div:last-child {
            flex-direction: column !important;
        }
        .container > div > div:last-child > form > div:last-child a,
        .container > div > div:last-child > form > div:last-child button {
            width: 100%;
            justify-content: center;
        }
        select {
            max-width: 100% !important;
        }
    }
</style>
@endpush