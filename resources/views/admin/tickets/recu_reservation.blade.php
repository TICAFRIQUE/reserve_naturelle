<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 30px 40px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .logo { width: 90px; height: auto; }
        .brand h2 { margin: 0 0 3px; color: #2d5a27; font-size: 20px; }
        .brand p { margin: 0; color: #6c757d; font-size: 10px; }
        .doc-info { text-align: right; }
        .doc-title { font-size: 11px; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 4px; }
        .doc-num { font-size: 15px; font-weight: bold; color: #2d5a27; margin: 0 0 4px; }
        .doc-date { margin: 0; font-size: 11px; }
        .header-line { border-top: 3px solid #2d5a27; margin: 12px 0 18px; }
        .infos { background: #f8f5f0; border-left: 4px solid #b8860b; padding: 10px 14px; margin-bottom: 18px; }
        .infos p { margin: 4px 0; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th { background: #2d5a27; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
        table.items td { padding: 8px; border-bottom: 1px solid #e8e0d5; background: #eef5ec; }
        td.num, th.num { text-align: right; }
        .totaux { margin-top: 18px; text-align: right; }
        .total-box { display: inline-block; background: #2d5a27; color: #fff; padding: 8px 18px; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 40px; text-align: center; color: #6c757d; font-size: 10px; border-top: 1px solid #e8e0d5; padding-top: 10px; }
    </style>
</head>
<body>
@php
    $r = $reservation;
    $v = $r->variant;
    $clientNom = trim(($r->user->prenom ?? '') . ' ' . ($r->user->nom ?? $r->user->name ?? ''));
    $statuts = ['validee' => 'Validée', 'payee' => 'Payée', 'livree' => 'Livrée', 'annulee' => 'Annulée'];

    $logoPath = public_path('images/logo.png');
    $logoSrc = file_exists($logoPath)
        ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp

<table class="header-table">
    <tr>
        <td style="width:100px;">@if($logoSrc)<img src="{{ $logoSrc }}" class="logo" alt="Logo">@endif</td>
        <td class="brand">
            <h2>La Réserve Naturelle</h2>
            <p>TICAFRIQUE – Abidjan, Côte d'Ivoire</p>
        </td>
        <td class="doc-info">
            <p class="doc-title">Reçu de réservation</p>
            <p class="doc-num">{{ $r->num_reservation }}</p>
            <p class="doc-date">Date : {{ $r->date_reservation->format('d/m/Y') }}</p>
        </td>
    </tr>
</table>
<div class="header-line"></div>

<div class="infos">
    <p><strong>Client :</strong> {{ $clientNom ?: 'N/A' }}</p>
    <p><strong>Statut :</strong> {{ $statuts[$r->statut] ?? $r->statut }}</p>
    @if($r->note)<p><strong>Note :</strong> {{ $r->note }}</p>@endif
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
        <tr>
            <td>{{ $v?->reference_prod ?? '—' }}</td>
            <td>
                {{ $v?->product?->designation ?? 'Produit supprimé' }}
                @if($v?->conditionnement) ({{ $v->conditionnement }}) @endif
            </td>
            <td class="num">{{ $r->qte }}</td>
            <td class="num">{{ number_format($r->prix_unitaire, 0, ',', ' ') }} FCFA</td>
            <td class="num">{{ number_format($r->montant, 0, ',', ' ') }} FCFA</td>
        </tr>
    </tbody>
</table>

<div class="totaux">
    <div class="total-box">Total : {{ number_format($r->montant, 0, ',', ' ') }} FCFA</div>
</div>

<div class="footer">La Réserve Naturelle — Merci pour votre confiance.</div>
</body>
</html>