@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')

<div class="container orders-wrapper">
    <h1 class="orders-title">📋 Mes Commandes</h1>

    <!-- ============================================
         MESSAGES FLASH
    ============================================ -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->count() > 0)

        @php
            $statutLabels = [
                'en_attente' => ['label' => 'En attente', 'class' => 'status-pending'],
                'payee'      => ['label' => 'Payée',      'class' => 'status-paid'],
                'validee'    => ['label' => 'Validée',    'class' => 'status-validated'],
                'livree'     => ['label' => 'Livrée',     'class' => 'status-delivered'],
                'annulee'    => ['label' => 'Annulée',    'class' => 'status-cancelled'],
            ];
        @endphp

        <div class="orders-card">

            <!-- ========================================
                 VUE DESKTOP : TABLEAU
            ======================================== -->
            <div class="orders-table-wrapper">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $statut = $statutLabels[$order->statut]
                                    ?? ['label' => $order->statut, 'class' => 'status-default'];
                            @endphp
                            <tr>
                                <td class="orders-cell-num">
                                    <strong>{{ $order->num_order }}</strong>
                                </td>
                                <td class="orders-cell-center">
                                    {{ $order->date_order->format('d/m/Y') }}
                                </td>
                                <td class="orders-cell-center">
                                    <span class="order-status {{ $statut['class'] }}">
                                        {{ $statut['label'] }}
                                    </span>
                                </td>
                                <td class="orders-cell-center orders-cell-total">
                                    {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="orders-cell-center">
                                    <div class="orders-actions">
                                        <a href="{{ route('client.orders.show', $order) }}" class="btn-order-view">
                                            Voir détails
                                        </a>
                                        @if($order->ticket_path)
                                            <a href="{{ route('client.orders.ticket', $order) }}" class="btn-order-ticket">
                                                <i class="fas fa-file-pdf"></i> Reçu
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- ========================================
                 VUE MOBILE : CARTES
            ======================================== -->
            <div class="orders-mobile-list">
                @foreach($orders as $order)
                    @php
                        $statut = $statutLabels[$order->statut]
                            ?? ['label' => $order->statut, 'class' => 'status-default'];
                    @endphp
                    <div class="order-mobile-item">

                        <div class="order-mobile-header">
                            <div class="order-mobile-num">
                                <span class="order-mobile-label">N°</span>
                                <strong>{{ $order->num_order }}</strong>
                            </div>
                            <span class="order-status {{ $statut['class'] }}">
                                {{ $statut['label'] }}
                            </span>
                        </div>

                        <div class="order-mobile-body">
                            <div class="order-mobile-line">
                                <span class="order-mobile-label">Date</span>
                                <span>{{ $order->date_order->format('d/m/Y') }}</span>
                            </div>
                            <div class="order-mobile-line">
                                <span class="order-mobile-label">Total</span>
                                <span class="order-mobile-total">
                                    {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>

                        <div class="order-mobile-actions">
                            <a href="{{ route('client.orders.show', $order) }}" class="btn-order-view">
                                Voir détails
                            </a>
                            @if($order->ticket_path)
                                <a href="{{ route('client.orders.ticket', $order) }}" class="btn-order-ticket">
                                    <i class="fas fa-file-pdf"></i> Reçu
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="orders-pagination">
            {{ $orders->links() }}
        </div>

    @else
        <div class="orders-empty">
            <div class="orders-empty-icon">📦</div>
            <h3>Aucune commande pour l'instant</h3>
            <p>Vos commandes passées apparaîtront ici.</p>
            <a href="{{ route('client.products.catalogue') }}" class="btn-primary">
                Voir les produits
            </a>
        </div>
    @endif
</div>

@endsection