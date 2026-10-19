@extends('layouts.app')

@section('title', 'Catalogue - La Réserve Naturelle')
@section('meta_description', 'Découvrez tous nos produits naturels et locaux en Côte d’Ivoire : céréales, huiles, légumineuses, farines, épices et cosmétiques naturels.')

{{-- Cache le slider global (celui de la page d'accueil) --}}
@section('hide_slider', true)

@section('content')

{{-- Partagé (variables, modale) puis spécifique boutique --}}
<link rel="stylesheet" href="{{ asset('css/pages/_shop-common.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/_catalogue.css') }}">

@php
    // Sections affichables (catégories ayant au moins un produit) ; l'index sert à alterner les couleurs
    $sections = ($categoriesWithProducts ?? collect())
        ->filter(fn ($c) => $c->products->isNotEmpty())
        ->values();
@endphp

<div class="cp bq">
            {{-- ============ FIL D'ARIANE ============ --}}
    <div class="cp-breadcrumb-bar">
        <nav class="cp-container cp-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}" class="cp-back" aria-label="Retour"
               onclick="if (history.length > 1) { history.back(); return false; }">
                <i class="fas fa-arrow-left" aria-hidden="true"></i><span>Retour</span>
            </a>
            <span class="cp-sep" aria-hidden="true">›</span>
            <span class="cp-current" aria-current="page">Catalogue</span>

            <div class="cp-meta">
                <span class="cp-pill"><i class="far fa-clock" aria-hidden="true"></i><time id="cpClock">--:--</time></span>
            </div>
        </nav>
    </div>
    {{-- ============ BANNIÈRES (optionnel : $banners) / repli : bannière texte ============ --}}
    @if(isset($banners) && $banners->isNotEmpty())
        <section class="bq-banner" aria-roledescription="carrousel" aria-label="À la une">
            <div class="bq-banner-track" id="bqBannerTrack" tabindex="0">
                @foreach($banners as $banner)
                    <div class="bq-banner-slide" role="group" aria-roledescription="diapositive"
                         aria-label="{{ $loop->iteration }} sur {{ $loop->count }}">
                        @if(!empty($banner->lien))
                            <a href="{{ $banner->lien }}" aria-label="{{ $banner->titre ?? 'En savoir plus' }}">
                        @endif
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->titre ?? '' }}"
                                 @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                        @if(!empty($banner->lien))
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="bq-dots" id="bqBannerDots" hidden></div>
        </section>
    @else
        <section class="bq-hero">
            <div class="bq-hero-pattern"></div>
            <div class="bq-hero-content">
                <div class="bq-hero-icon"><i class="fas fa-store" aria-hidden="true"></i></div>
                <h1>Notre Catalogue</h1>
                <p>
                    Découvrez tous nos produits naturels et locaux en Côte d'Ivoire :
                    céréales, huiles, légumineuses, farines, épices et cosmétiques naturels.
                </p>
            </div>
            <div class="bq-hero-fade"></div>
        </section>
    @endif

    {{-- ============ CATÉGORIES ============ --}}
    @if(isset($categories) && $categories->isNotEmpty())
        <section class="bq-cats" aria-labelledby="bqCatsTitle">
            <div class="bq-heading"><h2 id="bqCatsTitle">Nos catégories</h2></div>

            <div class="bq-cats-wrap">
                <button type="button" class="bq-arrow bq-arrow--prev" id="bqCatsPrev" aria-label="Catégories précédentes" hidden>
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>

                <div class="bq-cats-track" id="bqCatsTrack">
                    @foreach($categories as $cat)
                        <a href="{{ route('client.products.category', $cat) }}" class="bq-cat">
                            <span class="bq-cat-media">
                                @if($cat->image_path)
                                    <img src="{{ $cat->image_url }}" alt="" loading="lazy">
                                @else
                                    <i class="fas fa-folder" aria-hidden="true"></i>
                                @endif
                            </span>
                            <span class="bq-cat-name">{{ $cat->nom }}</span>
                        </a>
                    @endforeach
                </div>

                <button type="button" class="bq-arrow bq-arrow--next" id="bqCatsNext" aria-label="Catégories suivantes" hidden>
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </section>
    @endif

    {{-- ============ SECTIONS PAR CATÉGORIE ============ --}}
    <div class="bq-page">
        <div class="cp-container">

            @if($sections->isNotEmpty())
                <div class="bq-sections">
                    @foreach($sections as $i => $cat)
                        <section class="bq-section bq-section--c{{ $i % 4 }}" aria-labelledby="bq-sec-{{ $cat->id }}">

                            <div class="bq-side">
                                @if($cat->image_path)
                                    <img src="{{ $cat->image_url }}" alt="" class="bq-side-bg" loading="lazy">
                                @endif
                                <div class="bq-side-content">
                                    <span class="bq-side-title" aria-hidden="true">{{ $cat->nom }}</span>
                                    <a href="{{ route('client.products.category', $cat) }}"
                                       class="bq-side-btn" aria-label="Découvrir {{ $cat->nom }}">
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i> Découvrir
                                    </a>
                                </div>
                            </div>

                            <div class="bq-main">
                                <div class="bq-head">
                                    <h2 id="bq-sec-{{ $cat->id }}">{{ $cat->nom }}</h2>
                                    <a href="{{ route('client.products.category', $cat) }}" class="bq-all">
                                        <i class="fas fa-th" aria-hidden="true"></i> Voir tout
                                    </a>
                                </div>

                                <div class="bq-grid">
                                    @foreach($cat->products as $product)
                                        @php
                                            $variant = $product->variants->first();
                                            $inStock = $product->variants->sum('qte_dispo') > 0;
                                            $price   = $product->variants->count() > 1
                                                ? $product->variants->min('prix_vente')
                                                : ($variant->prix_vente ?? 0);
                                            $url     = route('client.products.show', $product);
                                        @endphp

                                        <article class="bq-card">
                                            <span class="bq-badge {{ $inStock ? 'is-in' : 'is-out' }}">
                                                {{ $inStock ? 'En stock' : 'Rupture' }}
                                            </span>

                                            <a href="{{ $url }}" class="bq-card-media" tabindex="-1" aria-hidden="true">
                                                <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}"
                                                     alt="" loading="lazy">
                                            </a>

                                            <h3 class="bq-card-name">
                                                <a href="{{ $url }}">{{ $product->designation }}</a>
                                            </h3>

                                            <p class="bq-card-price">{{ number_format($price, 0, ',', ' ') }} FCFA</p>

                                            <div class="bq-card-action">
                                                @if($inStock)
                                                    <button type="button"
                                                            class="open-variants-modal-btn bq-btn bq-btn--add"
                                                            data-product-id="{{ $product->id }}"
                                                            data-cart-url="{{ route('client.cart.store') }}">
                                                        <i class="fas fa-shopping-cart" aria-hidden="true"></i> Ajouter
                                                    </button>
                                                @else
                                                    <button type="button" class="bq-btn bq-btn--reserve" disabled>
                                                        <i class="far fa-clock" aria-hidden="true"></i> Réserver
                                                    </button>
                                                @endif
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>

                {{-- Modales variantes (hors des grilles) --}}
                @foreach($sections as $cat)
                    @foreach($cat->products as $product)
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
                                                    <div class="variant-price">{{ number_format($v->prix_vente, 0, ',', ' ') }} <small>FCFA</small></div>
                                                </div>

                                                <div class="variant-col-actions">
                                                    @if($v->qte_dispo > 0)
                                                        <div class="qty-controls" data-variant-id="{{ $v->id }}">
                                                            <button type="button" class="qty-btn-minus" aria-label="Diminuer">−</button>
                                                            <input type="number" min="1" max="{{ $v->qte_dispo }}" value="1"
                                                                   class="qty-input" data-variant-id="{{ $v->id }}">
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
                @endforeach

            @else
                <div class="cp-empty">
                    <div class="cp-empty-icon" aria-hidden="true">📦</div>
                    <h2>Aucun produit disponible</h2>
                    <p>Les produits seront bientôt de retour.</p>
                </div>
            @endif

        </div>
    </div>


</div>
@endsection

@push('scripts')
<script src="{{ asset('js/client/catalogue.js') }}" defer></script>
@endpush