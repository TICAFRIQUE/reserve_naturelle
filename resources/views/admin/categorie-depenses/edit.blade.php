{{-- resources/views/admin/categorie_depenses/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Modifier la catégorie - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div style="margin-bottom: 30px;">
                <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                    <i class="fas fa-edit" style="color: #b8860b; margin-right: 10px;"></i>
                    Modifier la catégorie
                </h1>
                <p style="color: #6c757d; margin: 5px 0 0 0;">
                    <i class="fas fa-tag" style="color: #b8860b;"></i>
                    {{ $categorieDepense->nom }}
                </p>
            </div>

            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 35px;">
                <form action="{{ route('admin.categorie-depenses.update', $categorieDepense->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 25px;">
                        <label for="nom" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 6px;">
                            <i class="fas fa-tag" style="color: #b8860b; margin-right: 6px;"></i>
                            Nom de la catégorie <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $categorieDepense->nom) }}" required style="
                            width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; outline: none;
                        ">
                        @error('nom')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 2px solid #f0ebe5; padding-top: 20px;">
                        <a href="{{ route('admin.categorie-depenses.index') }}" style="
                            padding: 12px 28px; background: #f0ebe5; color: #2d5a27; border-radius: 30px;
                            text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" style="
                            padding: 12px 32px; background: #2d5a27; color: white; border: none; border-radius: 30px;
                            font-size: 1rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection