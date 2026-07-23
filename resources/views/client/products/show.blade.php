{{-- resources/views/client/products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->designation . ' - La Réserve Naturelle')

@section('content')

<div class="container" style="padding: 40px 20px;">
    <nav style="margin-bottom: 30px; font-size: 14px;">
        <a href="{{ route('home') }}" style="color: var(--green); text-decoration: none;">Accueil</a>
        <span style="color: var(--muted);"> › </span>
        <a href="{{ route('client.products.index') }}" style="color: var(--green); text-decoration: none;">Catalogue</a>
        <span style="color: var(--muted);"> › </span>
        <span style="color: var(--text);">{{ $product->designation }}</span>
    </nav>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; max-width: 1200px; margin: 0 auto;">

        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 20px;">
            <div style="aspect-ratio: 1; overflow: hidden; border-radius: 8px;">
                <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/default-product.jpg') }}"
                     alt="{{ $product->designation }}"
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
                @if($product->prix_vente < 1000)
                    <span style="background: #e74c3c; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px;">🔥 Promo</span>
                @endif
                @if($product->created_at->diffInDays(now()) < 7)
                    <span style="background: #4CAF50; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px;">🆕 Nouveau</span>
                @endif
                @if($product->qte_dispo > 10)
                    <span style="background: #27ae60; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px;">✓ En stock</span>
                @elseif($product->qte_dispo > 0)
                    <span style="background: #f39c12; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px;">⚠ Stock limité</span>
                @else
                    <span style="background: #e74c3c; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px;">✗ Rupture</span>
                @endif
            </div>
        </div>

        <div>
            <h1 style="font-size: 28px; color: var(--text); margin-bottom: 10px;">{{ $product->designation }}</h1>

            <p style="color: var(--muted); font-size: 14px; margin-bottom: 15px;">
                Catégorie :
                <span style="color: var(--green); font-weight: 600;">
                    {{ $product->category->nom ?? 'Non catégorisé' }}
                </span>
            </p>

            <div style="background: var(--green-light); padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
                <span style="font-size: 32px; font-weight: 700; color: var(--green-dark);">
                    {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                </span>
                @if($product->prix_vente < 1000)
                    <span style="font-size: 16px; color: var(--muted); text-decoration: line-through; margin-left: 15px;">
                        {{ number_format($product->prix_vente * 1.3, 0, ',', ' ') }} FCFA
                    </span>
                    <span style="background: #e74c3c; color: white; padding: 2px 10px; border-radius: 20px; font-size: 12px; margin-left: 10px;">-30%</span>
                @endif
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="font-size: 16px; color: var(--text); margin-bottom: 8px;">Description</h3>
                <p style="color: var(--text); line-height: 1.8;">
                    {{ $product->description ?? 'Aucune description disponible pour ce produit.' }}
                </p>
            </div>

            @if($product->reference_prod)
                <p style="color: var(--muted); font-size: 13px; margin-bottom: 20px;">Référence : {{ $product->reference_prod }}</p>
            @endif

            <div style="margin-bottom: 25px;">
                <p style="color: var(--text);">
                    <strong>Quantité disponible :</strong>
                    {{ $product->qte_dispo > 0 ? $product->qte_dispo . ' unité(s)' : 'Rupture de stock' }}
                </p>
            </div>

            @auth
                @if($product->qte_dispo > 0)
                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="qte" style="font-weight: 600;">Quantité :</label>
                            <input type="number" id="qte" value="1" min="1" max="{{ $product->qte_dispo }}"
                                   style="width: 70px; padding: 8px; border: 2px solid var(--green-light); border-radius: 8px; text-align: center;">
                        </div>
                        <button onclick="addToCart({{ $product->id }})"
                                style="background: var(--green); color: white; padding: 12px 40px; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; font-size: 16px;">
                            🛒 Ajouter au panier
                        </button>
                    </div>
                @else
                    <div style="background: #fde8e8; padding: 15px; border-radius: 8px; text-align: center;">
                        <p style="color: #e74c3c; font-weight: 600; margin: 0;">Ce produit est actuellement en rupture de stock</p>
                    </div>
                @endif
            @else
                <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; text-align: center;">
                    <p style="color: var(--text); margin-bottom: 10px;">🔒 Connectez-vous pour acheter ce produit</p>
                    <a href="{{ route('login') }}" style="background: var(--green); color: white; padding: 10px 30px; border-radius: 50px; text-decoration: none; display: inline-block;">Se connecter</a>
                    <a href="{{ route('register') }}" style="color: var(--green); padding: 10px 30px; border-radius: 50px; text-decoration: none; display: inline-block;">Créer un compte</a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Produits similaires (carte inlinée : pas de partial product-card dans le projet) -->
    @if(isset($similarProducts) && $similarProducts->count() > 0)
        <div style="max-width: 1200px; margin: 60px auto 0;">
            <h2 style="font-size: 24px; color: var(--text); margin-bottom: 20px;">Produits similaires</h2>
            <div class="products-grid" style="grid-template-columns: repeat(4, 1fr); display: grid; gap: 20px;">
                @foreach($similarProducts as $product)
                    <div class="product-card" style="
                        background: var(--white);
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
                        border: 1px solid rgba(0,0,0,0.04);
                        display: flex;
                        flex-direction: column;
                    ">
                        <div style="height: 180px; background: #f8f5f0; overflow: hidden;">
                            @if($product->image_path)
                                <a href="{{ route('client.products.show', $product) }}">
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                         alt="{{ $product->designation }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                            @else
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #b8860b;">
                                    <i class="fas fa-box" style="font-size: 32px;"></i>
                                </div>
                            @endif
                        </div>
                        <div style="padding: 14px; display: flex; flex-direction: column; gap: 6px;">
                            <a href="{{ route('client.products.show', $product) }}" style="color: var(--text); text-decoration: none; font-weight: 700; font-size: 15px;">
                                {{ $product->designation }}
                            </a>
                            <div style="font-size: 16px; font-weight: 800; color: var(--green-dark);">
                                {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
function addToCart(productId) {
    const qteInput = document.getElementById('qte');
    const qte = qteInput ? parseInt(qteInput.value) : 1;
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = 'Ajout en cours...';

    fetch('{{ route('client.cart.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ product_id: productId, qte: qte })
    })
    .then(res => res.json().then(data => ({ status: res.status, body: data })))
    .then(({ status, body }) => {
        if (status === 200 && body.success) {
            btn.innerHTML = '✓ Ajouté !';
            setTimeout(() => { btn.innerHTML = originalText; btn.disabled = false; }, 1500);
        } else {
            alert(body.message || 'Erreur lors de l\'ajout au panier.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(() => {
        alert('Erreur réseau. Réessayez.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>

@endsection