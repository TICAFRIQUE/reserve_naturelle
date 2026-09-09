{{-- resources/views/admin/tickets/recue_commande.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .infos { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #ccc; padding: 6px; text-align: left; }
        .totaux { margin-top: 15px; text-align: right; }
        .totaux p { margin: 4px 0; }
        .total-final { font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reçu de commande {{ $order->num_order }}</h2>
        <p>Date : {{ $order->date_order->format('d/m/Y') }}</p>
    </div>

    <div class="infos">
        <p><strong>Client :</strong> {{ $order->user->nom }}</p>
        <p><strong>Adresse :</strong> {{ $order->adresse_precise }}</p>
        <p><strong>Mode de livraison :</strong> {{ $order->mode_livraison }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Désignation</th>
                <th>Qté</th>
                <th>Prix unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product->reference_prod }}</td>
                <td>{{ $item->product->designation }}</td>
                <td>{{ $item->qte }}</td>
                <td>{{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($item->product->prix_vente * $item->qte, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totaux">
        <p>Sous-total : {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA</p>
        <p>Livraison : {{ number_format($order->tarif_livraison, 0, ',', ' ') }} FCFA</p>
        @if ($order->remise)
            <p>Remise : -{{ number_format($order->remise, 0, ',', ' ') }} FCFA</p>
        @endif
        <p class="total-final">Total TTC : {{ number_format($order->montant_ttc, 0, ',', ' ') }} FCFA</p>
    </div>
</body>
</html>