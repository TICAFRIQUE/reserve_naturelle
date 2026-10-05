@extends('layouts.app')

@section('title', 'Catalogue Complet - La Réserve Naturelle')

@section('content')

<!-- ============================================
     EN-TÊTE CATALOGUE
============================================ -->
<div class="catalogue-header">
    <div class="container">
        <h1>📦 Catalogue Complet</h1>
        <p class="catalogue-subtitle">
            Découvrez l'ensemble de nos produits naturels soigneusement sélectionnés
        </p>

        <div class="catalogue-stats">
            <div class="stat">
                <span class="stat-value">{{ $totalProducts ?? 0 }}</span>
                <span class="stat-label">Produits</span>
            </div>
            <div class="stat">
                <span class="stat-value">{{ $totalCategories ?? 0 }}</span>
                <span class="stat-label">Catégories</span>
            </div>
            <div class="stat">
                <span class="stat-value">{{ $totalInStock ?? 0 }}</span>
                <span class="stat-label">En stock</span>
            </div>
        </div>
    </div>
</div>

<div class="container catalogue-wrapper">

    <!-- ============================================
         BARRE DE FILTRES
    ============================================ -->
    <div class="catalogue-filters">
        <form action="{{ route('client.products.catalogue') }}" method="GET" id="catalogueForm">

            <div class="filters-grid">
                <div class="filter-field">
                    <label>🔍 Rechercher</label>
                    <input type="text" name="search" placeholder="Nom du produit..."
                           value="{{ request('search') }}">
                </div>

                <div class="filter-field">
                    <label>📂 Catégorie</label>
                    <select name="category">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom ?? $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-field">
                    <label>📊 Trier par</label>
                    <select name="sort">
                        <option value="designation" {{ request('sort') == 'designation' ? 'selected' : '' }}>Nom (A-Z)</option>
                        <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                        <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock décroissant</option>
                        <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock croissant</option>
                    </select>
                </div>

                <div class="filter-field filter-submit">
                    <button type="submit" class="btn-apply">Appliquer</button>
                </div>
            </div>

            <div class="filters-extra">
                <div class="filter-inline">
                    <label>💰 Prix :</label>
                    <input type="number" name="prix_min" placeholder="Min"
                           value="{{ request('prix_min') }}" class="input-mini">
                    <span class="separator">-</span>
                    <input type="number" name="prix_max" placeholder="Max"
                           value="{{ request('prix_max') }}" class="input-mini">
                </div>

                <div class="filter-inline">
                    <label>📦 Disponibilité :</label>
                    <select name="disponible">
                        <option value="">Tous</option>
                        <option value="oui" {{ request('disponible') == 'oui' ? 'selected' : '' }}>En stock</option>
                        <option value="non" {{ request('disponible') == 'non' ? 'selected' : '' }}>Rupture</option>
                    </select>
                </div>

                @if(request()->hasAny(['search', 'category', 'sort', 'prix_min', 'prix_max', 'disponible']))
                    <a href="{{ route('client.products.catalogue') }}" class="btn-reset">
                        ✕ Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ============================================
         RÉSULTATS
    ============================================ -->
    <div class="catalogue-layout">

        <!-- SIDEBAR -->
        <aside class="catalogue-sidebar">

            <div class="sidebar-card">
                <h3>📂 Catégories</h3>
                <ul class="sidebar-list">
                    <li>
                        <a href="{{ route('client.products.catalogue') }}"
                           class="{{ !request('category') ? 'active' : '' }}">
                            Tous les produits
                            <span class="count">({{ $totalProducts ?? 0 }})</span>
                        </a>
                    </li>
                    @foreach($categories ?? [] as $category)
                        <li>
                            <a href="{{ route('client.products.catalogue', ['category' => $category->id]) }}"
                               class="{{ request('category') == $category->id ? 'active' : '' }}">
                                {{ $category->nom ?? $category->name }}
                                <span class="count">({{ $category->products->count() ?? 0 }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-card">
                <h3>🆕 Nouveautés</h3>
                @forelse($recentProducts ?? [] as $recent)
                    <div class="recent-item">
                        <img src="{{ $recent->image_path ? asset('storage/' . $recent->image_path) : asset('images/default-product.jpg') }}"
                             alt="{{ $recent->designation }}">
                        <div>
                            <a href="{{ route('client.products.show', $recent->id) }}">
                                {{ $recent->designation }}
                            </a>
                            <p>{{ number_format($recent->prix_vente ?? 0, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                @empty
                    <p class="empty-text">Aucun produit récent</p>
                @endforelse
            </div>
        </aside>

        <!-- LISTE PRODUITS -->
        <div class="catalogue-main">

            <div class="results-header">
                <p>{{ $products->total() ?? 0 }} produit(s) trouvé(s)</p>
            </div>

            @if(($products ?? collect())->count() > 0)
                <div class="catalogue-products-grid">
                    @foreach($products as $product)
                        @php $variant = $product->variants->first(); @endphp
                        <article class="catalogue-product-card">

                            <a href="{{ route('client.products.show', $product->id) }}" class="product-link">
                                <div class="product-image"
                                     style="background-image: url('{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}');">

                                    @if($variant && $variant->prix_vente < 1000)
                                        <span class="badge badge-promo">PROMO</span>
                                    @endif

                                    @if($variant && $variant->qte_dispo > 10)
                                        <span class="badge badge-stock">En stock</span>
                                    @elseif($variant && $variant->qte_dispo > 0)
                                        <span class="badge badge-limited">Stock limité</span>
                                    @else
                                        <span class="badge badge-out">Rupture</span>
                                    @endif
                                </div>

                                <div class="product-info">
                                    <p class="product-category">
                                        {{ $product->category->nom ?? $product->category->name ?? 'Non catégorisé' }}
                                    </p>
                                    <h3>{{ $product->designation }}</h3>
                                    <p class="product-desc">
                                        {{ Str::limit($product->description ?? 'Aucune description', 80) }}
                                    </p>
                                </div>
                            </a>

                            <div class="product-footer">
                                <span class="product-price">
                                    @if($product->variants->count() > 1)
                                         {{ number_format($product->variants->min('prix_vente'), 0, ',', ' ') }} FCFA
                                    @else
                                        {{ number_format($variant->prix_vente ?? 0, 0, ',', ' ') }} FCFA
                                    @endif
                                </span>
                                @if($product->variants->sum('qte_dispo') > 0)
                                    <button type="button"
                                            class="open-variants-modal-btn"
                                            data-product-id="{{ $product->id }}"
                                            data-cart-url="{{ route('client.cart.store') }}"
                                            style="background: var(--green); color: white; padding: 6px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                                        🛒 Ajouter
                                    </button>
                                @else
                                    <span class="unavailable">Indisponible</span>
                                @endif
                            </div>
                        </article>

                        {{-- Modal variantes --}}
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
                                        @foreach($product->variants as $v)
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
                    @endforeach
                </div>

                @if(isset($products) && $products->hasPages())
                    <div class="catalogue-pagination">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                <div class="empty-state">
                    <div class="empty-icon">🔍</div>
                    <h3>Aucun produit trouvé</h3>
                    <p>Aucun produit ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('client.products.catalogue') }}" class="btn-primary">
                        Voir tous les produits
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
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

    /* ============================================
       LIGNES DE VARIANTES (dans la modal)
    ============================================ */
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

    /* Bouton Ajouter dans la modal */
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

    /* Responsive modal */
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
    // ===== Soumission auto du formulaire de filtre =====
    document.querySelectorAll('#catalogueForm select').forEach(select => {
        select.addEventListener('change', function () {
            document.getElementById('catalogueForm').submit();
        });
    });

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