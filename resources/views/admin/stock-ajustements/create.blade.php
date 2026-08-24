@extends('layouts.admin')

@section('title', 'Ajustement de Stock - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-sliders-h" style="color: #b8860b; margin-right: 10px;"></i>
                        Ajustement manuel de stock
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Correction de quantité, perte ou casse</p>
                </div>
                <a href="{{ route('admin.stock-mouvements.index') }}" style="
                    background: #e8e0d5;
                    color: #2d5a27;
                    padding: 10px 24px;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                ">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            @if ($errors->any())
                <div style="background: #fce4ec; padding: 16px 20px; border-radius: 8px; border-left: 4px solid #c62828; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px; color: #c62828; font-size: 14px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 30px; border: 1px solid #e8e0d5;">
                <form method="POST" action="{{ route('admin.stock-ajustements.store') }}">
                    @csrf

                    <div style="margin-bottom: 25px;">
                        <label for="product_id" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.95rem; margin-bottom: 6px;">
                            <i class="fas fa-box" style="color: #b8860b; margin-right: 8px;"></i>
                            Produit <span style="color: #dc3545;">*</span>
                        </label>
                        <select id="product_id" name="product_id" required style="
                            width: 100%; max-width: 400px; padding: 12px 16px;
                            border: 2px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem; background: white;
                        ">
                            <option value="">-- Sélectionner un produit --</option>
                            @foreach ($produits as $produit)
                                <option value="{{ $produit->id }}" data-stock="{{ $produit->qte_dispo }}" {{ old('product_id') == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->designation }} ({{ $produit->reference_prod }}) — Stock: {{ $produit->qte_dispo }}
                                </option>
                            @endforeach
                        </select>
                        <div id="stock-info" style="margin-top: 6px; font-size: 0.85rem; color: #6c757d;"></div>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.95rem; margin-bottom: 10px;">
                            <i class="fas fa-tag" style="color: #b8860b; margin-right: 8px;"></i>
                            Type d'ajustement <span style="color: #dc3545;">*</span>
                        </label>
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; border: 2px solid #e8e0d5; border-radius: 8px; cursor: pointer;">
                                <input type="radio" name="type" value="ajustement" onchange="toggleSens()" {{ old('type', 'ajustement') === 'ajustement' ? 'checked' : '' }}>
                                <span style="font-weight: 500; color: #2d5a27;">Ajustement (correction)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; border: 2px solid #e8e0d5; border-radius: 8px; cursor: pointer;">
                                <input type="radio" name="type" value="perte_casse" onchange="toggleSens()" {{ old('type') === 'perte_casse' ? 'checked' : '' }}>
                                <span style="font-weight: 500; color: #2d5a27;">Perte / Casse</span>
                            </label>
                        </div>
                    </div>

                    <div id="sens-selector" style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.95rem; margin-bottom: 10px;">
                            <i class="fas fa-arrows-alt-h" style="color: #b8860b; margin-right: 8px;"></i>
                            Sens <span style="color: #dc3545;">*</span>
                        </label>
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; border: 2px solid #e8e0d5; border-radius: 8px; cursor: pointer;">
                                <input type="radio" name="sens" value="entree" {{ old('sens') === 'entree' ? 'checked' : '' }}>
                                <span style="font-weight: 500; color: #28a745;">+ Entrée</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; border: 2px solid #e8e0d5; border-radius: 8px; cursor: pointer;">
                                <input type="radio" name="sens" value="sortie" {{ old('sens', 'sortie') === 'sortie' ? 'checked' : '' }}>
                                <span style="font-weight: 500; color: #dc3545;">- Sortie</span>
                            </label>
                        </div>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label for="quantite" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.95rem; margin-bottom: 6px;">
                            <i class="fas fa-cube" style="color: #b8860b; margin-right: 8px;"></i>
                            Quantité <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="number" id="quantite" name="quantite" min="1" value="{{ old('quantite') }}" required style="
                            width: 100%; max-width: 200px; padding: 12px 16px;
                            border: 2px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem;
                        ">
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label for="notes" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.95rem; margin-bottom: 6px;">
                            <i class="fas fa-sticky-note" style="color: #b8860b; margin-right: 8px;"></i>
                            Motif <span style="color: #dc3545;">*</span>
                        </label>
                        <textarea id="notes" name="notes" rows="3" required placeholder="Raison de cet ajustement (obligatoire)..." style="
                            width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
                            font-size: 0.95rem; font-family: inherit; resize: vertical;
                        ">{{ old('notes') }}</textarea>
                    </div>

                    <div style="display: flex; gap: 15px; flex-wrap: wrap; padding-top: 10px; border-top: 1px solid #e8e0d5;">
                        <button type="submit" style="
                            background: linear-gradient(135deg, #2d5a27 0%, #4caf50 100%); color: white;
                            padding: 14px 35px; border: none; border-radius: 30px; font-size: 1rem; font-weight: 600;
                            cursor: pointer; display: inline-flex; align-items: center; gap: 10px;
                        ">
                            <i class="fas fa-save"></i> Enregistrer l'ajustement
                        </button>
                        <a href="{{ route('admin.stock-mouvements.index') }}" style="
                            background: #e8e0d5; color: #2d5a27; padding: 14px 30px; border-radius: 30px;
                            text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        ">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSens() {
    const type = document.querySelector('input[name="type"]:checked')?.value;
    const sensSelector = document.getElementById('sens-selector');
    if (type === 'perte_casse') {
        sensSelector.style.display = 'none';
        document.querySelector('input[name="sens"][value="sortie"]').checked = true;
    } else {
        sensSelector.style.display = 'block';
    }
}

document.getElementById('product_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const stock = selected.dataset.stock;
    const info = document.getElementById('stock-info');
    info.textContent = stock !== undefined ? `Stock actuel : ${stock}` : '';
});

document.addEventListener('DOMContentLoaded', toggleSens);
</script>
@endsection