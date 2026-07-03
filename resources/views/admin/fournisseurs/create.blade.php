@extends('layouts.admin')

@section('title', 'Nouveau Fournisseur - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton retour -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-user-plus" style="color: #2d5a27; margin-right: 10px;"></i>
                        Nouveau fournisseur
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Ajoutez un nouveau fournisseur à votre base de données</p>
                </div>
                <a href="{{ route('admin.fournisseurs.index') }}" style="
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
                max-width: 800px;
                margin: 0 auto;
            ">
                <form action="{{ route('admin.fournisseurs.store') }}" method="POST">
                    @csrf

                    <!-- Nom et Prénom -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                        <!-- Nom -->
                        <div>
                            <label for="nom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                Nom <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
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
                                   placeholder="Ex: Dupont"
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
                                   value="{{ old('prenom') }}"
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
                                   placeholder="Ex: Jean"
                                   required>
                            @error('prenom')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div style="margin-bottom: 25px;">
                        <label for="tel" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-phone" style="color: #2d5a27; margin-right: 5px;"></i>
                            Téléphone <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               id="tel" 
                               name="tel" 
                               value="{{ old('tel') }}"
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
                               placeholder="Ex: 0555688480"
                               required>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Numéro de téléphone du fournisseur
                        </div>
                        @error('tel')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div style="margin-bottom: 25px;">
                        <label for="adress" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-map-marker-alt" style="color: #2d5a27; margin-right: 5px;"></i>
                            Adresse <span style="color: #dc3545;">*</span>
                        </label>
                        <textarea id="adress" 
                                  name="adress" 
                                  rows="3"
                                  style="
                                      width: 100%;
                                      padding: 12px 16px;
                                      border: 2px solid {{ $errors->has('adress') ? '#dc3545' : '#e8e0d5' }};
                                      border-radius: 8px;
                                      font-size: 1rem;
                                      transition: border-color 0.3s;
                                      outline: none;
                                      background: #faf8f5;
                                      resize: vertical;
                                      font-family: inherit;
                                  "
                                  onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                  onblur="this.style.borderColor='{{ $errors->has('adress') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                  placeholder="Ex: 123 Rue de la Paix, Quartier Administratif"
                                  required>{{ old('adress') }}</textarea>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Adresse complète du fournisseur
                        </div>
                        @error('adress')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Ville -->
                    <div style="margin-bottom: 30px;">
                        <label for="ville" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-city" style="color: #2d5a27; margin-right: 5px;"></i>
                            Ville <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               id="ville" 
                               name="ville" 
                               value="{{ old('ville') }}"
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
                               placeholder="Ex: Abidjan, Bouaké, Daloa..."
                               required>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Ville de résidence du fournisseur
                        </div>
                        @error('ville')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
                        <a href="{{ route('admin.fournisseurs.index') }}" style="
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
                            <i class="fas fa-save"></i> Enregistrer le fournisseur
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
        .container > div > div:last-child > form > div:first-child {
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
    }
</style>
@endpush