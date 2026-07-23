@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <h1 style="font-size: 32px; margin-bottom: 30px;">📋 Mes Commandes</h1>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->count() > 0)
        @php
            $statutLabels = [
                'en_attente' => ['label' => 'En attente', 'color' => '#e65100', 'bg' => '#fff3e0'],
                'payee'      => ['label' => 'Payée', 'color' => '#6a1b9a', 'bg' => '#f3e5f5'],
                'validee'    => ['label' => 'Validée', 'color' => '#1565c0', 'bg' => '#e3f2fd'],
                'livree'     => ['label' => 'Livrée', 'color' => '#2e7d32', 'bg' => '#e8f5e9'],
                'annulee'    => ['label' => 'Annulée', 'color' => '#c62828', 'bg' => '#ffebee'],
            ];
        @endphp

        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">N° Commande</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Date</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Statut</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Total</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php $statut = $statutLabels[$order->statut] ?? ['label' => $order->statut, 'color' => '#666', 'bg' => '#eee']; @endphp
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                <strong>{{ $order->num_order }}</strong>
                            </td>
                            <td style="padding: 15px; text-align: center; color: #6c757d;">
                                {{ $order->date_order->format('d/m/Y') }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: {{ $statut['bg'] }}; color: {{ $statut['color'] }}; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    {{ $statut['label'] }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center; font-weight: 700; color: #1b5e20;">
                                {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('client.orders.show', $order) }}"
                                   style="background: #2e7d32; color: white; padding: 6px 18px; border-radius: 6px; text-decoration: none; font-size: 13px;">
                                    Voir détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
            <div style="font-size: 64px; margin-bottom: 20px;">📦</div>
            <h3 style="color: #333; font-size: 24px; margin-bottom: 10px;">Aucune commande pour l'instant</h3>
            <p style="color: #6c757d; margin-bottom: 20px;">Vos commandes passées apparaîtront ici.</p>
            <a href="{{ route('client.products.catalogue') }}" style="background: #2e7d32; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; display: inline-block;">
                Voir les produits
            </a>
        </div>
    @endif
</div>
@endsection