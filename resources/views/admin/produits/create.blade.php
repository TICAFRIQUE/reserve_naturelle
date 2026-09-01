@extends('layouts.admin')

@section('title', 'Ajouter un produit - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-plus-circle" style="color: #b8860b; margin-right: 10px;"></i>
                        Ajouter un produit
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-box" style="color: #b8860b; margin-right: 5px;"></i>
                        Ajoutez un nouveau produit à votre catalogue
                    </p>
                </div>
                <a href="{{ route('admin.produits.index') }}" style="
                    background: #e8e0d5; color: #2d5a27; padding: 12px 24px; border-radius: 30px;
                    text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            @if($errors->any())
                <div style="background: #fce4ec; color: #c62828; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #c62828; margin-bottom: 20px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-exclamation-circle" style="font-size: 20px; margin-top: 2px;"></i>
                        <div>
                            <strong style="display: block; margin-bottom: 5px;">Des erreurs sont survenues :</strong>
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li style="margin-bottom: 3px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="form-card" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 40px;">
                <form action="{{ route('admin.produits.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
                    @csrf

                    <!-- Informations générales -->
                    <div class="form-section">
                        <h3 class="form-section-title"><i class="fas fa-info-circle"></i> Informations générales</h3>

                        <div>
                            <label for="designation" class="field-label">
                                <i class="fas fa-tag" style="color: #b8860b; margin-right: 5px;"></i>
                                Désignation <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="text" id="designation" name="designation" value="{{ old('designation') }}"
                                class="field-input"
                                style="border: 2px solid {{ $errors->has('designation') ? '#dc3545' : '#e8e0d5' }};"
                                onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                onblur="this.style.borderColor='{{ $errors->has('designation') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                placeholder="Ex: T-shirt Coton Bio" required>
                            @error('designation')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Classification -->
                    <div class="form-section">
                        <h3 class="form-section-title"><i class="fas fa-sitemap"></i> Classification</h3>

                        <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div>
                                <label for="category_id" class="field-label">
                                    <i class="fas fa-folder" style="color: #b8860b; margin-right: 5px;"></i>
                                    Catégorie <span style="color: #dc3545;">*</span>
                                </label>
                                <select id="category_id" name="category_id" class="field-input"
                                        style="border: 2px solid {{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }}; cursor: pointer;"
                                        onfocus="this.style.borderColor='#2d5a27'"
                                        onblur="this.style.borderColor='{{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }}'">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="field-hint"><i class="fas fa-info-circle"></i> Sélectionnez la catégorie parente</div>
                                @error('category_id')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label for="sous_category_id" class="field-label">
                                    <i class="fas fa-sitemap" style="color: #b8860b; margin-right: 5px;"></i>
                                    Sous-catégorie
                                </label>
                                <select id="sous_category_id" name="sous_category_id" class="field-input"
                                        style="border: 2px solid {{ $errors->has('sous_category_id') ? '#dc3545' : '#e8e0d5' }}; cursor: pointer;"
                                        onfocus="this.style.borderColor='#2d5a27'"
                                        onblur="this.style.borderColor='{{ $errors->has('sous_category_id') ? '#dc3545' : '#e8e0d5' }}'"
                                        disabled>
                                    <option value="">Choisir d'abord une catégorie</option>
                                </select>
                                <div class="field-hint"><i class="fas fa-info-circle"></i> Optionnel</div>
                                @error('sous_category_id')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Prix & Stock -->
                    <div class="form-section">
                        <h3 class="form-section-title"><i class="fas fa-warehouse"></i> Prix & Stock</h3>

                        <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                            <div>
                                <label for="prix_vente" class="field-label">
                                    <i class="fas fa-money-bill-wave" style="color: #b8860b; margin-right: 5px;"></i>
                                    Prix de vente <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="number" id="prix_vente" name="prix_vente" value="{{ old('prix_vente') }}"
                                    step="100" min="0" class="field-input"
                                    style="border: 2px solid {{ $errors->has('prix_vente') ? '#dc3545' : '#e8e0d5' }};"
                                    onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                    onblur="this.style.borderColor='{{ $errors->has('prix_vente') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                    placeholder="Ex: 5000" required>
                                <div class="field-hint"><i class="fas fa-info-circle"></i> Prix de vente en FCFA</div>
                                @error('prix_vente')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label for="qte_dispo" class="field-label">
                                    <i class="fas fa-cubes" style="color: #b8860b; margin-right: 5px;"></i>
                                    Quantité disponible <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="number" id="qte_dispo" name="qte_dispo" value="{{ old('qte_dispo', 0) }}"
                                    min="0" class="field-input"
                                    style="border: 2px solid {{ $errors->has('qte_dispo') ? '#dc3545' : '#e8e0d5' }};"
                                    onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                    onblur="this.style.borderColor='{{ $errors->has('qte_dispo') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                    required>
                                <div class="field-hint"><i class="fas fa-info-circle"></i> Quantité initiale en stock</div>
                                @error('qte_dispo')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="stock_minimum" class="field-label">
                                <i class="fas fa-triangle-exclamation" style="color: #b8860b; margin-right: 5px;"></i>
                                Seuil de stock minimum
                            </label>
                            <input type="number" id="stock_minimum" name="stock_minimum" value="{{ old('stock_minimum', 10) }}"
                                min="0" class="field-input" style="max-width: 300px; border: 2px solid {{ $errors->has('stock_minimum') ? '#dc3545' : '#e8e0d5' }};"
                                onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                onblur="this.style.borderColor='{{ $errors->has('stock_minimum') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'">
                            <div class="field-hint"><i class="fas fa-info-circle"></i> Alerte stock faible (défaut: 10)</div>
                            @error('stock_minimum')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Détails -->
                    <div class="form-section">
                        <h3 class="form-section-title"><i class="fas fa-align-left"></i> Détails</h3>

                        <div style="margin-bottom: 25px;">
                            <label for="description" class="field-label">Description</label>
                            <textarea id="description" name="description" rows="4" class="field-input"
                                style="border: 2px solid {{ $errors->has('description') ? '#dc3545' : '#e8e0d5' }}; resize: vertical; font-family: inherit;"
                                onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                onblur="this.style.borderColor='{{ $errors->has('description') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                placeholder="Description détaillée du produit (optionnelle)">{{ old('description') }}</textarea>
                            @error('description')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label for="image" class="field-label">
                                <i class="fas fa-image" style="color: #b8860b; margin-right: 5px;"></i>
                                Image
                            </label>
                            <input type="file" id="image" name="image" accept="image/*"
                                style="width: 100%; padding: 10px; border: 2px solid {{ $errors->has('image') ? '#dc3545' : '#e8e0d5' }}; border-radius: 8px; background: #faf8f5; cursor: pointer;"
                                onfocus="this.style.borderColor='#2d5a27'"
                                onblur="this.style.borderColor='{{ $errors->has('image') ? '#dc3545' : '#e8e0d5' }}'">
                            <div id="image-preview" style="margin-top: 12px; display: none;">
                                <img id="image-preview-img" style="height: 90px; width: 90px; object-fit: cover; border-radius: 8px; border: 1px solid #e8e0d5;">
                            </div>
                            <div class="field-hint"><i class="fas fa-info-circle"></i> Format accepté : JPG, PNG, GIF (Max: 2MB)</div>
                            @error('image')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5; flex-wrap: wrap;">
                        <a href="{{ route('admin.produits.index') }}" style="
                            background: #e8e0d5; color: #2d5a27; padding: 12px 30px; border-radius: 30px;
                            text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" style="
                            background: #2d5a27; color: white; padding: 12px 35px; border-radius: 30px; border: none;
                            font-weight: 500; cursor: pointer; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-save"></i> Enregistrer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('admin.produits._form-styles')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const sousCategorySelect = document.getElementById('sous_category_id');

    function loadSousCategories(categoryId, selectedId = '') {
        if (!categoryId) {
            sousCategorySelect.disabled = true;
            sousCategorySelect.innerHTML = '<option value="">Choisir d\'abord une catégorie</option>';
            return;
        }
        sousCategorySelect.disabled = true;
        sousCategorySelect.innerHTML = '<option value="">Chargement...</option>';

        fetch(`/admin/categories/${categoryId}/sous-categories`)
            .then(r => r.json())
            .then(data => {
                sousCategorySelect.disabled = false;
                let options = '<option value="">Aucune sous-catégorie</option>';
                data.forEach(sc => {
                    const sel = String(sc.id) === String(selectedId) ? 'selected' : '';
                    options += `<option value="${sc.id}" ${sel}>${sc.nom}</option>`;
                });
                sousCategorySelect.innerHTML = options;
            })
            .catch(() => {
                sousCategorySelect.disabled = false;
                sousCategorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    categorySelect.addEventListener('change', () => loadSousCategories(categorySelect.value));

    @if(old('category_id'))
        loadSousCategories("{{ old('category_id') }}", "{{ old('sous_category_id') }}");
    @endif

    // Preview image
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const img = document.getElementById('image-preview-img');
        const file = e.target.files[0];
        if (!file) { preview.style.display = 'none'; return; }
        img.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    });

    // Anti double-submit
    document.getElementById('product-form').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
    });
});
</script>
@endpush