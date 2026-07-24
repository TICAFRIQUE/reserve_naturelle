@extends('layouts.admin')

@section('title', 'Ajouter un produit - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton retour -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-plus-circle" style="color: #2d5a27; margin-right: 10px;"></i>
                        Ajouter un produit
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-box" style="color: #2d5a27; margin-right: 5px;"></i>
                        Ajoutez un nouveau produit à votre catalogue
                    </p>
                </div>
                <a href="{{ route('admin.produits.index') }}" style="
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
            ">
                <form action="{{ route('admin.produits.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                        <!-- Désignation -->
                        <div style="margin-bottom: 25px;">
                            <label for="designation" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i>
                                Désignation <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="text" 
                                id="designation" 
                                name="designation" 
                                value="{{ old('designation') }}"
                                style="
                                    width: 100%;
                                    padding: 12px 16px;
                                    border: 2px solid {{ $errors->has('designation') ? '#dc3545' : '#e8e0d5' }};
                                    border-radius: 8px;
                                    font-size: 1rem;
                                    transition: border-color 0.3s;
                                    outline: none;
                                    background: #faf8f5;
                                "
                                onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                onblur="this.style.borderColor='{{ $errors->has('designation') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                placeholder="Ex: T-shirt Coton Bio"
                                required>
                            @error('designation')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                    <!-- Catégorie, sous categorie, Prix et Quantité en stock -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                        <!-- Catégorie -->
                        <div>
                            <label for="category_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-folder" style="color: #2d5a27; margin-right: 5px;"></i>
                                Catégorie
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    style="
                                        width: 100%;
                                        padding: 12px 16px;
                                        border: 2px solid {{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }};
                                        border-radius: 8px;
                                        font-size: 1rem;
                                        transition: border-color 0.3s;
                                        outline: none;
                                        background: #faf8f5;
                                        cursor: pointer;
                                    "
                                    onfocus="this.style.borderColor='#2d5a27'"
                                    onblur="this.style.borderColor='{{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }}'">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Catégorie du produit
                            </div>
                            @error('category_id')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="category_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-folder" style="color: #2d5a27; margin-right: 5px;"></i>
                                Sous catégorie
                            </label>
                            <select id="sous_category_id" 
                                    name="sous_category_id" 
                                    style="
                                        width: 100%;
                                        padding: 12px 16px;
                                        border: 2px solid {{ $errors->has('sous_category_id') ? '#dc3545' : '#e8e0d5' }};
                                        border-radius: 8px;
                                        font-size: 1rem;
                                        transition: border-color 0.3s;
                                        outline: none;
                                        background: #faf8f5;
                                        cursor: pointer;
                                    "
                                    onfocus="this.style.borderColor='#2d5a27'"
                                    onblur="this.style.borderColor='{{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }}'">
                                <option value="">Sélectionner une sous catégorie</option>
                                @foreach($sous_categories as $sous_category)
                                    <option value="{{ $sous_category->id }}" 
                                        {{ old('sous_category_id') == $sous_category->id ? 'selected' : '' }}>
                                        {{ $sous_category->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> sous catégorie du produit
                            </div>
                            @error('category_id')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>  
                        <!-- Prix de vente -->
                        <div>
                            <label for="prix_vente" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-money-bill-wave" style="color: #2d5a27; margin-right: 5px;"></i>
                                Prix de vente <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="number" 
                                   id="prix_vente" 
                                   name="prix_vente" 
                                   value="{{ old('prix_vente') }}"
                                   step="100"
                                   min="0"
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('prix_vente') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('prix_vente') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   placeholder="Ex: 5000"
                                   required>
                            <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Prix de vente en FCFA
                            </div>
                            @error('prix_vente')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- Stock -->
                    <div style="margin-bottom: 25px;">
                        <label for="qte_dispo" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-cubes" style="color: #2d5a27; margin-right: 5px;"></i>
                            Quantité disponible <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="number" 
                               id="qte_dispo" 
                               name="qte_dispo" 
                               value="{{ old('qte_dispo', 0) }}"
                               min="0"
                               style="
                                   width: 100%;
                                   max-width: 300px;
                                   padding: 12px 16px;
                                   border: 2px solid {{ $errors->has('qte_dispo') ? '#dc3545' : '#e8e0d5' }};
                                   border-radius: 8px;
                                   font-size: 1rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: #faf8f5;
                               "
                               onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                               onblur="this.style.borderColor='{{ $errors->has('qte_dispo') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                               required>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Quantité initiale en stock
                        </div>
                        @error('qte_dispo')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    </div>

                    <!-- Description -->
                    <div style="margin-bottom: 25px;">
                        <label for="description" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-align-left" style="color: #2d5a27; margin-right: 5px;"></i>
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  style="
                                      width: 100%;
                                      padding: 12px 16px;
                                      border: 2px solid {{ $errors->has('description') ? '#dc3545' : '#e8e0d5' }};
                                      border-radius: 8px;
                                      font-size: 1rem;
                                      transition: border-color 0.3s;
                                      outline: none;
                                      background: #faf8f5;
                                      resize: vertical;
                                      font-family: inherit;
                                  "
                                  onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                  onblur="this.style.borderColor='{{ $errors->has('description') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                  placeholder="Description détaillée du produit">{{ old('description') }}</textarea>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Description du produit (optionnelle)
                        </div>
                        @error('description')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div style="margin-bottom: 30px;">
                        <label for="image" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-image" style="color: #2d5a27; margin-right: 5px;"></i>
                            Image
                        </label>
                        <input type="file" 
                               id="image" 
                               name="image"
                               style="
                                   width: 100%;
                                   padding: 10px;
                                   border: 2px solid {{ $errors->has('image') ? '#dc3545' : '#e8e0d5' }};
                                   border-radius: 8px;
                                   font-size: 0.95rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: #faf8f5;
                                   cursor: pointer;
                               "
                               onfocus="this.style.borderColor='#2d5a27'"
                               onblur="this.style.borderColor='{{ $errors->has('image') ? '#dc3545' : '#e8e0d5' }}'"
                               accept="image/*">
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Format accepté : JPG, PNG, GIF (Max: 2MB)
                        </div>
                        @error('image')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
                        <a href="{{ route('admin.produits.index') }}" style="
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
                            <i class="fas fa-save"></i> Enregistrer le produit
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
        }
        .container > div > div:last-child > form > div:first-child,
        .container > div > div:last-child > form > div:nth-child(2) {
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
        input[type="number"] {
            max-width: 100% !important;
        }
    }
</style>
@endpush