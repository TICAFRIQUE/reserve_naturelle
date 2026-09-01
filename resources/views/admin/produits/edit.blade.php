@extends('layouts.admin')

@section('title', 'Modifier le produit - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-edit" style="color: #2d5a27; margin-right: 10px;"></i>
                        Modifier le produit
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-box" style="color: #2d5a27; margin-right: 5px;"></i>
                        Modification du produit : <strong style="color: #2d5a27;">{{ $product->designation }}</strong>
                    </p>
                </div>
                <a href="{{ route('admin.produits.index') }}" style="
                    background: #e8e0d5; color: #2d5a27; padding: 12px 24px; border-radius: 30px;
                    text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-exclamation-circle" style="font-size: 20px; margin-top: 2px;"></i>
                        <div>
                            <strong>Des erreurs sont survenues :</strong>
                            <ul style="margin: 5px 0 0 20px; padding: 0;">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bandeau info produit -->
            <div style="background: #f8f5f0; padding: 15px 20px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #2d5a27; display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 45px; height: 45px; border-radius: 50%; background: #2d5a27; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #2d5a27; font-size: 1.05rem;">{{ $product->designation }}</div>
                        <div style="color: #6c757d; font-size: 0.85rem;"><i class="fas fa-tag" style="color: #2d5a27;"></i> Réf: {{ $product->reference_prod }}</div>
                    </div>
                </div>
                <div style="margin-left: auto; display: flex; gap: 20px;">
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #2d5a27; font-size: 0.85rem;">Prix</div>
                        <div style="color: #2d5a27; font-weight: 700; margin-top: 2px;">{{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #2d5a27; font-size: 0.85rem;">Stock</div>
                        <div style="margin-top: 2px;">
                            <span style="
                                background: {{ $product->qte_dispo > 10 ? '#d4edda' : ($product->qte_dispo > 0 ? '#fff3cd' : '#f8d7da') }};
                                color: {{ $product->qte_dispo > 10 ? '#155724' : ($product->qte_dispo > 0 ? '#856404' : '#721c24') }};
                                padding: 4px 14px; border-radius: 20px; font-size: 0.8rem;
                            ">{{ $product->qte_dispo }} unités</span>
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #2d5a27; font-size: 0.85rem;">Catégorie</div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-top: 2px;">{{ $product->category->nom ?? 'Non catégorisé' }}</div>
                    </div>
                </div>
            </div>

            <div class="form-card" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 40px;">
                <form action="{{ route('admin.produits.update', $product) }}" method="POST" enctype="multipart/form-data" id="product-form">
                    @csrf
                    @method('PUT')

                    <!-- Informations générales -->
                    <div class="form-section">
                        <h3 class="form-section-title"><i class="fas fa-info-circle"></i> Informations générales</h3>

                        <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div>
                                <label for="reference_prod" class="field-label">
                                    <i class="fas fa-hashtag" style="color: #2d5a27; margin-right: 5px;"></i>
                                    Référence <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="text" id="reference_prod" name="reference_prod" value="{{ old('reference_prod', $product->reference_prod) }}"
                                    class="field-input" style="border: 2px solid {{ $errors->has('reference_prod') ? '#dc3545' : '#e8e0d5' }};"
                                    onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                    onblur="this.style.borderColor='{{ $errors->has('reference_prod') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                    required>
                                @error('reference_prod')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label for="designation" class="field-label">
                                    <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i>
                                    Désignation <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="text" id="designation" name="designation" value="{{ old('designation', $product->designation) }}"
                                    class="field-input" style="border: 2px solid {{ $errors->has('designation') ? '#dc3545' : '#e8e0d5' }};"
                                    onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                    onblur="this.style.borderColor='{{ $errors->has('designation') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                    required>
                                @error('designation')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Classification -->
                    <div class="form-section">
                        <h3 class="form-section-title"><i class="fas fa-sitemap"></i> Classification</h3>

                        <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div>
                                <label for="category_id" class="field-label">
                                    <i class="fas fa-folder" style="color: #2d5a27; margin-right: 5px;"></i>
                                    Catégorie <span style="color: #dc3545;">*</span>
                                </label>
                                <select id="category_id" name="category_id" class="field-input"
                                        style="border: 2px solid {{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }}; cursor: pointer;"
                                        onfocus="this.style.borderColor='#2d5a27'"
                                        onblur="this.style.borderColor='{{ $errors->has('category_id') ? '#dc3545' : '#e8e0d5' }}'">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label for="sous_category_id" class="field-label">
                                    <i class="fas fa-sitemap" style="color: #2d5a27; margin-right: 5px;"></i>
                                    Sous-catégorie
                                </label>
                                <select id="sous_category_id" name="sous_category_id" class="field-input"
                                        style="border: 2px solid {{ $errors->has('sous_category_id') ? '#dc3545' : '#e8e0d5' }}; cursor: pointer;"
                                        onfocus="this.style.borderColor='#2d5a27'"
                                        onblur="this.style.borderColor='{{ $errors->has('sous_category_id') ? '#dc3545' : '#e8e0d5' }}'"
                                        disabled>
                                    <option value="">Chargement...</option>
                                </select>
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
                                    <i class="fas fa-money-bill-wave" style="color: #2d5a27; margin-right: 5px;"></i>
                                    Prix de vente <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="number" id="prix_vente" name="prix_vente" value="{{ old('prix_vente', $product->prix_vente) }}"
                                    step="100" min="0" class="field-input"
                                    style="border: 2px solid {{ $errors->has('prix_vente') ? '#dc3545' : '#e8e0d5' }};"
                                    onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                    onblur="this.style.borderColor='{{ $errors->has('prix_vente') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                    required>
                                @error('prix_vente')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label for="qte_dispo" class="field-label">
                                    <i class="fas fa-cubes" style="color: #2d5a27; margin-right: 5px;"></i>
                                    Quantité disponible <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="number" id="qte_dispo" name="qte_dispo" value="{{ old('qte_dispo', $product->qte_dispo) }}"
                                    min="0" class="field-input"
                                    style="border: 2px solid {{ $errors->has('qte_dispo') ? '#dc3545' : '#e8e0d5' }};"
                                    onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                    onblur="this.style.borderColor='{{ $errors->has('qte_dispo') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'"
                                    required>
                                @error('qte_dispo')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="stock_minimum" class="field-label">
                                <i class="fas fa-triangle-exclamation" style="color: #2d5a27; margin-right: 5px;"></i>
                                Seuil de stock minimum
                            </label>
                            <input type="number" id="stock_minimum" name="stock_minimum" value="{{ old('stock_minimum', $product->stock_minimum ?? 0) }}"
                                min="0" class="field-input" style="max-width: 300px; border: 2px solid {{ $errors->has('stock_minimum') ? '#dc3545' : '#e8e0d5' }};"
                                onfocus="this.style.borderColor='#2d5a27'; this.style.background='white'"
                                onblur="this.style.borderColor='{{ $errors->has('stock_minimum') ? '#dc3545' : '#e8e0d5' }}'; this.style.background='#faf8f5'">
                            <div class="field-hint"><i class="fas fa-info-circle"></i> Une alerte apparaît sur le dashboard sous ce seuil</div>
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
                                placeholder="Description détaillée du produit">{{ old('description', $product->description) }}</textarea>
                            @error('description')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label for="image" class="field-label">
                                <i class="fas fa-image" style="color: #2d5a27; margin-right: 5px;"></i>
                                Image
                            </label>

                            <div id="image-preview" style="margin-bottom: 10px; {{ $product->image_path ? '' : 'display:none;' }}">
                                <img id="image-preview-img"
                                     src="{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}"
                                     style="height: 80px; width: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #e8e0d5;">
                                <div class="field-hint" id="image-preview-label">Image actuelle</div>
                            </div>

                            <input type="file" id="image" name="image" accept="image/*"
                                style="width: 100%; padding: 10px; border: 2px solid {{ $errors->has('image') ? '#dc3545' : '#e8e0d5' }}; border-radius: 8px; background: #faf8f5; cursor: pointer;"
                                onfocus="this.style.borderColor='#2d5a27'"
                                onblur="this.style.borderColor='{{ $errors->has('image') ? '#dc3545' : '#e8e0d5' }}'">
                            <div class="field-hint"><i class="fas fa-info-circle"></i> Format accepté : JPG, PNG, GIF (Max: 2MB)</div>
                            @error('image')<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
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
                            <i class="fas fa-save"></i> Mettre à jour
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
    const currentSousCategoryId = "{{ old('sous_category_id', $product->sous_category_id) }}";

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

    if (categorySelect.value) loadSousCategories(categorySelect.value, currentSousCategoryId);

    // Preview image (remplace l'image actuelle si nouveau fichier choisi)
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const img = document.getElementById('image-preview-img');
        const label = document.getElementById('image-preview-label');
        const file = e.target.files[0];
        if (!file) return;
        img.src = URL.createObjectURL(file);
        label.textContent = 'Nouvelle image (aperçu)';
        preview.style.display = 'block';
    });

    // Anti double-submit
    document.getElementById('product-form').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour...';
    });
});
</script>
@endpush