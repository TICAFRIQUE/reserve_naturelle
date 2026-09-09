@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <h1 style="font-size: 32px; margin-bottom: 30px;">🛒 Mon Panier</h1>

    @guest
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            ℹ️ Vous naviguez en tant qu'invité. <a href="{{ route('login') }}" style="color: #856404; font-weight: 600;">Connectez-vous</a> pour valider votre commande.
        </div>
    @endguest

    @if($cart->items->count() > 0)
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 15px; text-align: left; font-weight: 600;">Produit</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600;">Prix</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600;">Quantité</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600;">Total</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart->items as $item)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px;">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                                             alt="{{ $item->product->designation }}"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        <div>
                                            <strong>{{ $item->product->designation }}</strong>
                                            <p style="font-size: 12px; color: #6c757d; margin: 0;">
                                                {{ $item->product->category->nom ?? 'Non catégorisé' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <form action="{{ route('client.cart.update', $item->id) }}" method="POST" style="display: flex; justify-content: center; gap: 5px; align-items: center;" class="update-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="qte" value="{{ $item->qte }}" min="1" max="{{ $item->product->qte_dispo }}"
                                               style="width: 60px; padding: 5px; border: 2px solid #e0e0e0; border-radius: 6px; text-align: center;">
                                        <button type="button" class="btn-update" data-nom="{{ $item->product->designation }}" style="background: #2e7d32; color: white; padding: 5px 10px; border: none; border-radius: 6px; cursor: pointer;">✓</button>
                                    </form>
                                </td>
                                <td style="padding: 15px; text-align: center; font-weight: 700; color: #1b5e20;">
                                    {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <form action="{{ route('client.cart.destroy', $item->id) }}" method="POST" style="display: inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete" data-nom="{{ $item->product->designation }}" style="background: #dc3545; color: white; padding: 5px 12px; border: none; border-radius: 6px; cursor: pointer;">✕</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <strong style="font-size: 18px;">Total :</strong>
                    <span style="font-size: 24px; font-weight: 700; color: #1b5e20; margin-left: 15px;">
                        {{ number_format($total, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <button type="button" id="btn-clear" style="background: #dc3545; color: white; padding: 10px 25px; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        🗑️ Vider
                    </button>

                    @auth
                        <button type="button" id="btn-order" style="background: #2e7d32; color: white; padding: 10px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                            📦 Passer commande
                        </button>
                    @else
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}"
                           style="background: #2e7d32; color: white; padding: 10px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center;">
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
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
            <div style="font-size: 64px; margin-bottom: 20px;">🛒</div>
            <h3 style="color: #333; font-size: 24px; margin-bottom: 10px;">Votre panier est vide</h3>
            <p style="color: #6c757d; margin-bottom: 20px;">Commencez vos achats dès maintenant !</p>
            <a href="{{ route('client.products.catalogue') }}" style="background: #2e7d32; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; display: inline-block;">
                Voir les produits
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour la mise à jour de la quantité
    document.querySelectorAll('.btn-update').forEach(function(button) {
        button.addEventListener('click', function() {
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
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        });
    });

    // Gestionnaire pour la suppression d'un article
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function() {
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
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        });
    });

    // Gestionnaire pour vider le panier
    const btnClear = document.getElementById('btn-clear');
    if (btnClear) {
        btnClear.addEventListener('click', function() {
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
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('clear-form').submit();
                }
            });
        });
    }

    // Gestionnaire pour passer commande
    const btnOrder = document.getElementById('btn-order');
    if (btnOrder) {
        btnOrder.addEventListener('click', function() {
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
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('order-form').submit();
                }
            });
        });
    }
});
</script>
@endsection