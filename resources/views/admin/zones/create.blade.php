@extends('layouts.admin')

@section('title', 'Ajouter une Zone - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-map-marker-alt" style="color: #2d5a27; margin-right: 10px;"></i>
                        Ajouter une zone
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-route" style="color: #2d5a27; margin-right: 5px;"></i>
                        Créez une zone de livraison avec son tarif
                    </p>
                </div>
                <a href="{{ route('admin.zones.index') }}" style="
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
                <form action="{{ route('admin.zones.store') }}" method="POST">
                    @csrf

                    <!-- Nom de la zone -->
                    <div style="margin-bottom: 25px;">
                        <label for="nom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-map-marker-alt" style="color: #2d5a27; margin-right: 5px;"></i>
                            Nom de la zone <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               name="nom" 
                               id="nom" 
                               value="{{ old('nom') }}" 
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
                               placeholder="Ex: Abidjan, Zone 1, Autres villes">
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Ex: "Abidjan" pour les communes locales, "Autres" pour l'expédition
                        </div>
                        @error('nom')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tarif -->
                    <div style="margin-bottom: 25px;">
                        <label for="tarif" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-coins" style="color: #2d5a27; margin-right: 5px;"></i>
                            Tarif (FCFA) <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="number" 
                               name="tarif" 
                               id="tarif" 
                               value="{{ old('tarif') }}" 
                               step="100" 
                               min="0" 
                               required 
                               style="
                                   width: 100%;
                                   padding: 12px 16px;
                                   border: 2px solid {{ $errors->has('tarif') ? '#dc3545' : '#e8e0d5' }};
                                   border-radius: 8px;
                                   font-size: 1rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: #faf8f5;
                               "
                               onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                               onblur="this.style.borderColor='{{ $errors->has('tarif') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                               placeholder="Ex: 1500">
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Tarif de livraison pour cette zone
                        </div>
                        @error('tarif')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Type de zone -->
                    <div style="margin-bottom: 30px;">
                        <label style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i>
                            Type de zone <span style="color: #dc3545;">*</span>
                        </label>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <!-- Livraison locale -->
                            <label style="
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                padding: 14px 20px;
                                background: {{ old('est_expedition', 0) == 0 ? '#e8f5e9' : '#faf8f5' }};
                                border: 2px solid {{ old('est_expedition', 0) == 0 ? '#2d5a27' : '#e8e0d5' }};
                                border-radius: 8px;
                                cursor: pointer;
                                flex: 1;
                                transition: all 0.3s ease;
                            " onmouseover="this.style.borderColor='#2d5a27'" onmouseout="this.style.borderColor='{{ old('est_expedition', 0) == 0 ? '#2d5a27' : '#e8e0d5' }}'">
                                <input type="radio" name="est_expedition" value="0" {{ old('est_expedition', 0) == 0 ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2d5a27;">
                                <div>
                                    <span style="font-weight: 600; color: #2d5a27;">
                                        <i class="fas fa-city" style="color: #2d5a27;"></i> Livraison locale
                                    </span>
                                    <div style="color: #6c757d; font-size: 0.8rem;">Communes et quartiers d'Abidjan</div>
                                </div>
                            </label>

                            <!-- Expédition -->
                            <label style="
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                padding: 14px 20px;
                                background: {{ old('est_expedition', 0) == 1 ? '#fff3cd' : '#faf8f5' }};
                                border: 2px solid {{ old('est_expedition', 0) == 1 ? '#b8860b' : '#e8e0d5' }};
                                border-radius: 8px;
                                cursor: pointer;
                                flex: 1;
                                transition: all 0.3s ease;
                            " onmouseover="this.style.borderColor='#b8860b'" onmouseout="this.style.borderColor='{{ old('est_expedition', 0) == 1 ? '#b8860b' : '#e8e0d5' }}'">
                                <input type="radio" name="est_expedition" value="1" {{ old('est_expedition', 0) == 1 ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #b8860b;">
                                <div>
                                    <span style="font-weight: 600; color: #b8860b;">
                                        <i class="fas fa-shipping-fast" style="color: #b8860b;"></i> Expédition
                                    </span>
                                    <div style="color: #6c757d; font-size: 0.8rem;">Hors Abidjan, livraison nationale</div>
                                </div>
                            </label>
                        </div>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> Sélectionnez le type de zone de livraison
                        </div>
                        @error('est_expedition')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
                        <a href="{{ route('admin.zones.index') }}" style="
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
                            <i class="fas fa-save"></i> Enregistrer la zone
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
        .container > div > div:last-child {
            padding: 25px 20px !important;
            margin: 0 !important;
        }
        .container > div > div:last-child > form > div:nth-child(3) {
            flex-direction: column !important;
        }
        .container > div > div:last-child > form > div:last-child {
            flex-direction: column !important;
        }
        .container > div > div:last-child > form > div:last-child a,
        .container > div > div:last-child > form > div:last-child button {
            width: 100%;
            justify-content: center;
        }
        .container > div > div:last-child > form > div:nth-child(3) label {
            flex: unset !important;
        }
    }
</style>
@endpush