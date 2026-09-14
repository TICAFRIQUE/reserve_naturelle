@extends('layouts.app')

@section('title', 'La Réserve Naturelle - Catalogue')

@php
    $isPaginated = $products instanceof \Illuminate\Contracts\Pagination\Paginator;
@endphp

@section('content')

<!-- ============================================
     BANNIÈRE
============================================ -->
<section class="home-banner">
    <div class="home-banner-inner">
        <div class="home-banner-content">
            <span class="eyebrow">Expérience Nature &amp; Terroir</span>
            <h1>Le meilleur de la<br><span>terre ivoirienne</span></h1>
            <p>Des produits naturels sélectionnés directement auprès des producteurs locaux, pour une qualité authentique jusqu'à votre table.</p>
            <a href="#catalogue" class="btn">Découvrir le catalogue</a>
        </div>
        <div class="home-banner-icons">
            <div class="icon-box">🌾<small>Céréales</small></div>
            <div class="icon-box">🌿<small>Huiles</small></div>
            <div class="icon-box">🥜<small>Légumes</small></div>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION PRODUITS
============================================ -->
<section class="section" id="catalogue">
    <div class="container">
        <div class="section-heading">
            <p>Notre sélection</p>
            <h2>Produits du moment</h2>
            @if(($isPaginated ? $products->total() : $products->count()) > 0)
                <p class="section-count">
                    {{ $isPaginated ? $products->total() : $products->count() }} produit(s) trouvé(s)
                </p>
            @endif
        </div>

        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <article class="product-card">

                        <a href="{{ route('client.products.show', $product->id) }}">
    <div class="product-img">
        @if($product->image_path)
            <img src="{{ asset('storage/' . $product->image_path) }}"
                 alt="{{ $product->designation }}">
        @else
            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#b8860b;font-size:48px;">
                📦
            </div>
        @endif

        {{-- Badge PROMO (produits < 1000 FCFA) --}}
        @if(($product->prix_vente ?? 0) < 1000)
            <span class="product-badge promo">🔥 Promo</span>
        @endif

        {{-- Badge STOCK (en haut à droite si pas promo, sinon en bas) --}}
        @if(($product->qte_dispo ?? 0) > 10)
            <span class="product-badge stock">✓ En stock</span>
        @elseif(($product->qte_dispo ?? 0) > 0)
            <span class="product-badge limited">⚠ Stock limité</span>
        @else
            <span class="product-badge out">✗ Rupture</span>
        @endif
    </div>
</a>

                        <div class="product-body">
                            @if($product->category)
                                <span class="tag">{{ $product->category->nom ?? $product->category->name ?? '' }}</span>
                            @endif

                            <h3>
                                <a href="{{ route('client.products.show', $product->id) }}">
                                    {{ $product->designation }}
                                </a>
                            </h3>

                            @if($product->description)
                                <p>{{ Str::limit($product->description, 80) }}</p>
                            @endif

                            <div class="product-footer">
                                <strong>{{ number_format($product->prix_vente ?? 0, 0, ',', ' ') }} FCFA</strong>

                                @if(($product->qte_dispo ?? 0) > 0)
                                    <x-add-to-cart-button :product-id="$product->id" />
                                @else
                                    <span style="color:var(--muted);font-size:13px;">Indisponible</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($isPaginated && $products->hasPages())
                <div class="home-pagination">
                    {{ $products->links() }}
                </div>
            @elseif(!$isPaginated)
                <div class="home-pagination">
                    <a href="{{ route('client.products.catalogue') }}" class="btn-primary">
                        Voir tout le catalogue →
                    </a>
                </div>
            @endif

        @else
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3>Aucun produit trouvé</h3>
                <p>Aucun produit ne correspond à vos critères de recherche. Essayez de modifier vos filtres.</p>
                <a href="{{ route('client.products.index') }}" class="btn-primary">
                    Voir tous les produits
                </a>
            </div>
        @endif

        @guest
            <div class="guest-cta">
                <div class="guest-cta-icon">🛒</div>
                <h3>Vous souhaitez commander ?</h3>
                <p>Connectez-vous ou créez un compte pour passer votre commande et profiter de nos produits naturels.</p>
                <div class="guest-cta-actions">
                    <a href="{{ route('login') }}" class="btn-primary">🔐 Se connecter</a>
                    <a href="{{ route('register') }}" class="btn-outline">📝 S'inscrire</a>
                </div>
            </div>
        @endguest
    </div>
</section>

@endsection