{{-- resources/views/admin/rapports/fournisseurs.blade.php --}}
@extends('admin.rapports._layout')

@section('tableau')
    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
        <i class="fas fa-truck" style="color: #b8860b;"></i> Fournisseurs — activité sur la période
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                <tr>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Fournisseur</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Ville</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Nb achats</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Total achats</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fournisseurs as $f)
                    <tr style="border-bottom: 1px solid #f0ebe5;">
                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $f->full_name }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $f->ville }}</td>
                        <td style="padding: 12px 20px; text-align: right;">{{ $f->achats_count }}</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($f->achats_sum_mt_total ?? 0, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun fournisseur</td></tr>
                @endforelse
            </tbody>
            @if($fournisseurs->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                        <td colspan="3" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($fournisseursTotal, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <div style="padding: 15px 25px;">{{ $fournisseurs->links() }}</div>
@endsection