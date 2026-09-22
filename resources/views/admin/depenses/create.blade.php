{{-- resources/views/admin/depenses/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Nouvelle dépense - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="margin-bottom: 30px;">
                <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                    <i class="fas fa-plus-circle" style="color: #b8860b; margin-right: 10px;"></i>
                    Nouvelle dépense
                </h1>
                <p style="color: #6c757d; margin: 5px 0 0 0;">Enregistrez une nouvelle dépense</p>
            </div>

            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 35px;">
                <form action="{{ route('admin.depenses.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 20px;">
                        <label for="categorie_depense_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-tag" style="color: #b8860b; margin-right: 6px;"></i>
                            Catégorie <span style="color: #dc3545;">*</span>
                        </label>
                        <select name="categorie_depense_id" id="categorie_depense_id" required style="
                            width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
                            font-size: 1rem; background: white; cursor: pointer; outline: none;
                        ">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ old('categorie_depense_id') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_depense_id')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="libelle" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-heading" style="color: #b8860b; margin-right: 6px;"></i>
                            Libellé <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" name="libelle" id="libelle" value="{{ old('libelle') }}" required style="
                            width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; outline: none;
                        " placeholder="Ex: Facture électricité mars">
                        @error('libelle')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="description" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-align-left" style="color: #b8860b; margin-right: 6px;"></i>
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3" style="
                            width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
                            font-size: 1rem; outline: none; resize: vertical; font-family: 'Lato', sans-serif;
                        " placeholder="Détails additionnels (optionnel)">{{ old('description') }}</textarea>
                        @error('description')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="montant" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                                <i class="fas fa-coins" style="color: #b8860b; margin-right: 6px;"></i>
                                Montant (FCFA) <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="number" name="montant" id="montant" value="{{ old('montant') }}" required min="1" style="
                                width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; outline: none;
                            " placeholder="0">
                            @error('montant')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label for="date_depense" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                                <i class="fas fa-calendar" style="color: #b8860b; margin-right: 6px;"></i>
                                Date <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="date" name="date_depense" id="date_depense" value="{{ old('date_depense', now()->format('Y-m-d')) }}" required style="
                                width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; outline: none;
                            ">
                            @error('date_depense')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 25px;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="mode_paiement" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                                <i class="fas fa-credit-card" style="color: #b8860b; margin-right: 6px;"></i>
                                Mode de paiement <span style="color: #dc3545;">*</span>
                            </label>
                            <select name="mode_paiement" id="mode_paiement" required style="
                                width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
                                font-size: 1rem; background: white; cursor: pointer; outline: none;
                            ">
                                @foreach(['especes' => 'Espèces', 'cheque' => 'Chèque', 'virement' => 'Virement', 'mobile_money' => 'Mobile Money'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('mode_paiement') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('mode_paiement')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label for="reference" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                                <i class="fas fa-hashtag" style="color: #b8860b; margin-right: 6px;"></i>
                                Référence
                            </label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}" style="
                                width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; outline: none;
                            " placeholder="N° chèque, transaction...">
                            @error('reference')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 2px solid #f0ebe5; padding-top: 20px;">
                        <a href="{{ route('admin.depenses.index') }}" style="
                            padding: 12px 28px; background: #f0ebe5; color: #2d5a27; border-radius: 30px;
                            text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" style="
                            padding: 12px 32px; background: #2d5a27; color: white; border: none; border-radius: 30px;
                            font-size: 1rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-save"></i> Enregistrer la dépense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection