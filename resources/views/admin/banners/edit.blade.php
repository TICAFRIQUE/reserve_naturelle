@extends('layouts.admin')

@section('title', 'Modifier la bannière')

@section('content')

<div class="admin-main">

    <!-- HERO -->
    <div class="admin-hero">
        <div class="hero-left">
            <div class="hero-text">
                <h1>Modifier la bannière d'accueil</h1>
                <p>Changez le contenu affiché sur la page d'accueil</p>
            </div>
        </div>
        <div class="hero-right">
            <a href="{{ route('home') }}" target="_blank" class="btn-back">
                <i class="fas fa-eye"></i> Voir le rendu
            </a>
        </div>
    </div>

    <!-- MESSAGE SUCCESS -->
    @if(session('success'))
        <div class="alert-success" style="background:#d4edda;color:#155724;padding:15px;border-radius:8px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORMULAIRE -->
    <form action="{{ route('admin.banner.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid-2cols">

            <!-- COLONNE GAUCHE : CONTENU -->
            <div class="finance-card">
                <h4><i class="fas fa-edit"></i> Contenu de la bannière</h4>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Titre principal</label>
                    <input type="text" name="titre" value="{{ old('titre', $banner->titre) }}" required
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                    @error('titre')
                        <span style="color:var(--red);font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Partie colorée du titre</label>
                    <input type="text" name="titre_span" value="{{ old('titre_span', $banner->titre_span) }}"
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                    <small style="color:var(--gray-500);font-size:12px;">Ex: "terre ivoirienne" (affiché en or)</small>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Sous-titre</label>
                    <textarea name="sous_titre" rows="3"
                              style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;resize:vertical;">{{ old('sous_titre', $banner->sous_titre) }}</textarea>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Texte du bouton</label>
                    <input type="text" name="texte_bouton" value="{{ old('texte_bouton', $banner->texte_bouton) }}"
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Lien du bouton</label>
                    <input type="text" name="lien_bouton" value="{{ old('lien_bouton', $banner->lien_bouton) }}"
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                    <small style="color:var(--gray-500);font-size:12px;">Ex: #catalogue, /produits, https://...</small>
                </div>
            </div>

            <!-- COLONNE DROITE : IMAGE + STATUT -->
            <div class="finance-card">
                <h4><i class="fas fa-image"></i> Image de fond</h4>

                @if($banner->image_path)
                    <div style="margin-bottom:16px;">
                        <img src="{{ asset('storage/' . $banner->image_path) }}"
                             style="max-width:100%;border-radius:8px;display:block;">
                    </div>
                @else
                    <div style="padding:40px;background:var(--gray-50);border-radius:8px;text-align:center;color:var(--gray-400);margin-bottom:16px;">
                        <i class="fas fa-image" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                        Aucune image
                    </div>
                @endif

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Changer l'image</label>
                    <input type="file" name="image" accept="image/*"
                           style="width:100%;padding:10px;border:1px solid var(--gray-200);border-radius:8px;">
                    <small style="color:var(--gray-500);font-size:12px;">Format recommandé : 1920×600, max 5MB</small>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="actif" value="1" {{ $banner->actif ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--green-primary);">
                        <span style="font-weight:600;">Bannière active</span>
                    </label>
                    <small style="color:var(--gray-500);font-size:12px;">Décochez pour masquer la bannière</small>
                </div>

                <button type="submit"
                        style="width:100%;padding:14px;background:var(--green-primary);color:white;border:none;border-radius:8px;font-weight:700;font-size:15px;cursor:pointer;">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>

        </div>
    </form>
</div>

@endsection