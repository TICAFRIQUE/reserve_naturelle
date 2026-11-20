@extends('layouts.app')

@section('title', 'Réservation ' . $reservation->num_reservation)
@section('hide_slider', true)
@section('content')

@php
    $labels = [
        'validee' => ['Validée', 'status-validated'],
        'payee'   => ['Payée',   'status-paid'],
        'livree'  => ['Livrée',  'status-delivered'],
        'annulee' => ['Annulée', 'status-cancelled'],
    ];
    [$label, $class] = $labels[$reservation->statut] ?? [$reservation->statut, 'status-default'];
    $v = $reservation->variant;
@endphp

<div class="container orders-wrapper" style="max-width:760px;">
    <a href="{{ route('client.reservations.index') }}" style="color:#2d5a27; font-weight:500; text-decoration:none;">
        <i class="fas fa-arrow-left"></i> Mes réservations
    </a>

    <h1 class="orders-title" style="margin-top:15px;">Réservation {{ $reservation->num_reservation }}</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-error">{{ session('error') }}</div> @endif

    <div class="orders-card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
            <div>
                <div style="color:#6c757d; font-size:0.85rem;">Date</div>
                <strong>{{ $reservation->date_reservation->format('d/m/Y') }}</strong>
            </div>
            <div>
                <div style="color:#6c757d; font-size:0.85rem;">Statut</div>
                <span class="order-status {{ $class }}">{{ $label }}</span>
            </div>
        </div>

        <table class="orders-table" style="width:100%;">
            <thead>
                <tr><th>Produit</th><th>Qté</th><th>Prix unitaire</th><th>Total</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $v?->libelle ?? 'Produit supprimé' }}</td>
                    <td class="orders-cell-center">{{ $reservation->qte }}</td>
                    <td class="orders-cell-center">{{ number_format($reservation->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td class="orders-cell-center"><strong>{{ number_format($reservation->montant, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            </tbody>
        </table>

        @if($reservation->note)
            <p style="margin-top:18px;"><strong>Note :</strong> {{ $reservation->note }}</p>
        @endif

        <div style="margin-top:24px; display:flex; gap:10px; flex-wrap:wrap;">
            @if($reservation->statut !== 'annulee')
                <a href="{{ route('client.reservations.receipt', $reservation) }}" class="btn-order-ticket">
                    <i class="fas fa-file-pdf"></i> Télécharger le reçu
                </a>
            @endif
            @if($reservation->statut === 'validee')
                <form method="POST" action="{{ route('client.reservations.cancel', $reservation) }}"
                      onsubmit="return confirm('Annuler cette réservation ?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-order-view" style="background:#dc3545; border:none; cursor:pointer;">Annuler</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection