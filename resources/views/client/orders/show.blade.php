@extends('layouts.app')

@section('title', 'Commande ' . $order->num_order . ' - La Réserve Naturelle')

@section('content')

@php
    $statutLabels = [
        'en_attente' => ['label' => 'En attente', 'class' => 'status-pending'],
        'payee'      => ['label' => 'Payée',      'class' => 'status-paid'],
        'validee'    => ['label' => 'Validée',    'class' => 'status-validated'],
        'livree'     => ['label' => 'Livrée',     'class' => 'status-delivered'],
        'annulee'    => ['label' => 'Annulée',    'class' => 'status-cancelled'],
    ];
    $statut = $statutLabels[$order->statut]
        ?? ['label' => $order->statut, 'class' => 'status-default'];
@endphp

<div class="container order-show-wrapper">

    <!-- ============================================
         MESSAGES FLASH
    ============================================ -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Retour -->
    <a href="{{ route('client.orders.index') }}" class="order-show-back">
        ← Retour aux commandes
    </a>

    <!-- ============================================
         EN-TÊTE
    ============================================ -->
    <div class="order-show-header">
        <div class="order-show-header-left">
            <h1>{{ $order->num_order }}</h1>
            <p>Passée le {{ $order->date_order->format('d/m/Y') }}</p>
        </div>
        <span class="order-status {{ $statut['class'] }}">
            {{ $statut['label'] }}
        </span>
    </div>

    <!-- ============================================
         LISTE DES ARTICLES
    ============================================ -->
    <div class="order-show-items">
        @foreach($order->items as $item)
            <div class="order-show-item">
                <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                     alt="{{ $item->product->designation }}">

                <div class="order-show-item-info">
                    <h3>{{ $item->product->designation }}</h3>
                    <p>
                        {{ $item->qte }} × {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <p class="order-show-item-total">
                    {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                </p>
            </div>
        @endforeach
    </div>

    <!-- ============================================
         TOTAL
    ============================================ -->
    <div class="order-show-total">
        <span>Total</span>
        <span class="order-show-total-value">
            {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
        </span>
    </div>

    <!-- ============================================
         ACTIONS SELON STATUT
    ============================================ -->
    @if($order->statut === 'en_attente')
        <div class="order-show-action">
            <a href="{{ route('client.checkout.show', $order) }}" class="btn-primary-lg">
                Valider le panier
            </a>
        </div>
    @elseif($order->statut === 'payee')
        <p class="order-show-note">
            Paiement reçu, commande en attente de validation par l'administration.
        </p>
    @endif

</div>

@endsection