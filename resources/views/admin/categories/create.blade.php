{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.auth')

@section('title', 'Nouvelle catégorie - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Entête -->
            <div style="margin-bottom: 30px;">
                <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                    <i class="fas fa-plus-circle" style="color: #b8860b; margin-right: 10px;"></i>
                    Nouvelle catégorie
                </h1>
                <p style="color: #6c757d; margin: 5px 0 0 0;">Ajoutez une nouvelle catégorie de produits</p>
            </div>

            <!-- Carte formulaire -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                padding: 35px;
            ">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <!-- Champ Nom -->
                    <div style="margin-bottom: 20px;">
                        <label for="nom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-tag" style="color: #b8860b; margin-right: 6px;"></i>
                            Nom de la catégorie <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                            style="
                                width: 100%;
                                padding: 12px 16px;
                                border: 2px solid #e8e0d5;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: all 0.3s;
                                outline: none;
                            "
                            onfocus="this.style.borderColor='#2d5a27'; this.style.boxShadow='0 0 0 3px rgba(45,90,39,0.1)'"
                            onblur="this.style.borderColor='#e8e0d5'; this.style.boxShadow='none'"
                            placeholder="Ex: Céréales, Huiles, Fruits secs..."
                        >
                        @error('nom')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Champ Description -->
                    <div style="margin-bottom: 20px;">
                        <label for="description" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-align-left" style="color: #b8860b; margin-right: 6px;"></i>
                            Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                            style="
                                width: 100%;
                                padding: 12px 16px;
                                border: 2px solid #e8e0d5;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: all 0.3s;
                                outline: none;
                                resize: vertical;
                                font-family: 'Lato', sans-serif;
                            "
                            onfocus="this.style.borderColor='#2d5a27'; this.style.boxShadow='0 0 0 3px rgba(45,90,39,0.1)'"
                            onblur="this.style.borderColor='#e8e0d5'; this.style.boxShadow='none'"
                            placeholder="Description de la catégorie (optionnelle)">{{ old('description') }}</textarea>
                        @error('description')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Champ Statut -->
                    <div style="margin-bottom: 20px;">
                        <label for="statut" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-toggle-on" style="color: #b8860b; margin-right: 6px;"></i>
                            Statut
                        </label>
                        <select name="statut" id="statut"
                            style="
                                width: 100%;
                                padding: 12px 16px;
                                border: 2px solid #e8e0d5;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: all 0.3s;
                                outline: none;
                                background: white;
                                cursor: pointer;
                            "
                            onfocus="this.style.borderColor='#2d5a27'; this.style.boxShadow='0 0 0 3px rgba(45,90,39,0.1)'"
                            onblur="this.style.borderColor='#e8e0d5'; this.style.boxShadow='none'"
                        >
                            <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('statut')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Champ Ordre -->
                    <div style="margin-bottom: 25px;">
                        <label for="ordre" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-sort" style="color: #b8860b; margin-right: 6px;"></i>
                            Ordre d'affichage
                        </label>
                        <input type="number" name="ordre" id="ordre" value="{{ old('ordre', 0) }}"
                            min="0"
                            style="
                                width: 100%;
                                padding: 12px 16px;
                                border: 2px solid #e8e0d5;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: all 0.3s;
                                outline: none;
                            "
                            onfocus="this.style.borderColor='#2d5a27'; this.style.boxShadow='0 0 0 3px rgba(45,90,39,0.1)'"
                            onblur="this.style.borderColor='#e8e0d5'; this.style.boxShadow='none'"
                            placeholder="0"
                        >
                        <small style="color: #6c757d; font-size: 0.8rem;">
                            <i class="fas fa-info-circle"></i> Les catégories avec un ordre plus petit s'affichent en premier
                        </small>
                        @error('ordre')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 2px solid #f0ebe5; padding-top: 20px;">
                        <a href="{{ route('admin.categories.index') }}" style="
                            padding: 12px 28px;
                            background: #f0ebe5;
                            color: #2d5a27;
                            border-radius: 30px;
                            text-decoration: none;
                            font-weight: 500;
                            transition: all 0.3s;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" style="
                            padding: 12px 32px;
                            background: #2d5a27;
                            color: white;
                            border: none;
                            border-radius: 30px;
                            font-size: 1rem;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.3s;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-save"></i> Créer la catégorie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection