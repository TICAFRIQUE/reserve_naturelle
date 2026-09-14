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
                <!-- Recherche -->
                <div class="filter-field">
                    <label>🔍 Rechercher</label>
                    <input type="text" name="search" placeholder="Nom du produit..."
                           value="{{ request('search') }}">
                </div>

                <!-- Catégorie -->
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

                <!-- Tri -->
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

                <!-- Bouton -->
                <div class="filter-field filter-submit">
                    <button type="submit" class="btn-apply">Appliquer</button>
                </div>
            </div>

            <!-- Filtres secondaires -->
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

            <!-- Catégories -->
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

            <!-- Nouveautés -->
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
                            <p>{{ number_format($recent->prix_vente, 0, ',', ' ') }} FCFA</p>
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
                        <article class="catalogue-product-card">

                            <a href="{{ route('client.products.show', $product->id) }}" class="product-link">
                                <div class="product-image"
                                     style="background-image: url('{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}');">

                                    @if($product->prix_vente < 1000)
                                        <span class="badge badge-promo">PROMO</span>
                                    @endif

                                    @if($product->qte_dispo > 10)
                                        <span class="badge badge-stock">En stock</span>
                                    @elseif($product->qte_dispo > 0)
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
                                    {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                                </span>
                                @if($product->qte_dispo > 0)
                                    <x-add-to-cart-button :product-id="$product->id" />
                                @else
                                    <span class="unavailable">Indisponible</span>
                                @endif
                            </div>
                        </article>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('#catalogueForm select');
    selects.forEach(select => {
        select.addEventListener('change', function () {
            document.getElementById('catalogueForm').submit();
        });
    });
});
</script>

@endsection