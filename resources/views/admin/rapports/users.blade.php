{{-- resources/views/admin/rapports/users.blade.php --}}
@extends('admin.rapports._layout')

@section('tableau')
    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
        <i class="fas fa-users" style="color: #b8860b;"></i> Utilisateurs — activité sur la période
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                <tr>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Utilisateur</th>
                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Email</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Nb commandes</th>
                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Total dépensé</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr style="border-bottom: 1px solid #f0ebe5;">
                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $u->prenom }} {{ $u->nom }}</td>
                        <td style="padding: 12px 20px; color: #6c757d;">{{ $u->email }}</td>
                        <td style="padding: 12px 20px; text-align: right;">{{ $u->orders_count }}</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($u->orders_sum_montant_ttc ?? 0, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun utilisateur</td></tr>
                @endforelse
            </tbody>
            @if($users->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                        <td colspan="3" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($usersTotal, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <div style="padding: 15px 25px;">{{ $users->links() }}</div>
@endsection