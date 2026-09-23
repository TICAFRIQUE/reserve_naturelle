{{-- resources/views/admin/rapports/vue-ensemble.blade.php --}}
@extends('admin.rapports._layout_principal')

@section('tableau')
    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
        <i class="fas fa-list" style="color: #b8860b;"></i> Achats, ventes et dépenses de la période
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                <tr>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Date</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Libellé</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Achats</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Ventes</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Dépenses</th>
                    <th style="padding: 12px 20px; text-align: center; color: #2d5a27;">Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvementsVue as $m)
                    @php
                        $badge = match($m->type) {
                            'Achat'   => ['bg' => '#e3f2fd', 'fg' => '#1565c0'],
                            'Vente'   => ['bg' => '#fff3e0', 'fg' => '#e65100'],
                            'Dépense' => ['bg' => '#f8d7da', 'fg' => '#721c24'],
                        };
                    @endphp
                    <tr style="border-bottom: 1px solid #f0ebe5;">
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $m->date?->format('d/m/Y') }}</td>
                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $m->libelle }}</td>
                        <td style="padding: 12px 20px; text-align: right; color: #1565c0;">{{ $m->montant_achat !== null ? number_format($m->montant_achat, 0, ',', ' ') . ' FCFA' : '—' }}</td>
                        <td style="padding: 12px 20px; text-align: right; color: #e65100;">{{ $m->montant_vente !== null ? number_format($m->montant_vente, 0, ',', ' ') . ' FCFA' : '—' }}</td>
                        <td style="padding: 12px 20px; text-align: right; color: #721c24;">{{ $m->montant_depense !== null ? number_format($m->montant_depense, 0, ',', ' ') . ' FCFA' : '—' }}</td>
                        <td style="padding: 12px 20px; text-align: center;">
                            <span style="background: {{ $badge['bg'] }}; color: {{ $badge['fg'] }}; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">{{ $m->type }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucune donnée sur cette période</td></tr>
                @endforelse
            </tbody>
            @if($mouvementsVue->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                        <td colspan="2" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #1565c0;">{{ number_format($vueEnsembleTotal['achats'], 0, ',', ' ') }} FCFA</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #e65100;">{{ number_format($vueEnsembleTotal['ventes'], 0, ',', ' ') }} FCFA</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #721c24;">{{ number_format($vueEnsembleTotal['depenses'], 0, ',', ' ') }} FCFA</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
     @if($mouvementsVue instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding: 15px 25px;">{{ $mouvementsVue->links() }}</div>
    @endif
@endsection