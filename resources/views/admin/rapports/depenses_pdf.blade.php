{{-- resources/views/admin/rapports/depenses_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 30px 36px; }
    * { box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        color: #2d2a24;
        font-size: 11px;
        margin: 0;
        background: #ffffff;
    }

    /* ================= EN-TÊTE ================= */
    table.entete { width: 100%; border-collapse: collapse; }
    table.entete td { border: none; padding: 0; vertical-align: middle; }
    table.entete td.logo { width: 84px; }
    table.entete td.logo img { width: 68px; height: 68px; border-radius: 10px; }
    table.entete td.titre { padding-left: 8px; }
    .marque {
        font-size: 9px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #b8860b;
        font-weight: bold;
        margin-bottom: 4px;
    }
    h1 {
        font-size: 22px;
        margin: 0;
        color: #2d5a27;
        font-family: 'DejaVu Serif', Georgia, serif;
    }
    .sous-titre { font-size: 10px; color: #8a7f72; margin-top: 3px; letter-spacing: 0.5px; }

    table.entete td.infos { text-align: right; line-height: 1.6; }
    .infos .label {
        font-size: 9px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #8a7f72;
        font-weight: bold;
    }
    .infos .periode {
        font-size: 13px;
        font-weight: bold;
        color: #2d5a27;
        margin-top: 2px;
    }
    .infos .gen { font-size: 9px; color: #a89b8b; margin-top: 4px; }

    .regle {
        border-bottom: 2px solid #2d5a27;
        margin: 16px 0 22px;
    }

    /* ================= BANDEAU RÉSUMÉ ================= */
    table.resume {
        width: 100%;
        border-collapse: separate;
        border-spacing: 8px 0;
        margin: 0 -8px 22px;
    }
    table.resume td {
        background: #faf8f5;
        border: 1px solid #ece6de;
        border-radius: 10px;
        padding: 12px 14px;
        vertical-align: top;
    }
    table.resume td .r-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a7f72;
        font-weight: bold;
        margin-bottom: 4px;
    }
    table.resume td .r-value {
        font-size: 16px;
        font-weight: bold;
        color: #2d5a27;
    }
    table.resume td .r-value.or { color: #b8860b; }

    /* ================= TABLEAU PRINCIPAL ================= */
    table.liste {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #ece6de;
    }
    table.liste thead th {
        background: #2d5a27;
        color: #ffffff;
        text-align: left;
        padding: 10px 12px;
        font-size: 9px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
    }
    table.liste thead th:last-child { text-align: right; }
    table.liste thead th:nth-child(1) { width: 15%; }
    table.liste thead th:nth-child(2) { width: 22%; }
    table.liste thead th:nth-child(3) { width: 43%; }

    table.liste tbody td {
        padding: 9px 12px;
        border-bottom: 1px solid #f0ebe5;
        color: #2d2a24;
    }
    table.liste tbody tr:nth-child(even) td { background: #faf8f5; }
    table.liste tbody tr:last-child td { border-bottom: none; }
    table.liste td.date { color: #5f5546; font-size: 10px; }
    table.liste td.famille { font-weight: bold; color: #2d5a27; }
    table.liste td.montant {
        text-align: right;
        font-weight: bold;
        white-space: nowrap;
        color: #2d2a24;
    }
    table.liste tr.vide td {
        text-align: center;
        color: #a89b8b;
        padding: 26px;
        font-style: italic;
    }

    /* ================= TOTAL ================= */
    table.total {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
        border: 2px solid #2d5a27;
        border-radius: 10px;
        overflow: hidden;
    }
    table.total td {
        padding: 14px 16px;
        font-weight: bold;
        font-size: 13px;
        color: #2d5a27;
        background: #faf8f5;
    }
    table.total td:last-child {
        text-align: right;
        font-size: 18px;
        white-space: nowrap;
        color: #b8860b;
    }
    table.total td .count {
        font-size: 10px;
        color: #8a7f72;
        font-weight: normal;
        margin-left: 6px;
    }

    /* ================= FOOTER ================= */
    .footer {
        margin-top: 28px;
        padding-top: 12px;
        border-top: 1px solid #ece6de;
        font-size: 9px;
        color: #a89b8b;
        text-align: center;
        letter-spacing: 0.3px;
    }
    .footer .brand {
        color: #2d5a27;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
</style>
</head>
<body>
@php
    $fcfa = fn ($v) => number_format((float) ($v ?? 0), 0, ',', ' ') . ' FCFA';

    // Logo : PNG/JPG conseillés (DomPDF gère mal le WebP)
    $logo = null;
    foreach (['logo.png' => 'image/png', 'logo.jpg' => 'image/jpeg', 'logo.webp' => 'image/webp'] as $fichier => $mime) {
        $chemin = public_path('img/' . $fichier);
        if (file_exists($chemin)) {
            $logo = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($chemin));
            break;
        }
    }

    $nbDepenses = $depensesListe->count();
    $moyenne = $nbDepenses > 0 ? $depensesTotal / $nbDepenses : 0;
@endphp

{{-- ================= EN-TÊTE ================= --}}
<table class="entete">
    <tr>
        @if($logo)
            <td class="logo"><img src="{{ $logo }}" alt="Logo"></td>
        @endif
        <td class="titre">
            <div class="marque">La Réserve Naturelle</div>
            <h1>Rapport des dépenses</h1>
            <div class="sous-titre">🌿 Produits naturels &amp; authentiques</div>
        </td>
        <td class="infos">
            <div class="label">Période analysée</div>
            <div class="periode">
                {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }}
                &ndash; {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
            </div>
            <div class="gen">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
        </td>
    </tr>
</table>
<div class="regle"></div>

{{-- ================= BANDEAU RÉSUMÉ ================= --}}
<table class="resume">
    <tr>
        <td style="width: 33%;">
            <div class="r-label">Total des dépenses</div>
            <div class="r-value or">{{ $fcfa($depensesTotal) }}</div>
        </td>
        <td style="width: 33%;">
            <div class="r-label">Nombre d'opérations</div>
            <div class="r-value">{{ $nbDepenses }}</div>
        </td>
        <td style="width: 33%;">
            <div class="r-label">Dépense moyenne</div>
            <div class="r-value">{{ $fcfa($moyenne) }}</div>
        </td>
    </tr>
</table>

{{-- ================= TABLEAU ================= --}}
<table class="liste">
    <thead>
        <tr>
            <th>Date</th>
            <th>Famille</th>
            <th>Libellé</th>
            <th>Montant</th>
        </tr>
    </thead>
    <tbody>
        @forelse($depensesListe as $d)
            <tr>
                <td class="date">{{ $d->date_depense->format('d/m/Y') }}</td>
                <td class="famille">{{ $d->libelle->categorie->nom ?? '—' }}</td>
                <td>{{ $d->libelle->nom ?? '—' }}</td>
                <td class="montant">{{ $fcfa($d->montant) }}</td>
            </tr>
        @empty
            <tr class="vide"><td colspan="4">Aucune dépense sur cette période</td></tr>
        @endforelse
    </tbody>
</table>

{{-- ================= TOTAL ================= --}}
<table class="total">
    <tr>
        <td>
            Total des dépenses
            <span class="count">({{ $nbDepenses }} opération{{ $nbDepenses > 1 ? 's' : '' }})</span>
        </td>
        <td>{{ $fcfa($depensesTotal) }}</td>
    </tr>
</table>

{{-- ================= FOOTER ================= --}}
<div class="footer">
    <span class="brand">La Réserve Naturelle</span> — Document généré automatiquement, à usage interne
</div>

</body>
</html>