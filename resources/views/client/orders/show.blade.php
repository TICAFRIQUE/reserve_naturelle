{{-- resources/views/client/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Commande ' . $order->num_order . ' - La Réserve Naturelle')

@section('content')

@php
    $statutLabels = [
        'en_attente' => ['label' => 'En attente', 'color' => '#e65100', 'bg' => '#fff3e0'],
        'validee'    => ['label' => 'Validée', 'color' => '#1565c0', 'bg' => '#e3f2fd'],
        'livree'     => ['label' => 'Livrée', 'color' => '#2e7d32', 'bg' => '#e8f5e9'],
        'annulee'    => ['label' => 'Annulée', 'color' => '#c62828', 'bg' => '#ffebee'],
    ];
    $statut = $statutLabels[$order->statut] ?? ['label' => $order->statut, 'color' => '#666', 'bg' => '#eee'];
@endphp

<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px 60px;">
    <a href="{{ route('client.orders.index') }}" style="color: var(--muted); text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 20px;">
        ← Retour aux commandes
    </a>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 28px; color: var(--text); margin-bottom: 6px;">{{ $order->num_order }}</h1>
            <p style="color: var(--muted); font-size: 14px;">Passée le {{ $order->date_order->format('d/m/Y') }}</p>
        </div>
        <span style="background: {{ $statut['bg'] }}; color: {{ $statut['color'] }}; padding: 8px 20px; border-radius: 20px; font-size: 14px; font-weight: 600;">
            {{ $statut['label'] }}
        </span>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 25px;">
        @foreach($order->items as $item)
            <div style="display: flex; align-items: center; gap: 20px; padding: 18px 20px; border-bottom: 1px solid #f0f0f0;">
                <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">

                <div style="flex: 1;">
                    <h3 style="font-size: 15px; color: var(--text); margin-bottom: 4px;">{{ $item->product->designation }}</h3>
                    <p style="font-size: 13px; color: var(--muted);">
                        {{ $item->qte }} × {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <p style="font-weight: 700; color: var(--text);">
                    {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                </p>
            </div>
        @endforeach
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 25px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 16px; color: var(--text); font-weight: 600;">Total</span>
        <span style="font-size: 24px; font-weight: 700; color: var(--green-dark);">
            {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
        </span>
    </div>

    @if($order->statut === 'en_attente')
        <p style="text-align: center; color: var(--muted); font-size: 13px; margin-top: 20px;">
            Votre commande est en cours de traitement. Contactez-nous pour toute question.
        </p>
    @endif
</div>

@endsection