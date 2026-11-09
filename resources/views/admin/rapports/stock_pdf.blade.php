{{-- resources/views/admin/rapports/stock_pdf.blade.php --}}
{{-- Page PDF unique pour tous les onglets des rapports (variable $onglet) --}}
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 30px 36px 48px; }
    * { box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        color: #2d2a24;
        font-size: 10.5px;
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

    /* ================= SYNTHÈSE (vue d'ensemble) ================= */
    table.synthese {
        width: 100%;
        border-collapse: separate;
        border-spacing: 8px 0;
        margin: 0 -8px 22px;
    }
    table.synthese td {
        width: 33.33%;
        background: #faf8f5;
        border: 1px solid #ece6de;
        border-radius: 10px;
        padding: 12px 16px;
        vertical-align: top;
    }
    .synthese .k {
        font-size: 9px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #8a7f72;
        font-weight: bold;
        margin-bottom: 4px;
    }
    .synthese .v {
        font-size: 15px;
        font-weight: bold;
        color: #2d5a27;
    }
    .synthese td.or .v { color: #b8860b; }

    /* ================= TABLEAUX ================= */
    table.liste {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #ece6de;
    }
    table.liste thead { display: table-header-group; }
    table.liste tr { page-break-inside: avoid; }

    table.liste thead th {
        background: #2d5a27;
        color: #ffffff;
        text-align: left;
        padding: 10px 12px;
        font-size: 8.5px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
    }
    table.liste thead th.num { text-align: right; }

    table.liste tbody td {
        padding: 8px 12px;
        border-bottom: 1px solid #f0ebe5;
        color: #2d2a24;
    }
    table.liste tbody tr:nth-child(even) td { background: #faf8f5; }
    table.liste tbody tr:last-child td { border-bottom: none; }

    table.liste .num { text-align: right; white-space: nowrap; }
    table.liste .fort { font-weight: bold; color: #2d5a27; }
    table.liste .gris { color: #6c5f4f; }
    table.liste .alerte { color: #c62828; font-weight: bold; }

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
    .nb {
        font-weight: normal;
        font-size: 10px;
        color: #8a7f72;
        margin-left: 6px;
    }

    /* ================= FOOTER ================= */
    .footer {
        position: fixed;
        bottom: -24px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 9px;
        color: #a89b8b;
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
    $titres = [
        'vue-ensemble' => "Vue d'ensemble",
        'mouvements'   => 'Mouvements de stock',
        'produits'     => 'Produits en stock',
        'achats'       => 'Achats',
        'fournisseurs' => 'Fournisseurs',
        'users'        => 'Utilisateurs',
        'ventes'       => 'Ventes',
        'depenses'     => 'Dépenses',
    ];
    $titre = $titres[$onglet] ?? ucfirst($onglet);

    $fmt    = fn ($v) => number_format((float) ($v ?? 0), 0, ',', ' ');
    $fcfa   = fn ($v) => $fmt($v) . ' FCFA';
    $date   = fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y') : '—';
    $statut = fn ($s) => $s ? ucfirst(str_replace('_', ' ', (string) $s)) : '—';
    $nomUser = fn ($u) => $u ? (trim(($u->prenom ?? '') . ' ' . ($u->nom ?? '')) ?: '—') : '—';

    // Logo : PNG/JPG conseillés (DomPDF gère mal le WebP)
    $logo = null;
    foreach (['logo.png' => 'image/png', 'logo.jpg' => 'image/jpeg', 'logo.webp' => 'image/webp'] as $fichier => $mime) {
        $chemin = public_path('img/' . $fichier);
        if (file_exists($chemin)) {
            $logo = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($chemin));
            break;
        }
    }

    $colspans = ['vue-ensemble' => 6, 'mouvements' => 8, 'produits' => 6, 'achats' => 6,
                 'fournisseurs' => 4, 'users' => 3, 'ventes' => 6, 'depenses' => 4];
    $colspan = $colspans[$onglet] ?? 4;

    // Barre de total : [libellé, valeur, nombre de lignes]
    [$totalLabel, $totalValeur, $totalNb] = match ($onglet) {
        'mouvements'   => ['Valeur totale des mouvements', $mouvementsTotal ?? 0, count($mouvements ?? [])],
        'produits'     => ['Valeur totale du stock', $variantsTotal ?? 0, count($variants ?? [])],
        'achats'       => ['Total des achats', $achatsTotal ?? 0, count($achats ?? [])],
        'fournisseurs' => ['Total des achats', $fournisseursTotal ?? 0, count($fournisseurs ?? [])],
        'users'        => ['Total des ventes', $usersTotal ?? 0, count($users ?? [])],
        'ventes'       => ['Total des ventes', $ventesTotal ?? 0, count($ventes ?? [])],
        'depenses'     => ['Total des dépenses', $depensesTotal ?? 0, count($depensesListe ?? [])],
        default        => [null, null, null],
    };
@endphp

{{-- ================= EN-TÊTE ================= --}}
<table class="entete">
    <tr>
        @if($logo)
            <td class="logo"><img src="{{ $logo }}" alt="Logo"></td>
        @endif
        <td class="titre">
            <h1>{{ $titre }}</h1>
        </td>
        <td class="infos">
            <div class="label">Période analysée</div>
            <div class="periode">{{ $date($dateFrom) }} &ndash; {{ $date($dateTo) }}</div>
            <div class="gen">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
        </td>
    </tr>
</table>
<div class="regle"></div>

{{-- ================= CONTENU SELON L'ONGLET ================= --}}

@if($onglet === 'vue-ensemble')
    <table class="synthese">
        <tr>
            <td><div class="k">Achats</div><div class="v">{{ $fcfa($vueEnsembleTotal['achats'] ?? 0) }}</div></td>
            <td><div class="k">Ventes</div><div class="v">{{ $fcfa($vueEnsembleTotal['ventes'] ?? 0) }}</div></td>
            <td class="or"><div class="k">Dépenses</div><div class="v">{{ $fcfa($vueEnsembleTotal['depenses'] ?? 0) }}</div></td>
        </tr>
    </table>
    <table class="liste">
        <thead>
            <tr>
                <th>Date</th><th>Type</th><th>Libellé</th>
                <th class="num">Achat</th><th class="num">Vente</th><th class="num">Dépense</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mouvementsVue as $m)
                <tr>
                    <td class="gris">{{ $date($m->date) }}</td>
                    <td class="fort">{{ $m->type }}</td>
                    <td>{{ $m->libelle }}</td>
                    <td class="num">{{ $m->montant_achat !== null ? $fmt($m->montant_achat) : '' }}</td>
                    <td class="num">{{ $m->montant_vente !== null ? $fmt($m->montant_vente) : '' }}</td>
                    <td class="num">{{ $m->montant_depense !== null ? $fmt($m->montant_depense) : '' }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucun mouvement sur cette période</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($onglet === 'mouvements')
    <table class="liste">
        <thead>
            <tr>
                <th>Date</th><th>Produit</th><th>Type</th>
                <th class="num">Qté</th><th class="num">Prix unitaire</th><th class="num">Valeur</th>
                <th>Référence</th><th>Utilisateur</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mouvements as $m)
                @php
                    $reference = match (true) {
                        $m->source instanceof \App\Models\Achat => $m->source->numero,
                        $m->source instanceof \App\Models\Order => $m->source->num_order,
                        $m->source instanceof \App\Models\Inventaire => $m->source->reference,
                        default => '—',
                    };
                @endphp
                <tr>
                    <td class="gris">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td class="fort">{{ $m->variant->reference_prod ?? $m->product->designation ?? '—' }}</td>
                    <td>{{ $m->sens === 'entree' ? 'Entrée' : 'Sortie' }} ({{ ucfirst($m->type) }})</td>
                    <td class="num">{{ $m->quantite }}</td>
                    <td class="num">{{ $fmt($m->cmp_apres) }}</td>
                    <td class="num fort">{{ $fmt($m->quantite * $m->cmp_apres) }}</td>
                    <td class="gris">{{ $reference }}</td>
                    <td class="gris">{{ $m->user->prenom ?? '—' }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucun mouvement sur cette période</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($onglet === 'produits')
    <table class="liste">
        <thead>
            <tr>
                <th>Référence</th><th>Produit</th><th>Catégorie</th>
                <th class="num">Qté</th><th class="num">CMP</th><th class="num">Valeur</th>
            </tr>
        </thead>
        <tbody>
            @forelse($variants as $v)
                <tr>
                    <td class="fort">{{ $v->reference_prod }}</td>
                    <td>{{ $v->product->designation ?? '—' }}</td>
                    <td class="gris">{{ $v->product->category->nom ?? '—' }}</td>
                    <td class="num {{ $v->qte_dispo <= ($v->stock_minimum ?? 0) ? 'alerte' : '' }}">{{ $v->qte_dispo }}</td>
                    <td class="num">{{ $fmt($v->cmp) }}</td>
                    <td class="num fort">{{ $fmt($v->qte_dispo * $v->cmp) }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucun produit</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($onglet === 'achats')
    <table class="liste">
        <thead>
            <tr>
                <th>N°</th><th>Fournisseur</th><th>Date</th><th>Statut</th>
                <th class="num">Montant total</th><th class="num">Montant payé</th>
            </tr>
        </thead>
        <tbody>
            @forelse($achats as $a)
                <tr>
                    <td class="fort">{{ $a->numero }}</td>
                    <td class="gris">{{ $a->fournisseur->full_name ?? '—' }}</td>
                    <td class="gris">{{ $date($a->date_achat) }}</td>
                    <td>{{ $statut($a->statut) }}</td>
                    <td class="num fort">{{ $fmt($a->mt_total) }}</td>
                    <td class="num gris">{{ $fmt($a->mt_paye) }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucun achat sur cette période</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($onglet === 'fournisseurs')
    <table class="liste">
        <thead>
            <tr>
                <th>Fournisseur</th><th>Ville</th>
                <th class="num">Nb achats</th><th class="num">Total achats</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fournisseurs as $f)
                <tr>
                    <td class="fort">{{ $f->full_name }}</td>
                    <td class="gris">{{ $f->ville }}</td>
                    <td class="num">{{ $f->achats_count }}</td>
                    <td class="num fort">{{ $fmt($f->achats_sum_mt_total) }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucun fournisseur</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($onglet === 'users')
    <table class="liste">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th class="num">Nb commandes</th><th class="num">Total dépensé</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
                <tr>
                    <td class="fort">{{ $nomUser($u) }}</td>
                    <td class="num">{{ $u->orders_count }}</td>
                    <td class="num fort">{{ $fmt($u->orders_sum_montant_ttc) }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucun utilisateur</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($onglet === 'ventes')
    <table class="liste">
        <thead>
            <tr>
                <th>N° commande</th><th>Date</th><th>Client</th><th>Zone</th><th>Statut</th>
                <th class="num">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventes as $v)
                <tr>
                    <td class="fort">{{ $v->num_order }}</td>
                    <td class="gris">{{ $date($v->date_order) }}</td>
                    <td>{{ $nomUser($v->user) }}</td>
                    <td class="gris">{{ $v->zone->nom ?? '—' }}</td>
                    <td>{{ $statut($v->statut) }}</td>
                    <td class="num fort">{{ $fmt($v->montant_ttc) }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucune vente sur cette période</td></tr>
            @endforelse
        </tbody>
    </table>

@elseif($onglet === 'depenses')
    <table class="liste">
        <thead>
            <tr>
                <th>Date</th><th>Famille</th><th>Libellé</th>
                <th class="num">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($depensesListe as $d)
                <tr>
                    <td class="gris">{{ $date($d->date_depense) }}</td>
                    <td class="fort">{{ $d->libelle->categorie->nom ?? '—' }}</td>
                    <td>{{ $d->libelle->nom ?? '—' }}</td>
                    <td class="num fort">{{ $fmt($d->montant) }}</td>
                </tr>
            @empty
                <tr class="vide"><td colspan="{{ $colspan }}">Aucune dépense sur cette période</td></tr>
            @endforelse
        </tbody>
    </table>
@endif

{{-- ================= TOTAL ================= --}}
@if($totalLabel)
    <table class="total">
        <tr>
            <td>
                {{ $totalLabel }}
                <span class="nb">({{ $totalNb }} ligne{{ $totalNb > 1 ? 's' : '' }})</span>
            </td>
            <td>{{ $fcfa($totalValeur) }}</td>
        </tr>
    </table>
@endif

{{-- ================= FOOTER ================= --}}
<div class="footer">
    <span class="brand">La Réserve Naturelle</span> — Document généré automatiquement, à usage interne
</div>

</body>
</html>