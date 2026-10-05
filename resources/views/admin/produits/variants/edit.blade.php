@extends('layouts.admin')

@section('title', 'Modifier ' . $variant->reference_prod)

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- En-tête --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px;">
                <div>
                    <h1 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:2rem;margin:0;">
                        <i class="fas fa-edit" style="color:#b8860b;margin-right:10px;"></i>
                        Modifier la variante
                    </h1>
                    <p style="color:#6c757d;margin:5px 0 0 0;">
                        Produit : <strong>{{ $product->designation }}</strong> ({{ $product->reference_prod }})
                    </p>
                </div>
                <a href="{{ route('admin.produits.variants.index', $product) }}" style="background:#e8e0d5;color:#2d5a27;padding:12px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                    onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            {{-- Erreurs --}}
            @if($errors->any())
                <div style="background:#f8d7da;color:#721c24;padding:15px 20px;border-radius:8px;border-left:4px solid #dc3545;margin-bottom:20px;">
                    <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul style="margin:10px 0 0 20px;padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info stock actuel (non modifiable) --}}
            <div style="background:#e3f2fd;color:#1565c0;padding:15px 20px;border-radius:8px;border-left:4px solid #1565c0;margin-bottom:20px;">
                <i class="fas fa-info-circle" style="margin-right:8px;"></i>
                <strong>Stock actuel :</strong> {{ $variant->qte_dispo }} unités
                &nbsp;•&nbsp;
                <strong>CMP :</strong> {{ number_format($variant->cmp, 0, ',', ' ') }} FCFA
                <br>
                <small style="opacity:0.8;">
                    Le stock et le CMP sont gérés automatiquement par les <strong>achats</strong>, <strong>commandes</strong> et <strong>inventaires</strong>.
                </small>
            </div>

            {{-- Formulaire --}}
            <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:30px;max-width:700px;">
                <form action="{{ route('admin.produits.variants.update', $variant) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Référence (lecture seule) --}}
                    <div style="margin-bottom:22px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.95rem;margin-bottom:6px;">
                            <i class="fas fa-barcode" style="color:#b8860b;margin-right:8px;"></i>
                            Référence (SKU)
                        </label>
                        <input type="text" value="{{ $variant->reference_prod }}" disabled
                            style="width:100%;padding:12px 16px;border:2px solid #e8e0d5;border-radius:8px;font-size:0.95rem;background:#f8f5f0;color:#6c757d;box-sizing:border-box;cursor:not-allowed;">
                        <div style="margin-top:6px;color:#6c757d;font-size:0.8rem;">
                            <i class="fas fa-info-circle"></i> Générée automatiquement à partir du nom et du conditionnement
                        </div>
                    </div>

                    {{-- Conditionnement --}}
                    <div style="margin-bottom:22px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.95rem;margin-bottom:6px;">
                            <i class="fas fa-box" style="color:#b8860b;margin-right:8px;"></i>
                            Conditionnement <span style="color:#dc3545;">*</span>
                        </label>
                        <input type="text" name="conditionnement" value="{{ old('conditionnement', $variant->conditionnement) }}" required
                            style="width:100%;padding:12px 16px;border:2px solid #e8e0d5;border-radius:8px;font-size:0.95rem;outline:none;background:white;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                    </div>

                    {{-- Prix + Stock minimum --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:22px;">
                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.95rem;margin-bottom:6px;">
                                <i class="fas fa-tag" style="color:#b8860b;margin-right:8px;"></i>
                                Prix de vente (FCFA) <span style="color:#dc3545;">*</span>
                            </label>
                            <input type="number" name="prix_vente" value="{{ old('prix_vente', $variant->prix_vente) }}" min="0" required
                                style="width:100%;padding:12px 16px;border:2px solid #e8e0d5;border-radius:8px;font-size:0.95rem;outline:none;background:white;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.95rem;margin-bottom:6px;">
                                <i class="fas fa-exclamation-triangle" style="color:#b8860b;margin-right:8px;"></i>
                                Stock minimum
                            </label>
                            <input type="number" name="stock_minimum" value="{{ old('stock_minimum', $variant->stock_minimum) }}" min="0"
                                style="width:100%;padding:12px 16px;border:2px solid #e8e0d5;border-radius:8px;font-size:0.95rem;outline:none;background:white;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>
                    </div>

                    {{-- Actif --}}
                    <div style="margin-bottom:25px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px;background:#f8f5f0;border-radius:8px;border:1px solid #e8e0d5;">
                            <input type="checkbox" name="actif" value="1" {{ old('actif', $variant->actif) ? 'checked' : '' }}
                                style="width:18px;height:18px;accent-color:#2d5a27;cursor:pointer;">
                            <span style="font-weight:600;color:#2d5a27;">
                                Variante active (visible dans la boutique)
                            </span>
                        </label>
                    </div>

                    {{-- Boutons --}}
                    <div style="display:flex;gap:15px;flex-wrap:wrap;padding-top:15px;border-top:1px solid #e8e0d5;">
                        <button type="submit" style="background:#2d5a27;color:white;padding:14px 35px;border-radius:30px;border:none;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:10px;"
                            onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.produits.variants.index', $product) }}" style="background:#e8e0d5;color:#2d5a27;padding:14px 30px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                            onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection