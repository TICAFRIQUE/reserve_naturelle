@extends('layouts.admin')

@section('title', 'Réservation ' . $reservation->num_reservation . ' - La Réserve Naturelle')

@section('content')
@php
    $cfg = [
        'validee' => ['Validée', '#cce5ff', '#004085'],
        'payee'   => ['Payée',   '#f3e5f5', '#6a1b9a'],
        'livree'  => ['Livrée',  '#d4edda', '#155724'],
        'annulee' => ['Annulée', '#f8d7da', '#721c24'],
    ];
    [$label, $bg, $color] = $cfg[$reservation->statut] ?? [$reservation->statut, '#e8e0d5', '#6c757d'];
    $v = $reservation->variant;
    $u = $reservation->user;
    $options = match($reservation->statut) {
        'validee' => ['payee' => 'Payée', 'livree' => 'Livrée', 'annulee' => 'Annulée'],
        'payee'   => ['livree' => 'Livrée', 'annulee' => 'Annulée'],
        default   => [],
    };
@endphp

<div class="container" style="padding:40px 0; max-width:860px;">
    <a href="{{ route('admin.reservations.index') }}" style="color:#2d5a27; text-decoration:none; font-weight:500;">
        <i class="fas fa-arrow-left"></i> Retour aux réservations
    </a>

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin:15px 0 20px;">
        <h1 style="font-family:'Playfair Display',serif; color:#2d5a27; margin:0;">
            Réservation {{ $reservation->num_reservation }}
        </h1>
        <span style="background:{{ $bg }}; color:{{ $color }}; padding:8px 18px; border-radius:20px; font-weight:500;">{{ $label }}</span>
    </div>

    @if(session('success')) <div style="background:#d4edda; color:#155724; padding:12px 20px; border-radius:8px; margin-bottom:15px;">{{ session('success') }}</div> @endif
    @if(session('error'))   <div style="background:#f8d7da; color:#721c24; padding:12px 20px; border-radius:8px; margin-bottom:15px;">{{ session('error') }}</div> @endif

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px; margin-bottom:20px;">
        <div style="background:#f8f5f0; border:1px solid #e8e0d5; border-radius:12px; padding:20px;">
            <h3 style="margin:0 0 10px; color:#2d5a27;"><i class="fas fa-user"></i> Client</h3> <br>
            <p style="margin:4px 0;"> <strong>Nom & prénoms :</strong> {{ trim(($u->prenom ?? '') . ' ' . ($u->nom ?? $u->name ?? '')) ?: 'N/A' }}</p><br>
            <p style="margin:4px 0; color:#6c757d;"> <strong>Email :</strong> {{ $u->email ?? '' }}</p><br>
            @if(!empty($u->tel))<p style="margin:4px 0; color:#6c757d;"> <strong>Contact :</strong> {{ $u->tel }}</p>@endif
        </div>
        <div style="background:#f8f5f0; border:1px solid #e8e0d5; border-radius:12px; padding:20px;">
            <h3 style="margin:0 0 10px; color:#2d5a27;"><i class="fas fa-calendar"></i> Informations</h3><br>
            <p style="margin:4px 0;">Date : <strong>{{ $reservation->date_reservation->format('d/m/Y') }}</strong></p><br>
            <p style="margin:4px 0;">Créée : <strong>{{ $reservation->created_at?->format('d/m/Y H:i') }}</strong></p>
            {{-- @if($reservation->note)<p style="margin:4px 0;">Note : {{ $reservation->note }}</p>@endif --}}
        </div>
    </div> <br>

    <div style="background:#fff; border:1px solid #e8e0d5; border-radius:12px; overflow-x:auto; margin-bottom:20px;">
        <table style="width:100%; border-collapse:collapse;">
            <thead style="background:#f8f5f0; color:#2d5a27;">
                <tr>
                    <th style="padding:14px; text-align:left;">Référence</th>
                    <th style="padding:14px; text-align:left;">Produit</th>
                    <th style="padding:14px; text-align:center;">Qté</th>
                    <th style="padding:14px; text-align:right;">Prix unitaire</th>
                    <th style="padding:14px; text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-top:1px solid #f0ebe5;">
                    <td style="padding:14px;">{{ $v?->reference_prod ?? '—' }}</td>
                    <td style="padding:14px;">
                        {{ $v?->libelle ?? 'Produit supprimé' }}
                        @if($v)
                            <br><small style="color:{{ $v->qte_dispo >= $reservation->qte ? '#155724' : '#721c24' }};">
                                Stock actuel : {{ $v->qte_dispo }}
                            </small>
                        @endif
                    </td>
                    <td style="padding:14px; text-align:center;">{{ $reservation->qte }}</td>
                    <td style="padding:14px; text-align:right;">{{ number_format($reservation->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td style="padding:14px; text-align:right;"><strong>{{ number_format($reservation->montant, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
        {{-- @if($reservation->statut !== 'annulee')
            <a href="{{ route('admin.reservations.receipt', $reservation) }}"
               style="background:#cce5ff; color:#004085; padding:10px 22px; border-radius:30px; text-decoration:none; font-weight:500;">
                <i class="fas fa-file-pdf"></i> Télécharger le reçu
            </a>
        @endif --}}

        @if($options)
            <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}" style="display:flex; gap:8px;">
                @csrf @method('PATCH')
                <select name="statut" required style="padding:8px 12px; border:1px solid #e8e0d5; border-radius:8px;">
                    @foreach($options as $val => $lab)
                        <option value="{{ $val }}">{{ $lab }}</option>
                    @endforeach
                </select>
                <button type="submit" style="background:#2d5a27; color:#fff; border:none; padding:8px 20px; border-radius:30px; cursor:pointer;">
                    Mettre à jour
                </button>
            </form>
        @endif
    </div>
</div>
@endsection