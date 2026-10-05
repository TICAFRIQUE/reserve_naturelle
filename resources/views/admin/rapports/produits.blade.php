{{-- resources/views/admin/rapports/produits.blade.php --}}
@extends('admin.rapports._layout')

@section('tableau')
    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
        <i class="fas fa-boxes" style="color: #b8860b;"></i> État des variantes
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                <tr>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Variante</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Catégorie</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Qté dispo</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Seuil min</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">CMP</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Valeur stock</th>
                    <th style="padding: 12px 20px; text-align: center; color: #2d5a27;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $v)
                    <tr style="border-bottom: 1px solid #f0ebe5;">
                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">
                            {{ $v->reference_prod }} — {{ $v->product->designation }}
                            <span style="color:#6c757d; font-size:0.85rem;">({{ $v->conditionnement }})</span>
                        </td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $v->product->category->nom ?? '—' }}</td>
                        <td style="padding: 12px 20px; text-align: right;">{{ $v->qte_dispo }}</td>
                        <td style="padding: 12px 20px; text-align: right; color: #6c757d;">{{ $v->stock_minimum }}</td>
                        <td style="padding: 12px 20px; text-align: right;">{{ number_format($v->cmp, 0, ',', ' ') }}</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($v->qte_dispo * $v->cmp, 0, ',', ' ') }}</td>
                        <td style="padding: 12px 20px; text-align: center;">
                            @if($v->qte_dispo <= 0)
                                <span style="background: #fdecea; color: #c62828; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">Rupture</span>
                            @elseif($v->sous_seuil)
                                <span style="background: #fff3e0; color: #e65100; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">Faible</span>
                            @else
                                <span style="background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">OK</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucune variante</td></tr>
                @endforelse
            </tbody>
            @if($variants->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                        <td colspan="5" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($variantsTotal, 0, ',', ' ') }} FCFA</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <div style="padding: 15px 25px;">{{ $variants->links() }}</div>
@endsection