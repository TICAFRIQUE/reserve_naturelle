@extends('layouts.app')

@section('title', $product->designation . ' - La Réserve Naturelle')

@section('content')

@php
    $variants   = $product->variants;
    $variant    = $variants->first();
    $prixMin    = $variants->min('prix_vente');
    $stockTotal = $variants->sum('qte_dispo');
@endphp

<div class="container product-show-wrapper">

    <!-- ============================================
         BREADCRUMB
    ============================================ -->
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Accueil</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('client.products.catalogue') }}">Catalogue</a>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current">{{ $product->designation }}</span>
    </nav>

    <!-- ============================================
         BLOC PRINCIPAL : IMAGE + INFOS
    ============================================ -->
    <div class="product-main-block">
        <!-- IMAGE -->
        <div class="product-main-image">
            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}"
                 alt="{{ $product->designation }}">
        </div>

        <!-- INFOS -->
        <div class="product-main-info">
            <h1>{{ $product->designation }}</h1>

            <p class="product-main-category">
                <i class="fas fa-folder" style="color:#b8860b;"></i>
                {{ $product->category->nom ?? 'Non catégorisé' }}
            </p>

            <div class="product-main-price">
                @if($variants->count() > 1)
                    <span class="price-label"></span>
                    <span class="price-value">{{ number_format($prixMin ?? 0, 0, ',', ' ') }} FCFA</span>
                @else
                    <span class="price-value">{{ number_format($prixMin ?? 0, 0, ',', ' ') }} FCFA</span>
                @endif
            </div>

            <!-- ⚡ Bouton Ajouter → ouvre la modal des variantes -->
            @if($variants->count() > 0 && $stockTotal > 0)
                <button type="button"
                        class="open-variants-modal-btn"
                        data-product-id="{{ $product->id }}"
                        style="background: #2d5a27; color: white; padding: 14px 22px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 1rem; display: inline-flex; align-items: center; gap: 10px; margin-bottom: 25px;">
                    <i class="fas fa-shopping-cart"></i>
                    Ajouter au panier
                </button>
            @endif

            <div class="product-main-description">
                <h3>Description</h3>
                <p>{{ $product->description ?? 'Aucune description disponible pour ce produit.' }}</p>
            </div>

            <!-- Badges -->
            <div class="product-main-badges">
                @if($variant && $variant->prix_vente < 1000)
                    <span class="badge badge-promo">🔥 Promo</span>
                @endif
                @if($product->created_at->diffInDays(now()) < 7)
                    <span class="badge badge-new">🆕 Nouveau</span>
                @endif
                @if($stockTotal > 10)
                    <span class="badge badge-stock">✓ En stock</span>
                @elseif($stockTotal > 0)
                    <span class="badge badge-limited">⚠ Stock limité</span>
                @else
                    <span class="badge badge-out">✗ Rupture</span>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================
         PRODUITS SIMILAIRES
    ============================================ -->
    @if(isset($similarProducts) && $similarProducts->count() > 0)
        <div class="similar-products">
            <h2>Produits similaires</h2>

            <div class="similar-products-grid">
                @foreach($similarProducts as $similar)
                    @php $similarVariant = $similar->variants->first(); @endphp
                    <article class="similar-product-card">
                        <a href="{{ route('client.products.show', $similar->id) }}" class="similar-product-link">
                            <div class="similar-product-image">
                                @if($similar->image_path)
                                    <img src="{{ asset('storage/' . $similar->image_path) }}"
                                         alt="{{ $similar->designation }}">
                                @else
                                    <div class="similar-product-placeholder">
                                        <i class="fas fa-box"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="similar-product-info">
                                <h3>{{ $similar->designation }}</h3>
                                <div class="similar-product-price">
                                    {{ number_format($similarVariant->prix_vente ?? 0, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================
         MODAL VARIANTES
    ============================================ --}}
    @if($variants->count() > 0 && $stockTotal > 0)
        <div class="variants-modal" id="variants-modal-{{ $product->id }}" style="display:none;">
            <div class="variants-modal-overlay"></div>
            <div class="variants-modal-content">
                <div class="variants-modal-header">
                    <h3>{{ $product->designation }}</h3>
                    <button type="button" class="variants-modal-close">&times;</button>
                </div>

                <div class="variants-modal-body">
                    <p class="variants-modal-subtitle">Veuillez sélectionner une option</p>

                    <div class="variants-list">
                        @foreach($variants as $v)
                            <div class="variant-row {{ $v->qte_dispo <= 0 ? 'is-out' : '' }}">
                                <div class="variant-col-condition">
                                    <div class="variant-condition">{{ $v->conditionnement }}</div>
                                </div>

                                <div class="variant-col-price">
                                    <div class="variant-price">{{ number_format($v->prix_vente, 0, ',', ' ') }} <small>FCFA</small></div>
                                </div>

                                <div class="variant-col-actions">
                                    @if($v->qte_dispo > 0)
                                        <div class="qty-controls" data-variant-id="{{ $v->id }}">
                                            <button type="button" class="qty-btn-minus">−</button>
                                            <input type="number" min="1" max="{{ $v->qte_dispo }}" value="1"
                                                   class="qty-input" data-variant-id="{{ $v->id }}">
                                            <button type="button" class="qty-btn-plus">+</button>
                                        </div>

                                        <button type="button"
                                                class="add-to-cart-btn"
                                                data-product-variant-id="{{ $v->id }}"
                                                data-cart-url="{{ route('client.cart.store') }}"
                                                data-qte-input="qty-input-{{ $v->id }}">
                                            Ajouter
                                        </button>
                                    @else
                                        <span class="variant-unavailable">Indisponible</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="variants-modal-footer">
                    <button type="button" class="btn-modal-close">Poursuivre mes achats</button>
                    <a href="{{ route('client.cart.index') }}" class="btn-modal-cart">Accéder au panier</a>
                </div>
            </div>
        </div>
    @endif

</div>

@endsection

@push('styles')
<style>
    /* ============================================
       CONTENEUR PRINCIPAL
    ============================================ */
    .product-show-wrapper {
        padding: 30px 0 60px;
    }

    /* ============================================
       BLOC IMAGE + INFOS
    ============================================ */
    .product-main-block {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 40px;
        margin-bottom: 40px;
        align-items: flex-start;
    }

    .product-main-image {
        background: #f8f5f0;
        border: 1px solid #e8e0d5;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-main-info h1 {
        font-family: 'Playfair Display', serif;
        color: #2d5a27;
        font-size: 2rem;
        margin: 0 0 8px;
        line-height: 1.2;
    }

    .product-main-category {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .product-main-price {
        background: #e8f5e9;
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 15px;
        display: flex;
        align-items: baseline;
        gap: 10px;
    }
    .product-main-price .price-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .product-main-price .price-value {
        color: #2d5a27;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .product-main-description h3 {
        font-size: 1.05rem;
        color: #2d5a27;
        margin-bottom: 8px;
    }
    .product-main-description p {
        color: #6c757d;
        font-size: 0.92rem;
        line-height: 1.7;
    }

    .product-main-badges {
        margin-top: 20px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .product-main-badges .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-promo   { background: #fdecea; color: #c62828; }
    .badge-new     { background: #e3f2fd; color: #1565c0; }
    .badge-stock   { background: #d4edda; color: #155724; }
    .badge-limited { background: #fff3cd; color: #856404; }
    .badge-out     { background: #f8d7da; color: #721c24; }

    /* ============================================
       MODAL VARIANTES
    ============================================ */
    .variants-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .variants-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
    }
    .variants-modal-content {
        position: relative;
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 550px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    .variants-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-bottom: 1px solid #e8e0d5;
    }
    .variants-modal-header h3 {
        margin: 0;
        font-family: 'Playfair Display', serif;
        color: #2d5a27;
        font-size: 1.2rem;
    }
    .variants-modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #6c757d;
        cursor: pointer;
        line-height: 1;
        padding: 0 5px;
        transition: color 0.2s;
    }
    .variants-modal-close:hover {
        color: #2d5a27;
    }

    .variants-modal-body {
        padding: 20px 25px;
        overflow-y: auto;
        flex: 1;
    }
    .variants-modal-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .variants-modal-footer {
        display: flex;
        gap: 10px;
        padding: 15px 25px;
        border-top: 1px solid #e8e0d5;
        background: #f8f5f0;
    }
    .btn-modal-close,
    .btn-modal-cart {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-modal-close {
        background: white;
        color: #2d5a27;
        border: 1px solid #2d5a27;
    }
    .btn-modal-close:hover {
        background: #e8f5e9;
    }
    .btn-modal-cart {
        background: #2d5a27;
        color: white;
        border: 1px solid #2d5a27;
    }
    .btn-modal-cart:hover {
        background: #1e3d1a;
    }

    /* Lignes de variantes dans la modal */
    .variants-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .variant-row {
        display: grid;
        grid-template-columns: 1.3fr 1fr 1.4fr;
        gap: 15px;
        align-items: center;
        padding: 14px 16px;
        background: white;
        border: 1px solid #e8e0d5;
        border-radius: 10px;
        transition: all 0.2s;
    }
    .variant-row:hover {
        border-color: #c8e6c9;
    }
    .variant-row.is-out {
        background: #f8f5f0;
        opacity: 0.6;
    }
    .variant-condition {
        font-weight: 700;
        color: #2d5a27;
        font-size: 1rem;
    }
    .variant-price {
        font-weight: 700;
        color: #2d5a27;
        font-size: 1rem;
    }
    .variant-price small {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }

    .variant-col-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: flex-end;
    }

    /* Contrôles quantité */
    .qty-controls {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #e8e0d5;
        border-radius: 8px;
        padding: 3px;
        background: white;
    }
    .qty-btn-minus,
    .qty-btn-plus {
        width: 28px;
        height: 28px;
        border: none;
        background: #f8f5f0;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        color: #2d5a27;
        font-size: 1rem;
        transition: background 0.2s;
    }
    .qty-btn-minus:hover,
    .qty-btn-plus:hover {
        background: #e8e0d5;
    }
    .qty-input {
        width: 40px;
        padding: 4px;
        text-align: center;
        border: none;
        background: transparent;
        outline: none;
        font-weight: 600;
        color: #2d5a27;
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .add-to-cart-btn {
        background: #2d5a27;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }
    .add-to-cart-btn:hover {
        background: #1e3d1a;
    }

    .variant-unavailable {
        color: #adb5bd;
        font-size: 0.85rem;
        font-style: italic;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 900px) {
        .product-main-block {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        .product-main-image {
            max-width: 380px;
            margin: 0 auto;
        }
        .product-main-info h1 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 600px) {
        .variant-row {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .variant-col-actions {
            grid-column: span 2;
            justify-content: space-between;
        }
        .variants-modal-footer {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Ouvrir la modal variantes =====
    document.querySelectorAll('.open-variants-modal-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const modal = document.getElementById('variants-modal-' + this.dataset.productId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                initQtyControls(modal);
            }
        });
    });

    // ===== Fermer la modal =====
    document.querySelectorAll('.variants-modal-overlay, .variants-modal-close, .btn-modal-close').forEach(el => {
        el.addEventListener('click', function () {
            const modal = this.closest('.variants-modal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });

    // ===== Contrôles quantité (+ / −) =====
    function initQtyControls(container) {
        container.querySelectorAll('.variant-row').forEach(row => {
            const input = row.querySelector('.qty-input');
            const btnMinus = row.querySelector('.qty-btn-minus');
            const btnPlus  = row.querySelector('.qty-btn-plus');

            if (!input) return;

            // Éviter de re-binder sur un input déjà traité
            if (input.dataset.bound === '1') return;
            input.dataset.bound = '1';

            input.id = 'qty-input-' + input.dataset.variantId;

            btnMinus?.addEventListener('click', () => {
                let v = parseInt(input.value) || 1;
                if (v > 1) input.value = v - 1;
            });
            btnPlus?.addEventListener('click', () => {
                let v = parseInt(input.value) || 1;
                let max = parseInt(input.max) || 999;
                if (v < max) input.value = v + 1;
            });
        });
    }
});
</script>
@endpush