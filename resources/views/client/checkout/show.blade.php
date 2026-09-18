@extends('layouts.app')

@section('title', 'Checkout - La Réserve Naturelle')

@section('content')

<div class="checkout-wrapper">
    <div class="checkout-container">

        <!-- ============================================
             STEPPER
        ============================================ -->
        <div class="checkout-stepper">
            @php
                $steps = ['Panier' => true, 'Livraison' => true, 'Confirmation' => false];
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

        <!-- ============================================
             ERREURS
        ============================================ -->
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

        <!-- ============================================
             FORMULAIRE
        ============================================ -->
        <form method="POST" action="{{ route('client.checkout.store', $order) }}" id="checkout-form">
            @csrf

            <div class="checkout-layout">

                <!-- ========================================
                     COLONNE GAUCHE
                ======================================== -->
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
                                <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                                     alt="{{ $item->product->designation }}">

                                <div class="checkout-item-info">
                                    <div class="checkout-item-name">
                                        {{ $item->product->designation }}
                                    </div>
                                    <div class="checkout-item-price">
                                        {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA / unité
                                    </div>
                                </div>

                                <div class="checkout-item-totals">
                                    <span class="checkout-item-qty">x{{ $item->qte }}</span>
                                    <span class="checkout-item-total">
                                        {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Livraison -->
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-truck"></i>
                            Livraison
                        </h2>

                        <!-- Mode de livraison -->
                        <div class="form-group-checkout">
                            <label>
                                Mode de livraison <span class="required">*</span>
                            </label>
                            <div class="delivery-modes">
                                <label class="delivery-option">
                                    <input type="radio" name="mode_livraison" value="domicile"
                                           class="mode-livraison-radio"
                                           {{ old('mode_livraison', 'domicile') == 'domicile' ? 'checked' : '' }}
                                           required>
                                    <span><i class="fas fa-home"></i> Livraison à domicile</span>
                                </label>

                                <label class="delivery-option">
                                    <input type="radio" name="mode_livraison" value="expedition"
                                           class="mode-livraison-radio"
                                           {{ old('mode_livraison') == 'expedition' ? 'checked' : '' }}
                                           required>
                                    <span><i class="fas fa-shipping-fast"></i> Expédition</span>
                                </label>
                            </div>
                        </div>

                        <!-- Zone -->
                        <div class="form-group-checkout" id="zone-wrapper">
                            <label for="zone_id">
                                Zone <span class="required">*</span>
                            </label>
                            <select name="zone_id" id="zone_id">
                                <option value="">-- Sélectionnez --</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}"
                                            data-tarif="{{ $zone->tarif }}"
                                            {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->nom }} — {{ number_format($zone->tarif, 0, ',', ' ') }} FCFA
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ville expédition -->
                        <div class="form-group-checkout" id="ville-expedition-wrapper" style="display: none;">
                            <label for="ville_expedition">
                                Ville (expédition) <span class="required">*</span>
                            </label>
                            <input type="text" name="ville_expedition" id="ville_expedition"
                                   value="{{ old('ville_expedition') }}"
                                   placeholder="Ex: Bouaké">
                        </div>

                        <!-- Adresse précise -->
                        <div class="form-group-checkout">
                            <label for="adresse_precise">
                                Adresse précise / repère <span class="required">*</span>
                            </label>
                            <textarea name="adresse_precise" id="adresse_precise" rows="2" required
                                      placeholder="Ex: Rue des jardins, non loin de la pharmacie">{{ old('adresse_precise') }}</textarea>
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
                                <strong>Vous serez redirigé vers un récapitulatif de votre commande.</strong><br>
                                <span>Les frais de livraison sont à régler en espèces directement au livreur, à la réception de votre commande.</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ========================================
                     COLONNE DROITE : RÉSUMÉ
                ======================================== -->
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
                            <div class="summary-line">
                                <span>Frais de livraison</span>
                                <span id="tarif-display">—</span>
                            </div>

                            <div class="summary-total">
                                <span>Total à payer</span>
                                <span id="total-general">
                                    {{ number_format($sousTotal, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn-checkout-submit">
                            <i class="fas fa-arrow-right"></i>
                            Valider votre commande
                        </button>

                        <div class="checkout-delivery-info">
                            <div>
                                <i class="fas fa-truck"></i>
                                <span>Livraison estimée : 1-2 jours ouvrables</span>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const zoneSelect = document.getElementById('zone_id');
    const villeWrapper = document.getElementById('ville-expedition-wrapper');
    const villeInput = document.getElementById('ville_expedition');
    const tarifDisplay = document.getElementById('tarif-display');
    const totalGeneral = document.getElementById('total-general');
    const modeLivraisonRadios = document.querySelectorAll('.mode-livraison-radio');
    const zoneWrapper = document.getElementById('zone-wrapper');

    const sousTotal = {{ $sousTotal }};
    const tarifExpedition = {{ $zoneExpedition ? $zoneExpedition->tarif : 0 }};

    function updateFieldsVisibility() {
        const selectedMode = document.querySelector('.mode-livraison-radio:checked')?.value;
        const isExpedition = selectedMode === 'expedition';

        zoneWrapper.style.display = isExpedition ? 'none' : 'block';
        zoneSelect.required = !isExpedition;

        if (isExpedition) {
            zoneSelect.value = '';
            updateTotals(tarifExpedition);
        } else {
            updateTotalsFromZone();
        }

        villeWrapper.style.display = isExpedition ? 'block' : 'none';
        villeInput.required = isExpedition;
        if (!isExpedition) villeInput.value = '';
    }

    function updateTotalsFromZone() {
        const selected = zoneSelect.options[zoneSelect.selectedIndex];
        const tarif = parseInt(selected?.dataset.tarif) || 0;
        updateTotals(tarif);
    }

    function updateTotals(tarif) {
        tarifDisplay.textContent = tarif > 0
            ? new Intl.NumberFormat('fr-FR').format(tarif) + ' FCFA'
            : '—';

        const total = sousTotal + tarif;
        totalGeneral.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
    }

    modeLivraisonRadios.forEach(radio => {
        radio.addEventListener('change', updateFieldsVisibility);
    });

    zoneSelect.addEventListener('change', updateTotalsFromZone);

    updateFieldsVisibility();

    if (zoneSelect.value) {
        updateTotalsFromZone();
    }
});
</script>

@endsection