{{-- resources/views/client/checkout/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Checkout - La Réserve Naturelle')

@section('content')
<div style="
    background: #f8f5f0;
    min-height: 100vh;
    padding: 40px 20px;
">
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Stepper -->
        <div style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-bottom: 35px;">
            @php
                $steps = ['Panier' => true, 'Livraison' => true, 'Confirmation' => false];
            @endphp
            @foreach($steps as $label => $done)
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="
                        width: 14px; height: 14px; border-radius: 50%;
                        background: {{ $done ? '#2d5a27' : 'white' }};
                        border: 2px solid #2d5a27;
                    "></div>
                    <span style="font-size: 0.85rem; color: {{ $done ? '#2d5a27' : '#9ca3af' }}; font-weight: 600;">{{ $label }}</span>
                </div>
                @if(!$loop->last)
                    <div style="width: 40px; height: 2px; background: #2d5a27; opacity: {{ $done ? '1' : '0.3' }};"></div>
                @endif
            @endforeach
        </div>

        @if($errors->any())
            <div style="
                background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 8px;
                border-left: 4px solid #dc3545; margin-bottom: 20px;
                display: flex; align-items: flex-start; gap: 12px; max-width: 800px; margin-left: auto; margin-right: auto;
            ">
                <i class="fas fa-exclamation-circle" style="font-size: 20px; margin-top: 2px;"></i>
                <div>
                    <strong style="display: block; margin-bottom: 4px;">Veuillez corriger les erreurs suivantes :</strong>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('client.checkout.store', $order) }}" id="checkout-form">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 30px;">
                <!-- Colonne gauche -->
                <div>
                    <!-- Items -->
                    <div style="
                        background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px;
                        border: 1px solid #e8e0d5; box-shadow: 0 2px 10px rgba(0,0,0,0.06);
                    ">
                        <h2 style="
                            font-size: 16px; color: #2d5a27; font-weight: 700; margin: 0 0 15px 0;
                            display: flex; align-items: center; justify-content: space-between;
                        ">
                            <span>
                                <i class="fas fa-box" style="color: #b8860b; margin-right: 8px;"></i>
                                Articles
                            </span>
                            <span style="background: #e8f5e9; color: #2d5a27; padding: 2px 12px; border-radius: 20px; font-size: 0.8rem;">
                                {{ $order->items->count() }} article(s)
                            </span>
                        </h2>

                        @foreach($order->items as $item)
                            <div style="
                                display: flex; align-items: center; gap: 15px; padding: 12px 0;
                                border-bottom: {{ !$loop->last ? '1px solid #f0ebe5' : 'none' }};
                            ">
                                <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; flex-shrink: 0;">

                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #2d5a27;">
                                        {{ $item->product->designation }}
                                    </div>
                                    <div style="font-size: 0.85rem; color: #6c757d; margin-top: 2px;">
                                        {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA / unité
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span style="color: #6c757d; font-size: 0.9rem;">x{{ $item->qte }}</span>
                                    <span style="font-weight: 700; color: #2d5a27; min-width: 100px; text-align: right;">
                                        {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Shipping -->
                    <div style="
                        background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px;
                        border: 1px solid #e8e0d5; box-shadow: 0 2px 10px rgba(0,0,0,0.06);
                    ">
                        <h2 style="font-size: 16px; color: #2d5a27; font-weight: 700; margin: 0 0 15px 0;">
                            <i class="fas fa-truck" style="color: #b8860b; margin-right: 8px;"></i>
                            Livraison
                        </h2>

                        <!-- Choix du mode de livraison -->
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 8px;">
                                Mode de livraison <span style="color: #dc3545;">*</span>
                            </label>
                            <div style="display: flex; gap: 15px;">
                                <label style="
                                    flex: 1; display: flex; align-items: center; gap: 10px; padding: 12px 15px;
                                    border: 2px solid #e8e0d5; border-radius: 8px; cursor: pointer;
                                ">
                                    <input type="radio" name="mode_livraison" value="domicile" class="mode-livraison-radio"
                                           {{ old('mode_livraison', 'domicile') == 'domicile' ? 'checked' : '' }} required
                                           style="width: 18px; height: 18px; accent-color: #2d5a27;">
                                    <span style="font-size: 0.9rem; color: #2d5a27; font-weight: 500;">
                                        <i class="fas fa-home"></i> Livraison à domicile
                                    </span>
                                </label>
                                <label style="
                                    flex: 1; display: flex; align-items: center; gap: 10px; padding: 12px 15px;
                                    border: 2px solid #e8e0d5; border-radius: 8px; cursor: pointer;
                                ">
                                    <input type="radio" name="mode_livraison" value="expedition" class="mode-livraison-radio"
                                           {{ old('mode_livraison') == 'expedition' ? 'checked' : '' }} required
                                           style="width: 18px; height: 18px; accent-color: #2d5a27;">
                                    <span style="font-size: 0.9rem; color: #2d5a27; font-weight: 500;">
                                        <i class="fas fa-shipping-fast"></i> Expédition
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div id="zone-wrapper" style="margin-bottom: 15px;">
                            <label for="zone_id" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                                Zone <span style="color: #dc3545;">*</span>
                            </label>
                            <select name="zone_id" id="zone_id" style="
                                width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px;
                                font-size: 0.95rem; background: white; outline: none; cursor: pointer;
                            " onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
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

                        <div id="ville-expedition-wrapper" style="margin-bottom: 15px; display: none;">
                            <label for="ville_expedition" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                                Ville (expédition) <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="text" name="ville_expedition" id="ville_expedition" value="{{ old('ville_expedition') }}"
                                   style="width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem; outline: none;"
                                   onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'"
                                   placeholder="Ex: Bouaké">
                        </div>

                        <div>
                            <label for="adresse_precise" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                                Adresse précise / repère <span style="color: #dc3545;">*</span>
                            </label>
                            <textarea name="adresse_precise" id="adresse_precise" rows="2" required
                                      style="width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem; outline: none; resize: vertical;"
                                      onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'"
                                      placeholder="Ex: Rue des jardins, non loin de la pharmacie">{{ old('adresse_precise') }}</textarea>
                        </div>
                    </div>

                    <!-- Confirmation (informatif) -->
                    <div style="
                        background: white; border-radius: 12px; padding: 25px;
                        border: 1px solid #e8e0d5; box-shadow: 0 2px 10px rgba(0,0,0,0.06);
                    ">
                        <h2 style="font-size: 16px; color: #2d5a27; font-weight: 700; margin: 0 0 15px 0;">
                            <i class="fas fa-check-circle" style="color: #b8860b; margin-right: 8px;"></i>
                            Prochaine étape
                        </h2>

                        <div style="
                            display: flex; align-items: flex-start; gap: 12px;
                            background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 10px; padding: 16px 18px;
                        ">
                            <i class="fas fa-info-circle" style="color: #2d5a27; font-size: 18px; margin-top: 2px;"></i>
                            <div style="font-size: 0.9rem; color: #2d5a27;">
                                <strong>Vous serez redirigé vers un récapitulatif de votre commande.</strong><br>
                                <span style="color: #4a7040;">Les frais de livraison sont à régler en espèces directement au livreur, à la réception de votre commande.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Résumé -->
                <div>
                    <div style="
                        background: white; border-radius: 12px; padding: 25px;
                        border: 1px solid #e8e0d5; box-shadow: 0 2px 10px rgba(0,0,0,0.06);
                        position: sticky; top: 20px;
                    ">
                        <h2 style="font-size: 16px; color: #2d5a27; font-weight: 700; margin: 0 0 15px 0;">
                            <i class="fas fa-file-invoice" style="color: #b8860b; margin-right: 8px;"></i>
                            Résumé de la commande
                        </h2>

                        <div style="background: #f8f5f0; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: #6c757d; margin-bottom: 8px;">
                                <span>Sous-total articles</span>
                                <span>{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: #6c757d;">
                                <span>Frais de livraison</span>
                                <span id="tarif-display">—</span>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; padding-top: 14px; border-top: 1px solid #e8e0d5;">
                                <span style="font-weight: 600; color: #2d5a27;">Total articles</span>
                                <span style="font-family: 'Playfair Display', serif; font-weight: 800; color: #2d5a27; font-size: 1.5rem;">
                                    {{ number_format($sousTotal, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>

                        <button type="submit" style="
                            width: 100%; padding: 16px;
                            background: linear-gradient(135deg, #0B7A48 0%, #055936 100%);
                            color: white; border: none; border-radius: 50px;
                            font-size: 1.05rem; font-weight: 700; cursor: pointer;
                            display: flex; align-items: center; justify-content: center; gap: 10px;
                            box-shadow: 0 4px 15px rgba(11, 122, 72, 0.3);
                        "
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-arrow-right"></i>
                            Continuer vers la confirmation
                        </button>

                        <div style="
                            margin-top: 15px; padding: 15px; background: #f8f5f0; border-radius: 8px;
                            font-size: 0.85rem; color: #6c757d;
                        ">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                                <i class="fas fa-truck" style="color: #b8860b;"></i>
                                <span>Livraison estimée : 2-3 jours ouvrables</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-money-bill-wave" style="color: #b8860b;"></i>
                                <span>Frais de livraison payés en espèces à réception</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const zoneSelect = document.getElementById('zone_id');
        const villeWrapper = document.getElementById('ville-expedition-wrapper');
        const villeInput = document.getElementById('ville_expedition');
        const tarifDisplay = document.getElementById('tarif-display');
        const modeLivraisonRadios = document.querySelectorAll('.mode-livraison-radio');

        const zoneWrapper = document.getElementById('zone-wrapper');

        function updateFieldsVisibility() {
            const selectedMode = document.querySelector('.mode-livraison-radio:checked')?.value;
            const isExpedition = selectedMode === 'expedition';

            // Zone : visible/requise uniquement en mode domicile
            zoneWrapper.style.display = isExpedition ? 'none' : 'block';
            zoneSelect.required = !isExpedition;
            if (isExpedition) {
                zoneSelect.value = '';
                tarifDisplay.textContent = '—';
            }

            // Ville expédition : visible/requise uniquement en mode expédition
            villeWrapper.style.display = isExpedition ? 'block' : 'none';
            villeInput.required = isExpedition;
            if (!isExpedition) villeInput.value = '';
        }

        function updateTarifDisplay() {
            const selected = zoneSelect.options[zoneSelect.selectedIndex];
            const tarif = parseInt(selected?.dataset.tarif) || 0;

            tarifDisplay.textContent = tarif > 0
                ? new Intl.NumberFormat('fr-FR').format(tarif) + ' FCFA'
                : '—';
        }

        modeLivraisonRadios.forEach(radio => {
            radio.addEventListener('change', updateFieldsVisibility);
        });

        zoneSelect.addEventListener('change', updateTarifDisplay);

        // État initial (utile si old() a pré-rempli le formulaire après une erreur de validation)
        updateFieldsVisibility();
        updateTarifDisplay();
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    form { animation: fadeIn 0.5s ease; }

    @media (max-width: 992px) {
        form > div { grid-template-columns: 1fr !important; }
        form > div > div:last-child > div:first-child { position: static !important; }
        .container { padding: 20px 15px !important; }
    }
</style>
@endsection