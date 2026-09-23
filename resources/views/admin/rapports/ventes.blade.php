{{-- resources/views/admin/rapports/ventes.blade.php --}}
@extends('admin.rapports._layout')

@section('tableau')
    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
        <i class="fas fa-shopping-cart" style="color: #b8860b;"></i> Total des ventes de la période
    </h3>

    {{-- Filtres --}}
    <form action="{{ route('admin.rapports.stock', ['onglet' => 'ventes']) }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; padding: 0 25px 20px;">
        <input type="hidden" name="date_from" value="{{ $dateFrom }}">
        <input type="hidden" name="date_to" value="{{ $dateTo }}">
        <div style="flex: 1; min-width: 160px;">
            <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.85rem; margin-bottom: 5px;">N° commande</label>
            <input type="text" name="num_order" value="{{ request('num_order') }}" placeholder="Ex: CMD-0012" style="width: 100%; padding: 9px 14px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.9rem; outline: none;">
        </div>
        <div style="flex: 1; min-width: 160px;">
            <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.85rem; margin-bottom: 5px;">Statut</label>
            <select name="statut" style="width: 100%; padding: 9px 14px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.9rem; background: white;">
                <option value="">Tous statuts</option>
                @foreach($statutsVentes as $s)
                    <option value="{{ $s }}" @selected(request('statut') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1; min-width: 160px;">
            <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.85rem; margin-bottom: 5px;">Zone</label>
            <select name="zone_id" style="width: 100%; padding: 9px 14px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.9rem; background: white;">
                <option value="">Toutes zones</option>
                @foreach($zones as $z)
                    <option value="{{ $z->id }}" @selected((int) request('zone_id') === $z->id)>{{ $z->nom }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" style="background: #2d5a27; color: white; padding: 9px 22px; border-radius: 30px; border: none; font-weight: 500; cursor: pointer;">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            <a href="{{ route('admin.rapports.stock', ['onglet' => 'ventes', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="background: #e8e0d5; color: #2d5a27; padding: 9px 22px; border-radius: 30px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-undo"></i>
            </a>
        </div>
    </form>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                <tr>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">N° commande</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Date</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Client</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Zone</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Statut</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Montant TTC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $v)
                    <tr style="border-bottom: 1px solid #f0ebe5;">
                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $v->num_order }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $v->date_order?->format('d/m/Y') }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $v->user->prenom ?? '—' }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $v->zone->nom ?? '—' }}</td>
                        <td style="padding: 12px 20px;">
                            <span style="
                                background: {{ $v->statut === 'livree' ? '#e8f5e9' : '#fff3e0' }};
                                color: {{ $v->statut === 'livree' ? '#2e7d32' : '#e65100' }};
                                padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; text-transform: capitalize;
                            ">{{ $v->statut }}</span>
                        </td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($v->montant_ttc, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucune vente sur cette période</td></tr>
                @endforelse
            </tbody>
            @if($ventes->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                        <td colspan="5" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($ventesTotal, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <div style="padding: 15px 25px;">{{ $ventes->links() }}</div>
@endsection