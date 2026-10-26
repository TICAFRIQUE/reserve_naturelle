@extends('layouts.app')

@section('title', 'Checkout - La Réserve Naturelle')

{{-- ⚠️ CACHE LE SLIDER GLOBAL SUR CETTE PAGE --}}
@section('hide_slider', true)
@section('content')

<div class="checkout-wrapper">
    <div class="checkout-container">

        <!-- STEPPER -->
        <div class="checkout-stepper">
            @php
                $steps = ['Panier' => true, 'Confirmation' => false];
            @endphp
            @foreach($steps as $label => $done)
                <div class="step">
                    <div class="step-dot {{ $done ? 'done' : 'pending' }}"></div>
                    <span class="step-label {{ $done ? 'done' : 'pending' }}">{{ $label }}</span>
                </div>
                @if(!$loop->last)
                    <div class="step-line {{ $done ? 'done' : 'pending' }}"></div>
                @endif
            @endforeach
        </div>

        <!-- ERREURS -->
        @if(session('error'))
            <div class="checkout-errors">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="checkout-errors">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Lien de retour au panier --}}
        <div style="margin-bottom: 20px;">
            <a href="{{ route('client.cart.index') }}"
               style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    color: #2d5a27;
                    font-size: 0.9rem;
                    font-weight: 500;
                    text-decoration: none;
                    transition: gap 0.2s;
               "
               onmouseover="this.style.gap='12px'"
               onmouseout="this.style.gap='8px'">
                <i class="fas fa-arrow-left"></i>
                Retour au panier (modifier ou annuler)
            </a>
        </div>

        <!-- FORMULAIRE -->
        <form method="POST" action="{{ route('client.checkout.store', $order) }}" id="checkout-form">
            @csrf

            <div class="checkout-layout">

                <!-- COLONNE GAUCHE -->
                <div class="checkout-main">

                    <!-- Articles -->
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">
                            <span>
                                <i class="fas fa-box"></i>
                                Articles
                            </span>
                            <span class="checkout-count-badge">
                                {{ $order->items->count() }} article(s)
                            </span>
                        </h2>

                        @foreach($order->items as $item)
                            <div class="checkout-item {{ $loop->last ? 'last' : '' }}">
                                <img src="{{ $item->variant->product->image_path ? asset('storage/' . $item->variant->product->image_path) : asset('images/default-product.jpg') }}"
                                     alt="{{ $item->variant->libelle }}">

                                <div class="checkout-item-info">
                                    <div class="checkout-item-name">
                                        {{ $item->variant->libelle }}
                                    </div>
                                    <div class="checkout-item-price">
                                        {{ number_format($item->variant->prix_vente, 0, ',', ' ') }} FCFA / unité
                                    </div>
                                </div>

                                <div class="checkout-item-totals">
                                    <span class="checkout-item-qty">x{{ $item->qte }}</span>
                                    <span class="checkout-item-total">
                                        {{ number_format($item->qte * $item->variant->prix_vente, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Prochaine étape -->
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-check-circle"></i>
                            Prochaine étape
                        </h2>

                        <div class="checkout-info-box">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>Votre commande sera enregistrée après validation.</strong><br>
                                <span>Elle sera ensuite traitée par notre équipe.</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- COLONNE DROITE : RÉSUMÉ -->
                <aside class="checkout-sidebar">
                    <div class="checkout-summary">

                        <h2 class="checkout-card-title">
                            <i class="fas fa-file-invoice"></i>
                            Résumé de la commande
                        </h2>

                        <div class="checkout-summary-box">
                            <div class="summary-line">
                                <span>Sous-total articles</span>
                                <span>{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</span>
                            </div>

                            <div class="summary-total">
                                <span>Total à payer</span>
                                <span>{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-checkout-submit">
                            <i class="fas fa-arrow-right"></i>
                            Valider votre commande
                        </button>
                    </div>
                </aside>

            </div>
        </form>
    </div>
</div>
@endsection