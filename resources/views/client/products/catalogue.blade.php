{{-- resources/views/client/products/catalogue.blade.php --}}
@extends('layouts.app')

@section('title', 'Catalogue Complet - La Réserve Naturelle')

@section('content')

<!-- ====== EN-TÊTE ====== -->
<div style="background: linear-gradient(135deg, var(--green-dark) 0%, var(--green) 100%); padding: 60px 20px; text-align: center; color: white; margin-bottom: 40px;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 42px; margin-bottom: 15px;">📦 Catalogue Complet</h1>
        <p style="font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto;">
            Découvrez l'ensemble de nos produits naturels soigneusement sélectionnés
        </p>

        <!-- Statistiques -->
        <div style="display: flex; justify-content: center; gap: 50px; margin-top: 30px; flex-wrap: wrap;">
            <div>
                <span style="font-size: 32px; font-weight: 700; display: block;">{{ $totalProducts ?? 0 }}</span>
                <span style="font-size: 14px; opacity: 0.8;">Produits</span>
            </div>
            <div>
                <span style="font-size: 32px; font-weight: 700; display: block;">{{ $totalCategories ?? 0 }}</span>
                <span style="font-size: 14px; opacity: 0.8;">Catégories</span>
            </div>
            <div>
                <span style="font-size: 32px; font-weight: 700; display: block;">{{ $totalInStock ?? 0 }}</span>
                <span style="font-size: 14px; opacity: 0.8;">En stock</span>
            </div>
        </div>
    </div>
</div>

<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px 60px;">
    <!-- Barre de recherche et filtres -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <form action="{{ route('client.products.catalogue') }}" method="GET" id="catalogueForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
                <!-- Recherche -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 5px; color: var(--text);">🔍 Rechercher</label>
                    <input type="text" name="search" placeholder="Nom du produit..."
                           value="{{ request('search') }}"
                           style="width: 100%; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                </div>

                <!-- Catégorie -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 5px; color: var(--text);">📂 Catégorie</label>
                    <select name="category" style="width: 100%; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom ?? $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tri -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 5px; color: var(--text);">📊 Trier par</label>
                    <select name="sort" style="width: 100%; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
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
                <div>
                    <button type="submit" style="background: var(--green); color: white; padding: 10px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%;">
                        Appliquer
                    </button>
                </div>
            </div>

            <!-- Filtres supplémentaires -->
            <div style="display: flex; gap: 20px; margin-top: 15px; flex-wrap: wrap; align-items: center;">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <label style="font-size: 14px; font-weight: 500;">💰 Prix :</label>
                    <input type="number" name="prix_min" placeholder="Min"
                           value="{{ request('prix_min') }}"
                           style="width: 80px; padding: 8px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 13px;">
                    <span style="color: var(--muted);">-</span>
                    <input type="number" name="prix_max" placeholder="Max"
                           value="{{ request('prix_max') }}"
                           style="width: 80px; padding: 8px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 13px;">
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <label style="font-size: 14px; font-weight: 500;">📦 Disponibilité :</label>
                    <select name="disponible" style="padding: 8px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 13px;">
                        <option value="">Tous</option>
                        <option value="oui" {{ request('disponible') == 'oui' ? 'selected' : '' }}>En stock</option>
                        <option value="non" {{ request('disponible') == 'non' ? 'selected' : '' }}>Rupture</option>
                    </select>
                </div>

                @if(request()->hasAny(['search', 'category', 'sort', 'prix_min', 'prix_max', 'disponible']))
                    <a href="{{ route('client.products.catalogue') }}" style="color: var(--red); font-size: 14px; text-decoration: none;">
                        ✕ Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Résultats -->
    <div style="display: flex; gap: 30px;">
        <!-- Sidebar gauche -->
        <div style="width: 250px; flex-shrink: 0;">
            <!-- Filtres rapides -->
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px;">
                <h3 style="font-size: 16px; margin-bottom: 15px; color: var(--text);">📂 Catégories</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 8px;">
                        <a href="{{ route('client.products.catalogue') }}" style="color: var(--text); text-decoration: none; font-size: 14px; {{ !request('category') ? 'font-weight: 700; color: var(--green);' : '' }}">
                            Tous les produits
                            <span style="color: var(--muted); font-weight: 400;">({{ $totalProducts ?? 0 }})</span>
                        </a>
                    </li>
                    @foreach($categories ?? [] as $category)
                        <li style="margin-bottom: 8px;">
                            <a href="{{ route('client.products.catalogue', ['category' => $category->id]) }}"
                               style="color: var(--text); text-decoration: none; font-size: 14px; {{ request('category') == $category->id ? 'font-weight: 700; color: var(--green);' : '' }}">
                                {{ $category->nom ?? $category->name }}
                                <span style="color: var(--muted); font-weight: 400;">({{ $category->products->count() ?? 0 }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Produits récents -->
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                <h3 style="font-size: 16px; margin-bottom: 15px; color: var(--text);">🆕 Nouveautés</h3>
                @forelse($recentProducts ?? [] as $recent)
                    <div style="display: flex; gap: 10px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #f0f0f0;">
                        <img src="{{ $recent->image_path ? asset('storage/' . $recent->image_path) : asset('images/default-product.jpg') }}"
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                        <div>
                            <a href="{{ route('client.products.show', $recent->id) }}" style="text-decoration: none; color: var(--text); font-size: 13px; font-weight: 500;">
                                {{ $recent->designation }}
                            </a>
                            <p style="font-size: 12px; color: var(--muted); margin: 2px 0 0;">
                                {{ number_format($recent->prix_vente, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                @empty
                    <p style="color: var(--muted); font-size: 13px; text-align: center;">Aucun produit récent</p>
                @endforelse
            </div>
        </div>

        <!-- Liste des produits -->
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <p style="color: var(--muted); font-size: 14px;">
                    {{ $products->total() ?? 0 }} produit(s) trouvé(s)
                </p>
            </div>

            @if(($products ?? collect())->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px;">
                    @foreach($products as $product)
                        <article style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;">

                            {{-- Le lien couvre uniquement image + infos, PAS le bloc prix/bouton --}}
                            <a href="{{ route('client.products.show', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
                                <div style="height: 200px; background-image: url('{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}');
                                            background-size: cover; background-position: center; position: relative;">
                                    @if($product->prix_vente < 1000)
                                        <span style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600;">PROMO</span>
                                    @endif
                                    @if($product->qte_dispo > 10)
                                        <span style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px;">En stock</span>
                                    @elseif($product->qte_dispo > 0)
                                        <span style="position: absolute; bottom: 10px; right: 10px; background: rgba(255,152,0,0.9); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px;">Stock limité</span>
                                    @else
                                        <span style="position: absolute; bottom: 10px; right: 10px; background: rgba(244,67,54,0.9); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px;">Rupture</span>
                                    @endif
                                </div>
                                <div style="padding: 15px 15px 0;">
                                    <p style="font-size: 12px; color: var(--muted); margin-bottom: 5px;">
                                        {{ $product->category->nom ?? $product->category->name ?? 'Non catégorisé' }}
                                    </p>
                                    <h3 style="font-size: 16px; margin-bottom: 5px; color: var(--text);">{{ $product->designation }}</h3>
                                    <p style="font-size: 14px; color: var(--muted); margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 40px;">
                                        {{ Str::limit($product->description ?? 'Aucune description', 80) }}
                                    </p>
                                </div>
                            </a>

                            {{-- Bloc prix + action, hors du <a> --}}
                            <div style="padding: 0 15px 15px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 18px; font-weight: 700; color: var(--green-dark);">
                                    {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                                </span>
                                @if($product->qte_dispo > 0)
                                    <x-add-to-cart-button :product-id="$product->id" />
                                @else
                                    <span style="color: var(--muted); font-size: 13px;">Indisponible</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(isset($products) && $products->hasPages())
                    <div style="margin-top: 40px; display: flex; justify-content: center;">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 60px 20px; background: #f5f5f5; border-radius: 12px;">
                    <div style="font-size: 64px; margin-bottom: 20px;">🔍</div>
                    <h3 style="color: var(--text); font-size: 24px; margin-bottom: 10px;">Aucun produit trouvé</h3>
                    <p style="color: var(--muted); max-width: 400px; margin: 0 auto 20px;">
                        Aucun produit ne correspond à vos critères de recherche.
                    </p>
                    <a href="{{ route('client.products.catalogue') }}" style="background: var(--green); color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; display: inline-block;">
                        Voir tous les produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Auto-submit du formulaire au changement de tri ou catégorie
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('#catalogueForm select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('catalogueForm').submit();
        });
    });
});
</script>

<style>
/* Variables CSS si non définies dans le layout */
:root {
    --green: #2e7d32;
    --green-dark: #1b5e20;
    --text: #333333;
    --muted: #6c757d;
    --red: #dc3545;
}
</style>
@endsection