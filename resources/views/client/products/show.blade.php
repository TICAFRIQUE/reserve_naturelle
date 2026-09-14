@extends('layouts.app')

@section('title', $product->designation . ' - La Réserve Naturelle')

@section('content')

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
         PRODUIT : IMAGE + INFOS
    ============================================ -->
    <div class="product-show-grid">

        <!-- Colonne image -->
        <div class="product-show-image-card">
            <div class="product-show-image">
                <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}"
                     alt="{{ $product->designation }}">
            </div>

            <div class="product-badges">
                @if($product->prix_vente < 1000)
                    <span class="badge badge-promo">🔥 Promo</span>
                @endif
                @if($product->created_at->diffInDays(now()) < 7)
                    <span class="badge badge-new">🆕 Nouveau</span>
                @endif
                @if($product->qte_dispo > 10)
                    <span class="badge badge-stock">✓ En stock</span>
                @elseif($product->qte_dispo > 0)
                    <span class="badge badge-limited">⚠ Stock limité</span>
                @else
                    <span class="badge badge-out">✗ Rupture</span>
                @endif
            </div>
        </div>

        <!-- Colonne infos -->
        <div class="product-show-info">
            <h1>{{ $product->designation }}</h1>

            <p class="product-show-category">
                Catégorie :
                <span>{{ $product->category->nom ?? 'Non catégorisé' }}</span>
            </p>

            <!-- Prix -->
            <div class="product-show-price-box">
                <span class="product-show-price">
                    {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                </span>
                @if($product->prix_vente < 1000)
                    <span class="product-show-price-old">
                        {{ number_format($product->prix_vente * 1.3, 0, ',', ' ') }} FCFA
                    </span>
                    <span class="product-show-discount">-30%</span>
                @endif
            </div>

            <!-- Description -->
            <div class="product-show-block">
                <h3>Description</h3>
                <p>{{ $product->description ?? 'Aucune description disponible pour ce produit.' }}</p>
            </div>

            @if($product->reference_prod)
                <p class="product-show-ref">Référence : {{ $product->reference_prod }}</p>
            @endif

            <p class="product-show-stock">
                <strong>Quantité disponible :</strong>
                {{ $product->qte_dispo > 0 ? $product->qte_dispo . ' unité(s)' : 'Rupture de stock' }}
            </p>

            <!-- Actions -->
            @if($product->qte_dispo > 0)
                <div class="product-show-actions">
                    <div class="qty-field">
                        <label for="qte">Quantité :</label>
                        <input type="number" id="qte" value="1" min="1" max="{{ $product->qte_dispo }}">
                    </div>
                    <x-add-to-cart-button
                        :product-id="$product->id"
                        qte-input-id="qte"
                        label="🛒 Ajouter au panier"
                        class="btn-add-cart-large" />
                </div>
            @else
                <div class="product-show-out">
                    Ce produit est actuellement en rupture de stock
                </div>
            @endif
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
                                    {{ number_format($similar->prix_vente, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

</div>

@endsection