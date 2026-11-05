{{-- resources/views/admin/tickets/recue_commande.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 30px 40px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }

        /* En-tête */
        .header-table { width: 100%; border-collapse: collapse; margin: 0; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .logo { width: 90px; height: auto; }
        .brand h2 { margin: 0 0 3px; color: #2d5a27; font-size: 20px; }
        .brand p { margin: 0; color: #6c757d; font-size: 10px; }
        .doc-info { text-align: right; }
        .doc-info .doc-title { font-size: 11px; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 4px; }
        .doc-info .doc-num { font-size: 15px; font-weight: bold; color: #2d5a27; margin: 0 0 4px; }
        .doc-info .doc-date { margin: 0; font-size: 11px; }
        .header-line { border-top: 3px solid #2d5a27; margin: 12px 0 18px; }

        /* Infos client */
        .infos { background: #f8f5f0; border-left: 4px solid #b8860b; padding: 10px 14px; margin-bottom: 18px; }
        .infos p { margin: 4px 0; }

        /* Tableau articles */
        table.items { width: 100%; border-collapse: collapse; }
        table.items th {
            background: #2d5a27; color: #ffffff;
            padding: 8px 8px; text-align: left; font-size: 11px;
        }
        table.items td { padding: 8px; border-bottom: 1px solid #e8e0d5; }
        table.items tr.even td { background: #eef5ec; }   /* vert très clair */
        table.items tr.odd td  { background: #ffffff; }
        td.num, th.num { text-align: right; }

        /* Totaux */
        .totaux { margin-top: 18px; text-align: right; }
        .totaux p { margin: 4px 0; }
        .total-box {
            display: inline-block; background: #2d5a27; color: #ffffff;
            padding: 8px 18px; font-weight: bold; font-size: 14px; margin-top: 6px;
        }

        .footer {
        margin-top: 40px;
        padding-top: 10px;
        border-top: 1px solid #e8e0d5;
        text-align: center;
        color: #6c757d;
        font-size: 10px;
        line-height: 1.5;
    }
    </style>
</head>
<body>
    @php
        $clientNom = trim(($order->user->prenom ?? '') . ' ' . ($order->user->nom ?? $order->user->name ?? ''));
        $dateOrder = \Carbon\Carbon::parse($order->date_order)->format('d/m/Y');
        $modes = [
            'retrait'    => 'Retrait sur place',
            'yango'      => 'Livraison Yango (frais à la charge du client)',
            'domicile'   => 'Livraison à domicile',
            'expedition' => 'Expédition',
        ];
        $mode = $modes[$order->mode_livraison] ?? null;

        // Logo en base64 (adapte le chemin à ton fichier, PNG/JPG conseillé)
        $logoPath = public_path('img/logo.webp');
        $logoSrc = file_exists($logoPath)
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : null;
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 100px;">
                @if ($logoSrc)
                    <img src="{{ $logoSrc }}" class="logo" alt="Logo">
                @endif
            </td>
            <td class="brand">
                <h2>La Réserve Naturelle</h2>
                <p>TICAFRIQUE – Abidjan, Côte d'Ivoire</p>
            </td>
            <td class="doc-info">
                <p class="doc-title">Reçu de commande</p>
                <p class="doc-num">{{ $order->num_order }}</p>
                <p class="doc-date">Date : {{ $dateOrder }}</p>
            </td>
        </tr>
    </table>
    <div class="header-line"></div>

    <div class="infos">
        <p><strong>Client :</strong> {{ $clientNom ?: 'N/A' }}</p>
        @if ($mode)
            <p><strong>Mode de récupération :</strong> {{ $mode }}</p>
            @if ($order->adresse_precise)
                <p><strong>Adresse :</strong> {{ $order->adresse_precise }}</p>
            @endif
        @endif
    </div>

    <table class="items">
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
            <tr class="{{ $loop->even ? 'even' : 'odd' }}">
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
        <div class="total-box">Total TTC : {{ number_format($order->montant_ttc ?? $order->mt_total, 0, ',', ' ') }} FCFA</div>
    </div>

    <div class="footer">
        La Réserve Naturelle — Merci pour votre confiance.<br>
        Pour plus d'infos, contactez le <strong>05 56 66 92 99</strong> par appel ou <a href="mailto:commande@lareservenaturelle.ci">commande@lareservenaturelle.ci</a>
    </div>
</body>
</html>