{{-- resources/views/admin/rapports/stock_pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 25px 30px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #2d2d2d; font-size: 11px; }
        .header { border-bottom: 3px solid #2d5a27; padding-bottom: 12px; margin-bottom: 18px; }
        .header h1 { color: #2d5a27; font-size: 19px; margin: 0 0 4px 0; }
        .header .meta { color: #6c757d; font-size: 10px; }
        .cartes { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .cartes td {
            width: 20%; background: #f8f5f0; border: 1px solid #e8e0d5; padding: 10px 12px;
            vertical-align: top;
        }
        .cartes .label { color: #6c757d; font-size: 9px; text-transform: uppercase; }
        .cartes .valeur { color: #2d5a27; font-size: 13px; font-weight: bold; margin-top: 3px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th {
            background: #2d5a27; color: white; padding: 7px 8px; text-align: left; font-size: 10px;
        }
        table.data td { padding: 6px 8px; border-bottom: 1px solid #e8e0d5; font-size: 10px; }
        table.data tr:nth-child(even) td { background: #faf8f5; }
        table.data tfoot td { background: #f0ebe5; font-weight: bold; color: #2d5a27; border-top: 2px solid #2d5a27; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; text-align: center; color: #adb5bd; font-size: 9px; }
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 8px; font-size: 9px; font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>La Réserve Naturelle — Rapport {{ ucwords(str_replace('-', ' ', $onglet)) }}</h1>
        <div class="meta">
            Période du {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
            &nbsp;•&nbsp; Généré le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    @if(in_array($onglet, ['vue-ensemble', 'mouvements']))
        <table class="data">
            <thead>
                <tr>
                    <th>Date</th><th>Produit</th><th>Type</th><th class="text-right">Qté</th>
                    <th class="text-right">Prix unit.</th><th class="text-right">Valeur</th><th>Référence</th><th>Utilisateur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $m)
                    @php
                        $reference = match(true) {
                            $m->source instanceof \App\Models\Achat => $m->source->numero,
                            $m->source instanceof \App\Models\Order => $m->source->num_order,
                            $m->source instanceof \App\Models\Inventaire => $m->source->reference,
                            default => '—',
                        };
                        $estEntree = $m->sens === 'entree';
                    @endphp
                    <tr>
                        <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $m->product->designation ?? '—' }}</td>
                        <td>{{ $estEntree ? 'Entrée' : 'Sortie' }} ({{ ucfirst($m->type) }})</td>
                        <td class="text-right">{{ $m->quantite }}</td>
                        <td class="text-right">{{ number_format($m->cmp_apres, 0, ',', ' ') }}</td>
                        <td class="text-right">{{ number_format($m->quantite * $m->cmp_apres, 0, ',', ' ') }}</td>
                        <td>{{ $reference }}</td>
                        <td>{{ $m->user->prenom ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">Aucun mouvement sur cette période</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><td colspan="5" class="text-right">Total</td><td class="text-right">{{ number_format($mouvementsTotal, 0, ',', ' ') }} FCFA</td><td colspan="2"></td></tr>
            </tfoot>
        </table>
    @endif

    @if($onglet == 'produits')
        <table class="data">
            <thead>
                <tr>
                    <th>Produit</th><th>Catégorie</th><th class="text-right">Qté dispo</th>
                    <th class="text-right">Seuil min</th><th class="text-right">CMP</th><th class="text-right">Valeur stock</th><th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $p)
                    <tr>
                        <td>{{ $p->designation }}</td>
                        <td>{{ $p->category->nom ?? '—' }}</td>
                        <td class="text-right">{{ $p->qte_dispo }}</td>
                        <td class="text-right">{{ $p->stock_minimum }}</td>
                        <td class="text-right">{{ number_format($p->cmp, 0, ',', ' ') }}</td>
                        <td class="text-right">{{ number_format($p->qte_dispo * $p->cmp, 0, ',', ' ') }}</td>
                        <td>{{ $p->qte_dispo <= 0 ? 'Rupture' : ($p->sous_seuil ? 'Faible' : 'OK') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Aucun produit</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><td colspan="5" class="text-right">Total</td><td class="text-right">{{ number_format($produitsTotal, 0, ',', ' ') }} FCFA</td><td></td></tr>
            </tfoot>
        </table>
    @endif

    @if($onglet == 'achats')
        <table class="data">
            <thead>
                <tr>
                    <th>N°</th><th>Fournisseur</th><th>Date</th><th>Statut</th>
                    <th class="text-right">Montant total</th><th class="text-right">Montant payé</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achats as $a)
                    <tr>
                        <td>{{ $a->numero }}</td>
                        <td>{{ $a->fournisseur->full_name ?? '—' }}</td>
                        <td>{{ $a->date_achat?->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($a->statut) }}</td>
                        <td class="text-right">{{ number_format($a->mt_total, 0, ',', ' ') }}</td>
                        <td class="text-right">{{ number_format($a->mt_paye, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Aucun achat sur cette période</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><td colspan="4" class="text-right">Total</td><td class="text-right">{{ number_format($achatsTotal, 0, ',', ' ') }} FCFA</td><td></td></tr>
            </tfoot>
        </table>
    @endif

    @if($onglet == 'fournisseurs')
        <table class="data">
            <thead>
                <tr><th>Fournisseur</th><th>Ville</th><th class="text-right">Nb achats</th><th class="text-right">Total achats</th></tr>
            </thead>
            <tbody>
                @forelse($fournisseurs as $f)
                    <tr>
                        <td>{{ $f->full_name }}</td>
                        <td>{{ $f->ville }}</td>
                        <td class="text-right">{{ $f->achats_count }}</td>
                        <td class="text-right">{{ number_format($f->achats_sum_mt_total ?? 0, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Aucun fournisseur</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><td colspan="3" class="text-right">Total</td><td class="text-right">{{ number_format($fournisseursTotal, 0, ',', ' ') }} FCFA</td></tr>
            </tfoot>
        </table>
    @endif

    @if($onglet == 'users')
        <table class="data">
            <thead>
                <tr><th>Utilisateur</th><th>Email</th><th class="text-right">Nb commandes</th><th class="text-right">Total dépensé</th></tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>{{ $u->prenom }} {{ $u->nom }}</td>
                        <td>{{ $u->email }}</td>
                        <td class="text-right">{{ $u->orders_count }}</td>
                        <td class="text-right">{{ number_format($u->orders_sum_montant_ttc ?? 0, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Aucun utilisateur</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><td colspan="3" class="text-right">Total</td><td class="text-right">{{ number_format($usersTotal, 0, ',', ' ') }} FCFA</td></tr>
            </tfoot>
        </table>
    @endif

    @if($onglet == 'ventes')
        <table class="data">
            <thead>
                <tr><th>N° commande</th><th>Date</th><th>Client</th><th>Zone</th><th>Statut</th><th class="text-right">Montant TTC</th></tr>
            </thead>
            <tbody>
                @forelse($ventes as $v)
                    <tr>
                        <td>{{ $v->num_order }}</td>
                        <td>{{ $v->date_order?->format('d/m/Y') }}</td>
                        <td>{{ $v->user->prenom ?? '—' }}</td>
                        <td>{{ $v->zone->nom ?? '—' }}</td>
                        <td>{{ ucfirst($v->statut) }}</td>
                        <td class="text-right">{{ number_format($v->montant_ttc, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Aucune vente sur cette période</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><td colspan="5" class="text-right">Total</td><td class="text-right">{{ number_format($ventesTotal, 0, ',', ' ') }} FCFA</td></tr>
            </tfoot>
        </table>
    @endif
    <div class="footer">La Réserve Naturelle — Rapport généré automatiquement</div>
</body>
</html>