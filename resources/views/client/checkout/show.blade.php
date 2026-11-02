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
                $steps = ['Panier' => true, 'Récupération' => true, 'Confirmation' => false];
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

                    <!-- Mode de récupération -->
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-truck"></i>
                            Récupération de la commande
                        </h2>

                        <div class="form-group-checkout">
                            <label>
                                Comment souhaitez-vous recevoir votre commande ? <span class="required">*</span>
                            </label>
                            <div class="delivery-modes">
                                <label class="delivery-option">
                                    <input type="radio" name="mode_livraison" value="retrait"
                                           class="mode-livraison-radio"
                                           {{ old('mode_livraison', 'retrait') === 'retrait' ? 'checked' : '' }} required>
                                    <span><i class="fas fa-store"></i> Je viens récupérer sur place</span>
                                </label>

                                <label class="delivery-option">
                                    <input type="radio" name="mode_livraison" value="yango"
                                           class="mode-livraison-radio"
                                           {{ old('mode_livraison') === 'yango' ? 'checked' : '' }} required>
                                    <span><i class="fas fa-motorcycle"></i> Livraison Yango (à mes frais)</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group-checkout" id="adresse-wrapper" style="display: none;">
                            <label for="adresse_precise">
                                Adresse précise / repère <span class="required">*</span>
                            </label>
                            <textarea name="adresse_precise" id="adresse_precise" rows="2"
                                      placeholder="Ex: Cocody Angré, 8e tranche, près de la pharmacie">{{ old('adresse_precise') }}</textarea>
                        </div>

                        <div class="checkout-info-box" id="yango-info" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <span>Nous commandons le Yango pour vous. Les frais de course sont à régler directement au chauffeur à la réception.</span>
                            </div>
                        </div>

                        <div class="checkout-info-box" id="retrait-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <span>Retrait chez TICAFRIQUE – Abidjan. Vous serez contacté lorsque votre commande sera prête.</span>
                            </div>
                        </div>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const radios = document.querySelectorAll('.mode-livraison-radio');
    const adresseWrapper = document.getElementById('adresse-wrapper');
    const adresse = document.getElementById('adresse_precise');
    const yangoInfo = document.getElementById('yango-info');
    const retraitInfo = document.getElementById('retrait-info');

    function update() {
        const yango = document.querySelector('.mode-livraison-radio:checked')?.value === 'yango';
        adresseWrapper.style.display = yango ? 'block' : 'none';
        yangoInfo.style.display = yango ? 'flex' : 'none';
        retraitInfo.style.display = yango ? 'none' : 'flex';
        adresse.required = yango;
    }

    radios.forEach(r => r.addEventListener('change', update));
    update();
});
</script>

@endsection