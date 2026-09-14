@extends('layouts.app')

@section('title', 'Confirmation - La Réserve Naturelle')

@section('content')

<div class="payment-wrapper">
    <div class="payment-card">

        <!-- ============================================
             EN-TÊTE
        ============================================ -->
        <div class="payment-header">
            <div class="payment-header-icon">✅</div>
            <h1>Confirmation de commande</h1>
            <p class="payment-header-sub">Commande #{{ $order->id }}</p>
            <div class="payment-header-bg">✅</div>
        </div>

        <!-- ============================================
             CORPS
        ============================================ -->
        <div class="payment-body">

            <!-- Erreurs -->
            @if ($errors->any())
                <div class="payment-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Erreur</strong>
                        <p>{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <!-- Récapitulatif -->
            <div class="payment-summary">
                <h2 class="payment-summary-title">
                    <i class="fas fa-shopping-bag"></i>
                    Récapitulatif de la commande
                </h2>

                <!-- Articles -->
                <ul class="payment-items">
                    @foreach ($order->items as $item)
                        <li class="payment-item">
                            <span class="payment-item-name">
                                {{ $item->product->designation ?? $item->product->nom ?? 'Produit' }}
                                <span class="payment-item-qty">x {{ $item->qte }}</span>
                            </span>
                            <span class="payment-item-price">
                                {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                            </span>
                        </li>
                    @endforeach
                </ul>

                <!-- Sous-total -->
                <div class="payment-line">
                    <span>Sous-total</span>
                    <span>{{ number_format($order->mt_total, 0, ',', ' ') }} FCFA</span>
                </div>

                <!-- Frais de livraison -->
                @if($order->tarif_livraison)
                    <div class="payment-line">
                        <span>Frais de livraison</span>
                        <span>{{ number_format($order->tarif_livraison, 0, ',', ' ') }} FCFA</span>
                    </div>
                @endif

                <!-- Total -->
                <div class="payment-line payment-line-total">
                    <span>Total</span>
                    <span class="payment-total-value">
                        {{ number_format($order->montant_ttc ?? $order->mt_total, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>

            <!-- Adresse de livraison -->
            <div class="payment-address">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    @if($order->zone)
                        <strong>Zone :</strong> {{ $order->zone->nom }}<br>
                    @endif
                    <strong>Livraison :</strong> {{ $order->adresse_precise }}
                    @if($order->ville_expedition)
                        <br><span class="payment-address-city">{{ $order->ville_expedition }}</span>
                    @endif
                </div>
            </div>

            <!-- Formulaire de confirmation -->
            <form method="POST" action="{{ route('client.orders.confirm', $order) }}" id="confirm-form">
                @csrf
                <button type="submit" id="confirm-btn" class="btn-payment-confirm">
                    <i class="fas fa-check-circle"></i>
                    Valider ma commande
                </button>
            </form>

            <!-- Lien retour -->
            <div class="payment-back">
                <a href="{{ route('client.checkout.show', $order) }}">
                    <i class="fas fa-arrow-left"></i> Modifier les informations de livraison
                </a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('confirm-form');
    const btn = document.getElementById('confirm-btn');

    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
    });
});
</script>

@endsection