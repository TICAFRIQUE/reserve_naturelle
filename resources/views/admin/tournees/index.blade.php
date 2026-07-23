@extends('layouts.admin')

@section('title', 'Gestion des Tournées - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-route" style="color: #2d5a27; margin-right: 10px;"></i>
                        Gestion des tournées
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Organisez les livraisons par zone et livreur</p>
                </div>
                <a href="{{ route('admin.tournees.create') }}" style="
                    background: #2d5a27;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                    <i class="fas fa-plus"></i> Nouvelle tournée
                </a>
            </div>

            <!-- Messages flash -->
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #28a745; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tableau -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5;">
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 0.95rem;">
                    <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                        <tr>
                            <th style="padding: 12px 10px; text-align: left; font-weight: 600; color: #2d5a27; width: 5%;">#</th>
                            <th style="padding: 12px 10px; text-align: left; font-weight: 600; color: #2d5a27; width: 20%;">Livreur</th>
                            <th style="padding: 12px 10px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Zone</th>
                            <th style="padding: 12px 10px; text-align: left; font-weight: 600; color: #2d5a27; width: 13%;">Date</th>
                            <th style="padding: 12px 10px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Commandes</th>
                            <th style="padding: 12px 10px; text-align: center; font-weight: 600; color: #2d5a27; width: 13%;">Statut</th>
                            <th style="padding: 12px 10px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tournees as $tournee)
                            <tr style="border-bottom: 1px solid #f0ebe5;">
                                <td style="padding: 12px 10px; color: #6c757d;">{{ $tournee->id }}</td>
                                <td style="padding: 12px 10px; color: #2d5a27; font-weight: 500;">
                                    {{ $tournee->livreur->full_name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px 10px; color: #2d5a27;">
                                    {{ $tournee->zone->nom ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px 10px; color: #2d5a27;">
                                    {{ $tournee->date_tournee->format('d/m/Y') }}
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    <span style="background: #f8f5f0; color: #2d5a27; padding: 4px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        {{ $tournee->orders->count() }}
                                    </span>
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    @if($tournee->statut === 'en_cours')
                                        <span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                            <i class="fas fa-truck-loading" style="margin-right: 5px;"></i> En cours
                                        </span>
                                    @else
                                        <span style="background: #d4edda; color: #155724; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                            <i class="fas fa-check-circle" style="margin-right: 5px;"></i> Terminée
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    <a href="{{ route('admin.tournees.show', $tournee) }}" style="
                                        background: #f0ebe5; color: #2d5a27; padding: 8px 14px; border-radius: 20px;
                                        text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;
                                    ">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                    <i class="fas fa-route" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                    <p style="font-size: 1.1rem; margin: 0;">Aucune tournée créée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $tournees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection