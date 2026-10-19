@extends('layouts.app')

@section('title', $category->nom . ' - La Réserve Naturelle')
@section('meta_description', 'Découvrez tous nos produits ' . $category->nom . ' chez La Réserve Naturelle : qualité naturelle et locale en Côte d\'Ivoire.')

{{-- Cache le slider global de la page d'accueil --}}
@section('hide_slider', true)

@section('content')

{{-- Partagé (variables, modale) puis spécifique catégorie --}}
<link rel="stylesheet" href="{{ asset('css/pages/_shop-common.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/_category.css') }}">

@php
    $count     = $products->count();
    $plural    = $count > 1 ? 's' : '';
    // Le bandeau de catégories n'apparaît que si le contrôleur passe $categories
    $showStrip = isset($categories) && $categories->isNotEmpty();
@endphp

<div class="cp">

    {{-- ============ FIL D'ARIANE ============ --}}
    <div class="cp-breadcrumb-bar">
        <nav class="cp-container cp-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ route('client.products.catalogue') }}" class="cp-back">
                <i class="fas fa-arrow-left" aria-hidden="true"></i><span>Retour</span>
            </a>
            <span class="cp-sep" aria-hidden="true">›</span>
            <a href="{{ route('client.products.catalogue') }}" class="cp-crumb">Boutique</a>
            <span class="cp-sep" aria-hidden="true">›</span>
            <span class="cp-current" aria-current="page">{{ $category->nom }}</span>

            <div class="cp-meta">
                <span class="cp-pill"><i class="fas fa-th" aria-hidden="true"></i>{{ $count }} produit{{ $plural }}</span>
                <span class="cp-pill"><i class="far fa-clock" aria-hidden="true"></i><time id="cpClock">--:--</time></span>
            </div>
        </nav>
    </div>

    {{-- ============ BANNIÈRE ============ --}}
    <section class="cp-hero">
        @if($category->image_path)
            <img src="{{ $category->image_url }}" alt="" class="cp-hero-bg" fetchpriority="high"
                 onerror="this.remove()">
        @endif
        <div class="cp-hero-overlay"></div>
        <div class="cp-hero-content">
            <h1>{{ $category->nom }}</h1>
            <p>{{ $category->description ?: 'Découvrez notre sélection « ' . $category->nom . ' », de qualité naturelle et locale.' }}</p>
        </div>
    </section>

    {{-- ============ BANDEAU DES CATÉGORIES ============ --}}
    @if($showStrip)
        <div class="cp-container">
            <section class="cp-strip" aria-label="Catégories">
                <div class="cp-strip-track" id="cpStripTrack">

                    <a href="{{ route('client.products.catalogue') }}" class="cp-strip-card">
                        <span class="cp-strip-badge">{{ $categories->sum('products_count') }}</span>
                        <span class="cp-strip-all" aria-hidden="true"><i class="fas fa-th-large"></i></span>
                        <span class="cp-strip-name">Tous</span>
                        <span class="cp-strip-count">{{ $categories->sum('products_count') }} produits</span>
                    </a>

                    @foreach($categories as $cat)
                        {{-- ⚠ adapte le nom de route à ta page catégorie --}}
                        <a href="{{ route('client.products.category', $cat) }}"
                           class="cp-strip-card {{ $cat->id === $category->id ? 'is-active' : '' }}"
                           @if($cat->id === $category->id) aria-current="page" @endif>
                            <span class="cp-strip-badge">{{ $cat->products_count }}</span>
                            <img src="{{ $cat->image_url }}" alt="" loading="lazy">
                            <span class="cp-strip-name">{{ $cat->nom }}</span>
                            <span class="cp-strip-count">{{ $cat->products_count }} produit{{ $cat->products_count > 1 ? 's' : '' }}</span>
                        </a>
                    @endforeach
                </div>
                <div class="cp-dots" id="cpStripDots" hidden></div>
            </section>
        </div>
    @endif

    {{-- ============ PRODUITS ============ --}}
    <section class="cp-products">
        <div class="cp-container">

            @if($count > 0)

                <header class="cp-products-head">
                    <h2>{{ $category->nom }}</h2>
                    <p>{{ $count }} produit{{ $plural }} trouvé{{ $plural }}</p>
                </header>

                <div class="cp-grid">
                    @foreach($products as $product)
                        @php
                            $variant = $product->variants->first();
                            $inStock = $product->variants->sum('qte_dispo') > 0;
                        @endphp

                        <article class="catalogue-product-card">

                            @if($inStock)
                                <span class="product-status-badge stock"><i class="fas fa-check-circle" aria-hidden="true"></i>En stock</span>
                            @else
                                <span class="product-status-badge out"><i class="fas fa-times-circle" aria-hidden="true"></i>Rupture</span>
                            @endif

                            <a href="{{ route('client.products.show', $product) }}" class="product-link" tabindex="-1" aria-hidden="true">
                                <div class="product-image-box">
                                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}"
                                         alt="" loading="lazy">
                                </div>
                            </a>

                            <div class="product-info">
                                <h3>
                                    <a href="{{ route('client.products.show', $product) }}">{{ $product->designation }}</a>
                                </h3>
                                <p class="product-price">
                                    @if($product->variants->count() > 1)
                                        À partir de {{ number_format($product->variants->min('prix_vente'), 0, ',', ' ') }} FCFA
                                    @else
                                        {{ number_format($variant->prix_vente ?? 0, 0, ',', ' ') }} FCFA
                                    @endif
                                </p>
                            </div>

                            <div class="product-action">
                                @if($inStock)
                                    <button type="button"
                                            class="open-variants-modal-btn product-btn-add"
                                            data-product-id="{{ $product->id }}"
                                            data-cart-url="{{ route('client.cart.store') }}">
                                        <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                        Ajouter au panier
                                    </button>
                                @else
                                    <button type="button" class="product-btn-reserve" disabled>
                                        <i class="far fa-clock" aria-hidden="true"></i>
                                        Réserver
                                    </button>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Modales variantes (hors de la grille pour ne pas casser le layout) --}}
                @foreach($products as $product)
                    <div class="variants-modal" id="variants-modal-{{ $product->id }}" style="display:none;">
                        <div class="variants-modal-overlay"></div>
                        <div class="variants-modal-content" role="dialog" aria-modal="true"
                             aria-label="Options pour {{ $product->designation }}">
                            <div class="variants-modal-header">
                                <h3>{{ $product->designation }}</h3>
                                <button type="button" class="variants-modal-close" aria-label="Fermer">&times;</button>
                            </div>

                            <div class="variants-modal-body">
                                <p class="variants-modal-subtitle">Choisissez une option</p>

                                <div class="variants-list">
                                    @foreach($product->variants as $v)
                                        <div class="variant-row {{ $v->qte_dispo <= 0 ? 'is-out' : '' }}">
                                            <div class="variant-col-condition">
                                                <div class="variant-condition">{{ $v->conditionnement }}</div>
                                            </div>

                                            <div class="variant-col-price">
                                                <div class="variant-price">
                                                    {{ number_format($v->prix_vente, 0, ',', ' ') }} <small>FCFA</small>
                                                </div>
                                            </div>

                                            <div class="variant-col-actions">
                                                @if($v->qte_dispo > 0)
                                                    <div class="qty-controls" data-variant-id="{{ $v->id }}">
                                                        <button type="button" class="qty-btn-minus" aria-label="Diminuer">−</button>
                                                        <input type="number"
                                                               min="1"
                                                               max="{{ $v->qte_dispo }}"
                                                               value="1"
                                                               class="qty-input"
                                                               data-variant-id="{{ $v->id }}">
                                                        <button type="button" class="qty-btn-plus" aria-label="Augmenter">+</button>
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
                                <a href="{{ route('client.cart.index') }}" class="btn-modal-cart">Voir le panier</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="cp-empty">
                    <div class="cp-empty-icon" aria-hidden="true">📦</div>
                    <h2>Aucun produit dans cette catégorie</h2>
                    <p>Les produits arrivent bientôt. Parcourez le reste de la boutique en attendant.</p>
                    <a href="{{ route('client.products.catalogue') }}" class="cp-empty-btn">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Retour à la boutique
                    </a>
                </div>
            @endif

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/client/catalogue.js') }}" defer></script>
@endpush