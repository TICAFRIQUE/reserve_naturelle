@extends('layouts.app')

@section('title', 'Mes Réservations')
@section('hide_slider', true)
@section('content')

<div class="container orders-wrapper">
    <h1 class="orders-title">🕒 Mes Réservations</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-error">{{ session('error') }}</div> @endif
    @if($errors->any())     <div class="alert alert-error">{{ $errors->first() }}</div> @endif

    @php
        $labels = [
            'validee' => ['Validée', 'status-validated'],
            'payee'   => ['Payée',   'status-paid'],
            'livree'  => ['Livrée',  'status-delivered'],
            'annulee' => ['Annulée', 'status-cancelled'],
        ];
    @endphp

    @if($reservations->count())
        <div class="orders-card">
            <div class="orders-table-wrapper" style="display:block; overflow-x:auto;">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $r)
                            @php [$label, $class] = $labels[$r->statut] ?? [$r->statut, 'status-default']; @endphp
                            <tr>
                                <td class="orders-cell-num"><strong>{{ $r->num_reservation }}</strong></td>
                                <td>{{ $r->variant?->libelle ?? 'Produit supprimé' }}</td>
                                <td class="orders-cell-center">{{ $r->qte }}</td>
                                <td class="orders-cell-center">{{ $r->date_reservation->format('d/m/Y') }}</td>
                                <td class="orders-cell-center"><span class="order-status {{ $class }}">{{ $label }}</span></td>
                                <td class="orders-cell-center">{{ number_format($r->montant, 0, ',', ' ') }} FCFA</td>
                                <td class="orders-cell-center">
                                    {{-- Modification : conteneur flex + nouveaux boutons --}}
                                    <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                                        <a href="{{ route('client.reservations.show', $r) }}" class="btn-order-view">Voir détails</a>
                                        @if($r->statut !== 'annulee')
                                            <a href="{{ route('client.reservations.receipt', $r) }}" class="btn-order-ticket">
                                                <i class="fas fa-file-pdf"></i> Reçu
                                            </a>
                                        @endif
                                        @if($r->statut === 'validee')
                                            <form method="POST" action="{{ route('client.reservations.cancel', $r) }}"
                                                  onsubmit="return confirm('Annuler cette réservation ?')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-order-view" style="background:#dc3545; border:none; cursor:pointer;">Annuler</button>
                                            </form>
                                        @else
                                            
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="orders-pagination">{{ $reservations->links() }}</div>
    @else
        <div class="orders-empty">
            <div class="orders-empty-icon">🕒</div>
            <h3>Aucune réservation</h3>
            <p>Les produits en rupture peuvent être réservés depuis le catalogue.</p>
            <a href="{{ route('client.products.catalogue') }}" class="btn-primary">Voir les produits</a>
        </div>
    @endif
</div>
@endsection