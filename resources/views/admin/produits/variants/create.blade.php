@extends('layouts.admin')

@section('title', 'Nouvelle variante - ' . $product->designation)

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- En-tête --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px;">
                <div>
                    <h1 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:2rem;margin:0;">
                        <i class="fas fa-plus-circle" style="color:#b8860b;margin-right:10px;"></i>
                        Nouvelle variante
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

            {{-- Formulaire --}}
            <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:30px;max-width:700px;">
                <form action="{{ route('admin.produits.variants.store', $product) }}" method="POST">
                    @csrf

                    {{-- Conditionnement --}}
                    <div style="margin-bottom:22px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.95rem;margin-bottom:6px;">
                            <i class="fas fa-box" style="color:#b8860b;margin-right:8px;"></i>
                            Conditionnement <span style="color:#dc3545;">*</span>
                        </label>
                        <input type="text" name="conditionnement" value="{{ old('conditionnement') }}" required
                            placeholder="Ex: Sac 50kg, Carton, Unité..."
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
                            <input type="number" name="prix_vente" value="{{ old('prix_vente') }}" min="0" required
                                placeholder="0"
                                style="width:100%;padding:12px 16px;border:2px solid #e8e0d5;border-radius:8px;font-size:0.95rem;outline:none;background:white;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.95rem;margin-bottom:6px;">
                                <i class="fas fa-exclamation-triangle" style="color:#b8860b;margin-right:8px;"></i>
                                Stock minimum
                            </label>
                            <input type="number" name="stock_minimum" value="{{ old('stock_minimum', 0) }}" min="0"
                                style="width:100%;padding:12px 16px;border:2px solid #e8e0d5;border-radius:8px;font-size:0.95rem;outline:none;background:white;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>
                    </div>

                    {{-- Stock initial + CMP (optionnels) --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:22px;padding:15px;background:#faf8f5;border-radius:8px;border:1px dashed #e8e0d5;">
                        <div>
                            <label style="display:block;font-weight:600;color:#6c757d;font-size:0.85rem;margin-bottom:6px;">
                                Stock initial (optionnel)
                            </label>
                            <input type="number" name="qte_dispo" value="{{ old('qte_dispo', 0) }}" min="0"
                                style="width:100%;padding:10px 14px;border:1px solid #e8e0d5;border-radius:8px;font-size:0.9rem;outline:none;background:white;box-sizing:border-box;">
                            <small style="color:#6c757d;font-size:0.75rem;">Si tu démarres avec du stock existant</small>
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;color:#6c757d;font-size:0.85rem;margin-bottom:6px;">
                                CMP initial (optionnel)
                            </label>
                            <input type="number" name="cmp" value="{{ old('cmp', 0) }}" min="0"
                                style="width:100%;padding:10px 14px;border:1px solid #e8e0d5;border-radius:8px;font-size:0.9rem;outline:none;background:white;box-sizing:border-box;">
                            <small style="color:#6c757d;font-size:0.75rem;">Coût moyen pondéré de départ</small>
                        </div>
                    </div>

                    {{-- Actif --}}
                    <div style="margin-bottom:25px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px;background:#f8f5f0;border-radius:8px;border:1px solid #e8e0d5;">
                            <input type="checkbox" name="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
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
                            <i class="fas fa-check"></i> Enregistrer
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