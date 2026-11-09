{{-- resources/views/admin/depenses/_form.blade.php --}}
@php
    $familleSelected = old('categorie_depense_id', $depense->libelle->categorie_depense_id ?? '');
    $libelleSelected = old('libelle_depense_id', $depense->libelle_depense_id ?? '');
@endphp

<div style="margin-bottom: 20px;">
    <label for="categorie_depense_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
        <i class="fas fa-tag" style="color: #b8860b; margin-right: 6px;"></i>
        Famille <span style="color: #dc3545;">*</span>
    </label>
    <select name="categorie_depense_id" id="categorie_depense_id" required style="
        width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
        font-size: 1rem; background: white; cursor: pointer; outline: none;
    ">
        <option value="">Sélectionner une famille</option>
        @foreach($categories as $categorie)
            <option value="{{ $categorie->id }}" @selected($familleSelected == $categorie->id)>{{ $categorie->nom }}</option>
        @endforeach
    </select>
    @error('categorie_depense_id')
        <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
    @enderror
</div>

<div style="margin-bottom: 20px;">
    <label for="libelle_depense_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
        <i class="fas fa-heading" style="color: #b8860b; margin-right: 6px;"></i>
        Libellé <span style="color: #dc3545;">*</span>
    </label>
    <select name="libelle_depense_id" id="libelle_depense_id" required style="
        width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
        font-size: 1rem; background: white; cursor: pointer; outline: none;
    ">
        <option value="">Choisir d'abord une famille</option>
    </select>
    @error('libelle_depense_id')
        <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
    @enderror
</div>

<div style="margin-bottom: 25px;">
    <label for="montant" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
        <i class="fas fa-coins" style="color: #b8860b; margin-right: 6px;"></i>
        Montant (FCFA) <span style="color: #dc3545;">*</span>
    </label>
    <input type="number" name="montant" id="montant" value="{{ old('montant', $depense->montant ?? '') }}" required min="1" style="
        width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; outline: none;
    " placeholder="0">
    @error('montant')
        <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
    @enderror
</div>

<div style="margin-bottom: 25px;">
    <label for="date_depense" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
        <i class="fas fa-calendar" style="color: #b8860b; margin-right: 6px;"></i>
        Date <span style="color: #dc3545;">*</span>
    </label>
    <input type="date" name="date_depense" id="date_depense"
        value="{{ old('date_depense', isset($depense) ? $depense->date_depense->format('Y-m-d') : now()->format('Y-m-d')) }}"
        max="{{ now()->format('Y-m-d') }}" required style="
        width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; outline: none;
    ">
    @error('date_depense')
        <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const libellesParFamille = @json($libellesParFamille);
    const famille = document.getElementById('categorie_depense_id');
    const libelle = document.getElementById('libelle_depense_id');
    const selectedLibelle = @json((string) $libelleSelected);

    function remplir(familleId, selected = '') {
        libelle.innerHTML = '';
        const liste = libellesParFamille[familleId] || [];

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = !familleId ? "Choisir d'abord une famille"
            : (liste.length ? 'Sélectionner un libellé' : 'Aucun libellé dans cette famille');
        libelle.appendChild(placeholder);

        liste.forEach(l => {
            const opt = document.createElement('option');
            opt.value = l.id;
            opt.textContent = l.nom;
            if (String(l.id) === String(selected)) opt.selected = true;
            libelle.appendChild(opt);
        });
    }

    famille.addEventListener('change', () => remplir(famille.value));
    remplir(famille.value, selectedLibelle);
});
</script>
@endpush