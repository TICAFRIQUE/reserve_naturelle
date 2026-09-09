{{-- resources/views/client/orders/payment.blade.php --}}
@extends('layouts.app')

@section('title', 'Confirmation - La Réserve Naturelle')

@section('content')

<div style="
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 50%, #a5d6a7 100%);
    min-height: 100vh;
    padding: 60px 20px;
    display: flex;
    justify-content: center;
    align-items: center;
">
    <div style="
        background: white;
        max-width: 600px;
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(11, 122, 72, 0.15);
        overflow: hidden;
        border: 1px solid rgba(11, 122, 72, 0.08);
    ">
        <!-- En-tête -->
        <div style="
            background: linear-gradient(135deg, #0B7A48 0%, #055936 100%);
            padding: 30px 30px 25px;
            text-align: center;
            position: relative;
        ">
            <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
            <h1 style="
                font-family: 'Playfair Display', serif;
                color: white;
                font-size: 28px;
                margin: 0;
            ">
                Confirmation de commande
            </h1>
            <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 5px 0 0;">
                Commande #{{ $order->id }}
            </p>
            <div style="
                position: absolute;
                top: -30px;
                right: -30px;
                font-size: 100px;
                opacity: 0.08;
                pointer-events: none;
            ">
                ✅
            </div>
        </div>

        <!-- Corps -->
        <div style="padding: 30px 30px 35px;">
            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div style="
                    background: #fef2f2;
                    border: 1px solid #fecaca;
                    border-left: 4px solid #dc3545;
                    padding: 14px 18px;
                    border-radius: 10px;
                    margin-bottom: 25px;
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                ">
                    <i class="fas fa-exclamation-circle" style="color: #dc3545; margin-top: 2px;"></i>
                    <div>
                        <strong style="color: #991b1b;">Erreur</strong>
                        <p style="color: #991b1b; margin: 4px 0 0; font-size: 14px;">
                            {{ $errors->first() }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Récapitulatif -->
            <div style="
                background: #faf8f5;
                border-radius: 12px;
                padding: 20px 24px;
                margin-bottom: 25px;
                border: 1px solid #e8e0d5;
            ">
                <h2 style="
                    font-size: 16px;
                    color: #2d5a27;
                    font-weight: 700;
                    margin: 0 0 15px 0;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                ">
                    <i class="fas fa-shopping-bag" style="color: #b8860b;"></i>
                    Récapitulatif de la commande
                </h2>

                <!-- Articles -->
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($order->items as $item)
                        <li style="
                            display: flex;
                            justify-content: space-between;
                            padding: 10px 0;
                            border-bottom: 1px solid #e8e0d5;
                            font-size: 14px;
                        ">
                            <span style="color: #2d5a27;">
                                {{ $item->product->designation ?? $item->product->nom ?? 'Produit' }}
                                <span style="color: #6c757d; font-size: 13px;">
                                    x {{ $item->qte }}
                                </span>
                            </span>
                            <span style="font-weight: 600; color: #2d5a27;">
                                {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                            </span>
                        </li>
                    @endforeach
                </ul>

                <!-- Sous-total -->
                <div style="
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 0;
                    border-bottom: 1px solid #e8e0d5;
                ">
                    <span style="color: #6c757d; font-size: 14px;">Sous-total</span>
                    <span style="font-weight: 500; color: #2d5a27;">
                        {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
                    </span>
                </div>

                <!-- Frais de livraison -->
                @if($order->tarif_livraison)
                <div style="
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 0;
                    border-bottom: 1px solid #e8e0d5;
                ">
                    <span style="color: #6c757d; font-size: 14px;">Frais de livraison</span>
                    <span style="font-weight: 500; color: #2d5a27;">
                        {{ number_format($order->tarif_livraison, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                @endif

                <!-- Total -->
                <div style="
                    display: flex;
                    justify-content: space-between;
                    padding: 15px 0 0;
                    margin-top: 5px;
                    border-top: 2px solid #e8e0d5;
                ">
                    <span style="font-weight: 700; color: #2d5a27; font-size: 16px;">Total</span>
                    <span style="
                        font-weight: 800;
                        color: #2d5a27;
                        font-size: 20px;
                    ">
                        {{ number_format($order->montant_ttc ?? $order->mt_total, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>

         <!-- Adresse de livraison -->
        <div style="
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        ">
            <i class="fas fa-map-marker-alt" style="color: #2d5a27; font-size: 18px; margin-top: 2px;"></i>
            <div style="font-size: 0.9rem; color: #2d5a27;">
                @if($order->zone)
                    <strong>Zone :</strong> {{ $order->zone->nom }}<br>
                @endif
                <strong>Livraison :</strong> {{ $order->adresse_precise }}
                @if($order->ville_expedition)
                    <br><span style="color: #4a7040;">{{ $order->ville_expedition }}</span>
                @endif
            </div>
        </div>

            <!-- Formulaire de confirmation -->
            <form method="POST" action="{{ route('client.orders.confirm', $order) }}" id="confirm-form">
                @csrf

                <button type="submit"
                        id="confirm-btn"
                        style="
                            width: 100%;
                            padding: 16px;
                            background: linear-gradient(135deg, #0B7A48 0%, #055936 100%);
                            color: white;
                            border: none;
                            border-radius: 50px;
                            font-size: 18px;
                            font-weight: 700;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 10px;
                            box-shadow: 0 4px 15px rgba(11, 122, 72, 0.3);
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(11, 122, 72, 0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(11, 122, 72, 0.3)'">
                    <i class="fas fa-check-circle"></i>
                    Valider ma commande
                </button>
            </form>

            <!-- Lien retour -->
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('client.checkout.show', $order) }}" style="
                    color: #6c757d;
                    text-decoration: none;
                    font-size: 14px;
                    transition: color 0.3s;
                " onmouseover="this.style.color='#2d5a27'" onmouseout="this.style.color='#6c757d'">
                    <i class="fas fa-arrow-left"></i> Modifier les informations de livraison
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('confirm-form');
        const btn = document.getElementById('confirm-btn');

        form.addEventListener('submit', function() {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection