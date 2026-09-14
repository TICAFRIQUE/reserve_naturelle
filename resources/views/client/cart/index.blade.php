@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')

<div class="container cart-wrapper">
    <h1 class="cart-title">🛒 Mon Panier</h1>

    @guest
        <div class="cart-guest-notice">
            ℹ️ Vous naviguez en tant qu'invité.
            <a href="{{ route('login') }}">Connectez-vous</a> pour valider votre commande.
        </div>
    @endguest

    @if($cart->items->count() > 0)

        <!-- ============================================
             TABLEAU (desktop) + CARTES (mobile)
        ============================================ -->
        <div class="cart-card">

            <!-- Vue desktop : tableau -->
            <div class="cart-table-wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart->items as $item)
                            <tr class="cart-row">
                                <td>
                                    <div class="cart-product">
                                        <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                                             alt="{{ $item->product->designation }}">
                                        <div>
                                            <strong>{{ $item->product->designation }}</strong>
                                            <p>{{ $item->product->category->nom ?? 'Non catégorisé' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="cart-cell-center">
                                    {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="cart-cell-center">
                                    <form action="{{ route('client.cart.update', $item->id) }}"
                                          method="POST"
                                          class="update-form cart-qty-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="qte" value="{{ $item->qte }}"
                                               min="1" max="{{ $item->product->qte_dispo }}">
                                        <button type="button" class="btn-update" data-nom="{{ $item->product->designation }}">✓</button>
                                    </form>
                                </td>
                                <td class="cart-cell-center cart-cell-total">
                                    {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="cart-cell-center">
                                    <form action="{{ route('client.cart.destroy', $item->id) }}"
                                          method="POST"
                                          class="delete-form cart-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete" data-nom="{{ $item->product->designation }}">✕</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Vue mobile : cartes -->
            <div class="cart-mobile-list">
                @foreach($cart->items as $item)
                    <div class="cart-mobile-item">
                        <div class="cart-mobile-top">
                            <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                                 alt="{{ $item->product->designation }}">
                            <div class="cart-mobile-info">
                                <strong>{{ $item->product->designation }}</strong>
                                <p>{{ $item->product->category->nom ?? 'Non catégorisé' }}</p>
                                <span class="cart-mobile-unit-price">
                                    {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA / unité
                                </span>
                            </div>
                        </div>

                        <div class="cart-mobile-bottom">
                            <form action="{{ route('client.cart.update', $item->id) }}"
                                  method="POST"
                                  class="update-form cart-qty-form">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="qte" value="{{ $item->qte }}"
                                       min="1" max="{{ $item->product->qte_dispo }}">
                                <button type="button" class="btn-update" data-nom="{{ $item->product->designation }}">✓</button>
                            </form>

                            <div class="cart-mobile-total">
                                <span>Total</span>
                                <strong>{{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA</strong>
                            </div>

                            <form action="{{ route('client.cart.destroy', $item->id) }}"
                                  method="POST"
                                  class="delete-form cart-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" data-nom="{{ $item->product->designation }}">✕</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pied du panier -->
            <div class="cart-footer">
                <div class="cart-footer-total">
                    <strong>Total :</strong>
                    <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="cart-footer-actions">
                    <button type="button" id="btn-clear" class="btn-clear">
                        🗑️ Vider
                    </button>

                    @auth
                        <button type="button" id="btn-order" class="btn-order">
                            📦 Passer commande
                        </button>
                    @else
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn-order">
                            🔒 Se connecter pour commander
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Formulaires cachés --}}
        <form action="{{ route('client.cart.clear') }}" method="POST" id="clear-form" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        @auth
            <form action="{{ route('client.orders.store') }}" method="POST" id="order-form" style="display: none;">
                @csrf
            </form>
        @endauth

    @else
        <div class="cart-empty">
            <div class="cart-empty-icon">🛒</div>
            <h3>Votre panier est vide</h3>
            <p>Commencez vos achats dès maintenant !</p>
            <a href="{{ route('client.products.catalogue') }}" class="btn-primary">
                Voir les produits
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Mise à jour de la quantité =====
    document.querySelectorAll('.btn-update').forEach(function (button) {
        button.addEventListener('click', function () {
            const form = this.closest('.update-form');
            const input = form.querySelector('input[name="qte"]');
            const qte = parseInt(input.value);
            const max = parseInt(input.getAttribute('max'));
            const nom = this.dataset.nom;

            if (!qte || qte < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Quantité invalide',
                    text: 'La quantité doit être supérieure à 0.',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (qte > max) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stock insuffisant',
                    text: 'La quantité demandée (' + qte + ') dépasse le stock disponible (' + max + ').',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
                input.value = max;
                return;
            }

            Swal.fire({
                title: 'Mettre à jour la quantité ?',
                html: '<div style="text-align: left;"><p><strong>Produit :</strong> ' + nom + '</p><p><strong>Nouvelle quantité :</strong> ' + qte + '</p></div>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2e7d32',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '✅ Oui, mettre à jour',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mise à jour en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    form.submit();
                }
            });
        });
    });

    // ===== Suppression d'un article =====
    document.querySelectorAll('.btn-delete').forEach(function (button) {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');
            const nom = this.dataset.nom;

            Swal.fire({
                title: '🗑️ Supprimer cet article ?',
                html: '<div style="text-align: left;"><p style="color: #721c24; font-weight: 500;"><i class="fas fa-exclamation-triangle" style="color: #856404;"></i> Vous êtes sur le point de supprimer :</p><p style="font-weight: 600; color: #2d5a27; background: #f8f5f0; padding: 10px; border-radius: 5px; text-align: center;"><strong>' + nom + '</strong></p></div>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Suppression en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    form.submit();
                }
            });
        });
    });

    // ===== Vider le panier =====
    const btnClear = document.getElementById('btn-clear');
    if (btnClear) {
        btnClear.addEventListener('click', function () {
            Swal.fire({
                title: '🗑️ Vider le panier ?',
                html: '<div style="text-align: left;"><p style="color: #721c24; font-weight: 500;"><i class="fas fa-exclamation-triangle" style="color: #856404;"></i> Tous les articles seront supprimés définitivement.</p></div>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Oui, vider',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Vidage en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById('clear-form').submit();
                }
            });
        });
    }

    // ===== Passer commande =====
    const btnOrder = document.getElementById('btn-order');
    if (btnOrder) {
        btnOrder.addEventListener('click', function () {
            const total = '{{ number_format($total, 0, ",", " ") }}';
            const nbArticles = {{ $cart->items->count() }};

            Swal.fire({
                title: '📦 Passer commande ?',
                html: '<div style="text-align: left;"><p style="color: #155724; font-weight: 500;"><i class="fas fa-exclamation-triangle" style="color: #856404;"></i> Vous êtes sur le point de valider votre commande.</p><div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;"><p style="margin: 5px 0; font-weight: 600; color: #2d5a27;"><i class="fas fa-box"></i> Nombre d\'articles : <strong>' + nbArticles + '</strong></p><p style="margin: 5px 0; font-size: 1.1rem; color: #2d5a27; font-weight: 700;"><i class="fas fa-money-bill-wave"></i> Total : <strong>' + total + ' FCFA</strong></p></div><p style="color: #155724; font-weight: 500; background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 10px;"><i class="fas fa-info-circle"></i> Vous serez redirigé vers la page de livraison pour finaliser votre commande.</p></div>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2e7d32',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '✅ Oui, commander',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                width: '550px'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Création de la commande...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById('order-form').submit();
                }
            });
        });
    }
});
</script>

@endsection