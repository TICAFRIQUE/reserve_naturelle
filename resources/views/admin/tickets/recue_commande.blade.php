{{-- resources/views/admin/tickets/recue_commande.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2d5a27; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px; color: #2d5a27; }
        .header p { margin: 2px 0; }
        .infos { margin-bottom: 15px; }
        .infos p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f8f5f0; color: #2d5a27; }
        th, td { border-bottom: 1px solid #ccc; padding: 6px; text-align: left; }
        td.num, th.num { text-align: right; }
        .totaux { margin-top: 15px; text-align: right; }
        .totaux p { margin: 4px 0; }
        .total-final { font-weight: bold; font-size: 14px; color: #2d5a27; }
    </style>
</head>
<body>
    @php
        $clientNom = trim(($order->user->prenom ?? '') . ' ' . ($order->user->nom ?? $order->user->name ?? ''));
        $dateOrder = \Carbon\Carbon::parse($order->date_order)->format('d/m/Y');
        $modes = ['domicile' => 'Livraison à domicile', 'expedition' => 'Expédition'];
        $mode = $modes[$order->mode_livraison] ?? null;
    @endphp

    <div class="header">
        <h2>La Réserve Naturelle</h2>
        <p><strong>Reçu de commande {{ $order->num_order }}</strong></p>
        <p>Date : {{ $dateOrder }}</p>
    </div>

    <div class="infos">
        <p><strong>Client :</strong> {{ $clientNom ?: 'N/A' }}</p>
        {{-- Bloc livraison : uniquement pour les anciennes commandes --}}
        @if ($mode)
            <p><strong>Adresse :</strong> {{ $order->adresse_precise ?: 'Non renseignée' }}</p>
            <p><strong>Mode de livraison :</strong> {{ $mode }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Désignation</th>
                <th class="num">Qté</th>
                <th class="num">Prix unitaire</th>
                <th class="num">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            @php
                $variant = $item->variant;
                $pu = $item->prix_unitaire ?: ($variant?->prix_vente ?? 0);
                $st = $item->sous_total ?: ($pu * $item->qte);
            @endphp
            <tr>
                <td>{{ $variant?->reference_prod ?? $item->product?->reference_prod ?? '—' }}</td>
                <td>
                    {{ $item->product?->designation ?? 'Produit supprimé' }}
                    @if ($variant?->conditionnement)
                        ({{ $variant->conditionnement }})
                    @endif
                </td>
                <td class="num">{{ $item->qte }}</td>
                <td class="num">{{ number_format($pu, 0, ',', ' ') }} FCFA</td>
                <td class="num">{{ number_format($st, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totaux">
        @if ($order->tarif_livraison > 0)
            <p>Sous-total : {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA</p>
            <p>Livraison : {{ number_format($order->tarif_livraison, 0, ',', ' ') }} FCFA</p>
        @endif
        <p class="total-final">Total TTC : {{ number_format($order->montant_ttc ?? $order->mt_total, 0, ',', ' ') }} FCFA</p>
    </div>
</body>
</html>