{{-- resources/views/admin/rapports/achats.blade.php --}}
@extends('admin.rapports._layout')

@section('tableau')
    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
        <i class="fas fa-shopping-basket" style="color: #b8860b;"></i> Achats de la période
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                <tr>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">N°</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Fournisseur</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Date</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Statut</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Montant total</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Montant payé</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achats as $a)
                    <tr style="border-bottom: 1px solid #f0ebe5;">
                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $a->numero }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $a->fournisseur->full_name ?? '—' }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $a->date_achat?->format('d/m/Y') }}</td>
                        <td style="padding: 12px 20px; text-transform: capitalize;">{{ $a->statut }}</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($a->mt_total, 0, ',', ' ') }}</td>
                        <td style="padding: 12px 20px; text-align: right; color: #6c757d;">{{ number_format($a->mt_paye, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun achat sur cette période</td></tr>
                @endforelse
            </tbody>
            @if($achats->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                        <td colspan="4" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($achatsTotal, 0, ',', ' ') }} FCFA</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <div style="padding: 15px 25px;">{{ $achats->links() }}</div>
@endsection