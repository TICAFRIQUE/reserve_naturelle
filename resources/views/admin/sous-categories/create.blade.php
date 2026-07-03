@extends('layouts.admin')

@section('title', 'Créer une sous-catégorie - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton retour -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-plus-circle" style="color: #2d5a27; margin-right: 10px;"></i>
                        Créer une sous-catégorie
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Ajoutez une nouvelle sous-catégorie à votre catalogue</p>
                </div>
                <a href="{{ route('admin.sous-categories.index') }}" style="
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
                <form action="{{ route('admin.sous-categories.store') }}" method="POST">
                    @csrf

                    <!-- Nom -->
                    <div style="margin-bottom: 25px;">
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
                               placeholder="Ex: T-shirts, Robes, Accessoires..."
                               required>
                        @error('nom')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Catégorie parente -->
                    <div style="margin-bottom: 25px;">
                        <label for="category_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            Catégorie parente <span style="color: #dc3545;">*</span>
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
                                onblur="this.style.borderColor='{{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }}'"
                                required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nom }}
                                </option>
                            @endforeach
                        </select>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> La sous-catégorie sera rattachée à cette catégorie
                        </div>
                        @error('category_id')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Statut et Ordre -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                        <!-- Statut -->
                        <div>
                            <label for="statut" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                Statut <span style="color: #dc3545;">*</span>
                            </label>
                            <select id="statut" 
                                    name="statut" 
                                    style="
                                        width: 100%;
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
                                    onblur="this.style.borderColor='{{ $errors->has('statut') ? '#dc3545' : '#e8e0d5' }}'"
                                    required>
                                <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Détermine si la sous-catégorie est visible
                            </div>
                            @error('statut')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Ordre -->
                        <div>
                            <label for="ordre" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                Ordre <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="number" 
                                   id="ordre" 
                                   name="ordre" 
                                   value="{{ old('ordre', 0) }}"
                                   min="0"
                                   style="
                                       width: 100%;
                                       padding: 12px 16px;
                                       border: 2px solid {{ $errors->has('ordre') ? '#dc3545' : '#e8e0d5' }};
                                       border-radius: 8px;
                                       font-size: 1rem;
                                       transition: border-color 0.3s;
                                       outline: none;
                                       background: #faf8f5;
                                   "
                                   onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                   onblur="this.style.borderColor='{{ $errors->has('ordre') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                   required>
                            <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Plus le chiffre est petit, plus la sous-catégorie apparaît en premier
                            </div>
                            @error('ordre')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div style="margin-bottom: 30px;">
                        <label for="description" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
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
                                  placeholder="Description de la sous-catégorie (optionnelle)">{{ old('description') }}</textarea>
                        <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Une brève description de la sous-catégorie
                        </div>
                        @error('description')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
                        <a href="{{ route('admin.sous-categories.index') }}" style="
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
                            <i class="fas fa-save"></i> Créer la sous-catégorie
                        </a>
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