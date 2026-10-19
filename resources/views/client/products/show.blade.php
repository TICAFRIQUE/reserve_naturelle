@extends('layouts.app')

@section('title', $product->designation . ' - La Réserve Naturelle')

@section('meta_description', Str::limit(
    $product->description
        ? $product->description . ' Découvrez ce produit naturel et local sur La Réserve Naturelle, votre boutique en Côte d’Ivoire.'
        : 'Découvrez ' . $product->designation . ', un produit naturel et local disponible sur La Réserve Naturelle en Côte d’Ivoire.',
    160
))

@section('og_type', 'product')
@section('og_title', $product->designation . ' - La Réserve Naturelle')
@section('og_description', Str::limit(
    $product->description
        ? $product->description . ' Découvrez ce produit naturel et local sur La Réserve Naturelle, votre boutique en Côte d’Ivoire.'
        : 'Découvrez ' . $product->designation . ', un produit naturel et local disponible sur La Réserve Naturelle en Côte d’Ivoire.',
    160
))
@section('og_image', $product->image_path
    ? asset('storage/' . $product->image_path)
    : asset('img/logo.webp')
)
@section('og_image_alt', $product->designation)

@php
    // $product->variants : variantes ACTIVES, triées par prix croissant (cf. ProductController::show)
    $variants    = $product->variants;
    $prixMin     = $variants->min('prix_vente');
    $prixMax     = $variants->max('prix_vente');
    $stockTotal  = $variants->sum('qte_dispo');
    $hasStock    = $variants->count() > 0 && $stockTotal > 0;
    $category    = $product->category;
    $categoryUrl = $category ? route('client.products.category', $category) : route('client.products.catalogue');
    $productUrl  = route('client.products.show', $product);
    $productImg  = $product->image_path
        ? asset('storage/' . $product->image_path)
        : asset('images/default-product.jpg');
    $isNew       = (bool) $product->created_at?->gt(now()->subDays(7));
    $descText    = trim((string) $product->description);
    $shareUrl    = 'https://wa.me/?text=' . rawurlencode($product->designation . ' - ' . $productUrl);

    // Schéma Product
    $productSchema = [
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => $product->designation,
        'description' => Str::limit(
            $descText !== '' ? $descText : 'Produit naturel et local disponible sur La Réserve Naturelle en Côte d’Ivoire.',
            300
        ),
        'image'       => [$productImg],
        'sku'         => 'PROD-' . $product->id,
        'category'    => $category->nom ?? 'Non catégorisé',
        'brand'       => ['@type' => 'Brand', 'name' => 'La Réserve Naturelle'],
        'url'         => $productUrl,
    ];

    if ($variants->count() > 0) {
        $productSchema['offers'] = [
            '@type'         => 'AggregateOffer',
            'priceCurrency' => 'XOF',
            'lowPrice'      => $prixMin ?? 0,
            'highPrice'     => $prixMax ?? $prixMin ?? 0,
            'offerCount'    => $variants->count(),
            'availability'  => $hasStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url'           => $productUrl,
            'seller'        => ['@type' => 'Organization', 'name' => 'La Réserve Naturelle'],
        ];
    }

    // Schéma Breadcrumb : Accueil > Boutique > Catégorie > Produit
    $crumbs = [
        ['name' => 'Accueil',  'item' => route('home')],
        ['name' => 'Boutique', 'item' => route('client.products.catalogue')],
    ];
    if ($category) {
        $crumbs[] = ['name' => $category->nom, 'item' => $categoryUrl];
    }
    $crumbs[] = ['name' => $product->designation, 'item' => $productUrl];

    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => collect($crumbs)->values()->map(fn ($c, $i) => [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $c['name'],
            'item'     => $c['item'],
        ])->all(),
    ];
@endphp

@push('structured_data')
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

{{-- ⚠️ CACHE LE SLIDER GLOBAL SUR CETTE PAGE --}}
@section('hide_slider', true)

@section('content')

<div class="cp pd">

    {{-- ============ FIL D'ARIANE ============ --}}
    <div class="cp-breadcrumb-bar">
        <nav class="cp-container cp-breadcrumb" aria-label="Fil d'Ariane">
            {{-- Retour : page précédente si elle existe, sinon la catégorie --}}
            <a href="{{ $categoryUrl }}" class="cp-back" aria-label="Retour"
               onclick="if (history.length > 1) { history.back(); return false; }">
                <i class="fas fa-arrow-left" aria-hidden="true"></i><span>Retour</span>
            </a>
            <span class="cp-sep" aria-hidden="true">›</span>
            <a href="{{ route('client.products.catalogue') }}" class="cp-crumb">Boutique</a>
            @if($category)
                <span class="cp-sep" aria-hidden="true">›</span>
                <a href="{{ $categoryUrl }}" class="cp-crumb">{{ $category->nom }}</a>
            @endif
            <span class="cp-sep" aria-hidden="true">›</span>
            <span class="cp-current" aria-current="page">{{ $product->designation }}</span>
            <div class="cp-meta">
                <span class="cp-pill"><i class="far fa-clock" aria-hidden="true"></i><time id="cpClock">--:--</time></span>
            </div>
        </nav>
    </div>

    <main class="pd-page">
        <div class="cp-container">

            {{-- ============ PRODUIT ============ --}}
            <article class="pd-card">

                <div class="pd-media">
                    <div class="pd-image">
                        <img src="{{ $productImg }}" alt="{{ $product->designation }}"
                             width="520" height="520" fetchpriority="high">

                        @if(! $hasStock)
                            <span class="pd-status is-out"><i class="fas fa-times-circle" aria-hidden="true"></i>Rupture</span>
                        @elseif($stockTotal > 10)
                            <span class="pd-status is-in"><i class="fas fa-check-circle" aria-hidden="true"></i>En stock</span>
                        @else
                            <span class="pd-status is-low"><i class="fas fa-exclamation-circle" aria-hidden="true"></i>Stock limité</span>
                        @endif
                    </div>
                </div>

                <div class="pd-info">
                    @if($category)
                        <a href="{{ $categoryUrl }}" class="pd-category"
                           title="Voir les produits de la catégorie {{ $category->nom }}">
                            <i class="fas fa-folder" aria-hidden="true"></i>{{ $category->nom }}
                        </a>
                    @endif

                    <h1>{{ $product->designation }}</h1>

                    @if($isNew)
                        <span class="pd-new">Nouveau</span>
                    @endif

                    <div class="pd-price">
                        @if($variants->count() > 1)
                            <span class="pd-price-label">À partir de</span>
                        @endif
                        <span class="pd-price-value">{{ number_format($prixMin ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="pd-actions">
                        @if($hasStock)
                            <button type="button"
                                    class="open-variants-modal-btn pd-btn pd-btn--add"
                                    data-product-id="{{ $product->id }}"
                                    data-cart-url="{{ route('client.cart.store') }}">
                                <i class="fas fa-shopping-cart" aria-hidden="true"></i> Ajouter au panier
                            </button>
                        @else
                            <button type="button" class="pd-btn pd-btn--reserve" disabled>
                                <i class="far fa-clock" aria-hidden="true"></i> Réserver
                            </button>
                        @endif

                        <a href="{{ $shareUrl }}" target="_blank" rel="noopener" class="pd-btn pd-btn--share">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Partager
                        </a>
                    </div>

                    @if($variants->count() > 1)
                        <section class="pd-options" aria-labelledby="pdOptionsTitle">
                            <h2 id="pdOptionsTitle">Conditionnements</h2>
                            <ul>
                                @foreach($variants as $v)
                                    <li class="{{ $v->qte_dispo <= 0 ? 'is-out' : '' }}">
                                        <span class="pd-opt-name">{{ $v->conditionnement }}</span>
                                        <span class="pd-opt-price">{{ number_format($v->prix_vente, 0, ',', ' ') }} FCFA</span>
                                        <span class="pd-opt-state">{{ $v->qte_dispo > 0 ? 'En stock' : 'Indisponible' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    <section class="pd-description">
                        <h2>Description</h2>
                        @if($descText !== '')
                            <p>{!! nl2br(e($descText)) !!}</p>
                        @else
                            <p class="is-empty">Aucune description disponible pour ce produit.</p>
                        @endif
                    </section>
                </div>
            </article>

            {{-- ============ PRODUITS SIMILAIRES ============ --}}
            @if(isset($similarProducts) && $similarProducts->isNotEmpty())
                <section class="pd-similar" aria-labelledby="pdSimilarTitle">
                    <div class="pd-heading"><h2 id="pdSimilarTitle">Produits similaires</h2></div>

                    <div class="pd-similar-grid">
                        @foreach($similarProducts as $similar)
                            <a href="{{ route('client.products.show', $similar) }}" class="pd-sim-card">
                                <span class="pd-sim-media">
                                    @if($similar->image_path)
                                        <img src="{{ asset('storage/' . $similar->image_path) }}" alt="" loading="lazy">
                                    @else
                                        <i class="fas fa-box" aria-hidden="true"></i>
                                    @endif
                                </span>
                                <span class="pd-sim-name">{{ $similar->designation }}</span>
                                <span class="pd-sim-price">{{ number_format($similar->variants->min('prix_vente') ?? 0, 0, ',', ' ') }} FCFA</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

        </div>
    </main>

    {{-- ============ MODALE VARIANTES (logique : catalogue.js) ============ --}}
    @if($hasStock)
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
    @endif

</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/_shop-common.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/_product.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/client/catalogue.js') }}" defer></script>
@endpush