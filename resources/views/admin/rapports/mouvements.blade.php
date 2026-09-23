{{-- resources/views/admin/rapports/mouvements.blade.php --}}
@extends('admin.rapports._layout')

@section('tableau')
    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
        <i class="fas fa-list" style="color: #b8860b;"></i> Détail des mouvements de stock
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                <tr>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Date</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Produit</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Type</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Quantité</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Prix unitaire</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Valeur</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Référence</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Utilisateur</th>
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
                    <tr style="border-bottom: 1px solid #f0ebe5;">
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $m->product->designation ?? '—' }}</td>
                        <td style="padding: 12px 20px;">
                            <span style="
                                background: {{ $estEntree ? '#e8f5e9' : '#e3f2fd' }};
                                color: {{ $estEntree ? '#2e7d32' : '#1565c0' }};
                                padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;
                            ">{{ $estEntree ? 'Entrée' : 'Sortie' }} ({{ ucfirst($m->type) }})</span>
                        </td>
                        <td style="padding: 12px 20px; text-align: right;">{{ $m->quantite }}</td>
                        <td style="padding: 12px 20px; text-align: right;">{{ number_format($m->cmp_apres, 0, ',', ' ') }}</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($m->quantite * $m->cmp_apres, 0, ',', ' ') }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $reference }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $m->user->prenom ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun mouvement sur cette période</td></tr>
                @endforelse
            </tbody>
            @if($mouvements->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                        <td colspan="5" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($mouvementsTotal, 0, ',', ' ') }} FCFA</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    @if($mouvements instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding: 15px 25px;">{{ $mouvements->links() }}</div>
    @endif
@endsection