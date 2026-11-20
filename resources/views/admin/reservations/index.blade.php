@extends('layouts.admin')

@section('title', 'Réservations - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
        <h1 style="font-family:'Playfair Display',serif; color:#2d5a27; margin:0;">
            <i class="fas fa-clock"></i> Gestion des réservations
        </h1>
        <a href="{{ route('admin.reservations.create') }}"
        style="background:#2d5a27; color:#fff; padding:10px 24px; border-radius:30px; text-decoration:none; font-weight:500;">
            <i class="fas fa-plus"></i> Nouvelle réservation
        </a>
    </div>

    @if(session('success')) <div style="background:#d4edda; color:#155724; padding:12px 20px; border-radius:8px; margin:15px 0;">{{ session('success') }}</div> @endif
    @if(session('error'))   <div style="background:#f8d7da; color:#721c24; padding:12px 20px; border-radius:8px; margin:15px 0;">{{ session('error') }}</div> @endif

    <form method="GET" action="{{ route('admin.reservations.index') }}"
          style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; background:#f8f5f0; padding:16px; border-radius:12px; margin:20px 0;">
        <select name="statut" style="padding:10px; border:1px solid #e8e0d5; border-radius:8px;">
            <option value="">Tous les statuts</option>
            @foreach(['validee' => 'Validée', 'payee' => 'Payée', 'livree' => 'Livrée', 'annulee' => 'Annulée'] as $v => $l)
                <option value="{{ $v }}" {{ request('statut') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <input type="date" name="date_debut" value="{{ request('date_debut') }}" style="padding:10px; border:1px solid #e8e0d5; border-radius:8px;">
        <input type="date" name="date_fin" value="{{ request('date_fin') }}" style="padding:10px; border:1px solid #e8e0d5; border-radius:8px;">
        <button type="submit" style="background:#2d5a27; color:#fff; border:none; padding:10px 24px; border-radius:30px; cursor:pointer;">
            <i class="fas fa-filter"></i> Filtrer
        </button>
        <a href="{{ route('admin.reservations.index') }}" style="background:#e8e0d5; color:#2d5a27; padding:10px 24px; border-radius:30px; text-decoration:none;">Réinitialiser</a>
    </form>

    @php
        $cfg = [
            'validee' => ['Validée', '#cce5ff', '#004085'],
            'payee'   => ['Payée',   '#f3e5f5', '#6a1b9a'],
            'livree'  => ['Livrée',  '#d4edda', '#155724'],
            'annulee' => ['Annulée', '#f8d7da', '#721c24'],
        ];
    @endphp

    <div style="background:#fff; border:1px solid #e8e0d5; border-radius:12px; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead style="background:#f8f5f0;">
                <tr style="color:#2d5a27; text-align:left;">
                    <th style="padding:14px;">N°</th>
                    <th style="padding:14px;">Client</th>
                    <th style="padding:14px;">Produit</th>
                    <th style="padding:14px; text-align:center;">Qté</th>
                    <th style="padding:14px; text-align:right;">Montant</th>
                    <th style="padding:14px;">Date</th>
                    <th style="padding:14px; text-align:center;">Statut</th>
                    <th style="padding:14px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                    @php [$label, $bg, $color] = $cfg[$r->statut] ?? [$r->statut, '#e8e0d5', '#6c757d']; @endphp
                    <tr style="border-top:1px solid #f0ebe5;">
                        <td style="padding:14px;"><strong>{{ $r->num_reservation }}</strong></td>
                        <td style="padding:14px;">
                            {{ $r->user->prenom ?? $r->user->name ?? 'N/A' }}<br>
                            <small style="color:#6c757d;">{{ $r->user->email ?? '' }}</small>
                        </td>
                        <td style="padding:14px;">
                            {{ $r->variant?->libelle ?? 'Produit supprimé' }}
                            @if($r->note)<br><small style="color:#6c757d;">{{ $r->note }}</small>@endif
                            @if($r->variant)
                                <br><small style="color:{{ $r->variant->qte_dispo >= $r->qte ? '#155724' : '#721c24' }};">
                                    Stock actuel : {{ $r->variant->qte_dispo }}
                                </small>
                            @endif
                        </td>
                        <td style="padding:14px; text-align:center;">{{ $r->qte }}</td>
                        <td style="padding:14px; text-align:right;">{{ number_format($r->montant, 0, ',', ' ') }} FCFA</td>
                        <td style="padding:14px;">{{ $r->date_reservation->format('d/m/Y') }}</td>
                        <td style="padding:14px; text-align:center;">
                            <span style="background:{{ $bg }}; color:{{ $color }}; padding:6px 14px; border-radius:20px; font-size:0.8rem;">{{ $label }}</span>
                        </td>
                        <td style="padding:14px;">
                            {{-- Conteneur flex pour aligner les boutons --}}
                            <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                                <a href="{{ route('admin.reservations.show', $r) }}"
                                   style="background:#f0ebe5; color:#2d5a27; padding:6px 12px; border-radius:20px; text-decoration:none; font-size:0.85rem;">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                @if($r->statut !== 'annulee')
                                    <a href="{{ route('admin.reservations.receipt', $r) }}"
                                       style="background:#cce5ff; color:#004085; padding:6px 12px; border-radius:20px; text-decoration:none; font-size:0.85rem;">
                                        <i class="fas fa-file-pdf"></i> Reçu
                                    </a>
                                @endif
                                {{-- @php
                                    $options = match($r->statut) {
                                        'validee' => ['payee' => 'Payée', 'livree' => 'Livrée', 'annulee' => 'Annulée'],
                                        'payee'   => ['livree' => 'Livrée', 'annulee' => 'Annulée'],
                                        default   => [],
                                    };
                                @endphp
                                @if($options)
                                    <form method="POST" action="{{ route('admin.reservations.status', $r) }}" style="display:flex; gap:6px; margin:0;">
                                        @csrf @method('PATCH')
                                        <select name="statut" required style="padding:6px; border:1px solid #e8e0d5; border-radius:8px; font-size:0.85rem;">
                                            @foreach($options as $v => $l)
                                                <option value="{{ $v }}">{{ $l }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" style="background:#2d5a27; color:#fff; border:none; padding:6px 14px; border-radius:20px; cursor:pointer; font-size:0.85rem;">OK</button>
                                    </form>
                                @else
                                    —
                                @endif --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="padding:50px; text-align:center; color:#6c757d;">Aucune réservation trouvée</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:25px;">{{ $reservations->links() }}</div>
</div>
@endsection