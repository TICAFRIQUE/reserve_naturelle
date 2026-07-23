@extends('layouts.admin')

@section('title', 'Détails du Fournisseur - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et boutons -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-user-circle" style="color: #2d5a27; margin-right: 10px;"></i>
                        Détails du fournisseur
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-truck" style="color: #2d5a27; margin-right: 5px;"></i>
                        Consultation des informations du fournisseur
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.fournisseurs.edit', $fournisseur->id) }}" style="
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
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('admin.fournisseurs.index') }}" style="
                        background: #e8e0d5;
                        color: #2d5a27;
                        padding: 12px 24px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <!-- Messages flash -->
            @if(session('success'))
                <div style="
                    background: #d4edda;
                    color: #155724;
                    padding: 12px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #28a745;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="
                    background: #f8d7da;
                    color: #721c24;
                    padding: 12px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #dc3545;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informations du fournisseur -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
                <!-- Carte informations personnelles -->
                <div style="
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    overflow: hidden;
                ">
                    <div style="
                        padding: 20px 25px;
                        border-bottom: 1px solid #e8e0d5;
                        background: #faf8f5;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    ">
                        <div style="
                            width: 45px;
                            height: 45px;
                            border-radius: 50%;
                            background: #2d5a27;
                            color: white;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: bold;
                            font-size: 16px;
                        ">
                            {{ strtoupper(substr($fournisseur->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($fournisseur->nom ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                                Informations personnelles
                            </h5>
                            <small style="color: #6c757d;">{{ $fournisseur->full_name }}</small>
                        </div>
                    </div>
                    <div style="padding: 20px 25px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27; width: 130px;">Nom complet</td>
                                <td style="padding: 8px 0; color: #2d5a27; font-weight: 500;">{{ $fournisseur->full_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-phone" style="color: #2d5a27; margin-right: 5px;"></i> Téléphone
                                </td>
                                <td style="padding: 8px 0;">
                                    <a href="tel:{{ $fournisseur->tel }}" style="color: #2d5a27; text-decoration: none;">
                                        {{ $fournisseur->tel }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-map-marker-alt" style="color: #2d5a27; margin-right: 5px;"></i> Adresse
                                </td>
                                <td style="padding: 8px 0; color: #6c757d;">{{ $fournisseur->adress }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-city" style="color: #2d5a27; margin-right: 5px;"></i> Ville
                                </td>
                                <td style="padding: 8px 0;">
                                    <span style="
                                        background: #e8f5e9;
                                        color: #2d5a27;
                                        padding: 4px 14px;
                                        border-radius: 20px;
                                        font-size: 0.85rem;
                                        display: inline-block;
                                    ">
                                        <i class="fas fa-map-pin"></i> {{ $fournisseur->ville }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-calendar-plus" style="color: #2d5a27; margin-right: 5px;"></i> Date d'ajout
                                </td>
                                <td style="padding: 8px 0; color: #6c757d;">
                                    @if($fournisseur->date_ajout instanceof \Carbon\Carbon)
                                        {{ $fournisseur->date_ajout->format('d/m/Y') }}
                                    @else
                                        {{ date('d/m/Y', strtotime($fournisseur->date_ajout)) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-clock" style="color: #2d5a27; margin-right: 5px;"></i> Créé le
                                </td>
                                <td style="padding: 8px 0; color: #6c757d;">
                                    @if($fournisseur->created_at instanceof \Carbon\Carbon)
                                        {{ $fournisseur->created_at->format('d/m/Y à H:i') }}
                                    @else
                                        {{ date('d/m/Y à H:i', strtotime($fournisseur->created_at)) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-edit" style="color: #2d5a27; margin-right: 5px;"></i> Modifié le
                                </td>
                                <td style="padding: 8px 0; color: #6c757d;">
                                    @if($fournisseur->updated_at instanceof \Carbon\Carbon)
                                        {{ $fournisseur->updated_at->format('d/m/Y à H:i') }}
                                    @else
                                        {{ date('d/m/Y à H:i', strtotime($fournisseur->updated_at)) }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Carte historique des achats -->
                <div style="
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    overflow: hidden;
                ">
                    <div style="
                        padding: 20px 25px;
                        border-bottom: 1px solid #e8e0d5;
                        background: #faf8f5;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">
                        <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                            <i class="fas fa-shopping-cart" style="color: #2d5a27; margin-right: 8px;"></i>
                            Historique des achats
                        </h5>
                        <span style="
                            background: #2d5a27;
                            color: white;
                            padding: 4px 16px;
                            border-radius: 20px;
                            font-size: 0.85rem;
                            font-weight: 500;
                        ">
                            {{ $fournisseur->achats()->count() }} achat(s)
                        </span>
                    </div>
                    <div style="padding: 0; overflow-x: auto;">
                        @if($fournisseur->achats()->count() > 0)
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                                <thead style="background: #f8f5f0; border-bottom: 1px solid #e8e0d5;">
                                    <tr>
                                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 20%;">#</th>
                                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 30%;">Date</th>
                                        <th style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27; width: 25%;">Montant</th>
                                        <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 25%;">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fournisseur->achats as $achat)
                                        <tr style="border-bottom: 1px solid #f0ebe5;">
                                            <td style="padding: 12px 20px; color: #6c757d;">#{{ $achat->id }}</td>
                                            <td style="padding: 12px 20px; color: #2d5a27;">
                                                @if($achat->created_at instanceof \Carbon\Carbon)
                                                    {{ $achat->created_at->format('d/m/Y') }}
                                                @else
                                                    {{ date('d/m/Y', strtotime($achat->created_at)) }}
                                                @endif
                                            </td>
                                            <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">
                                                {{ number_format($achat->montant ?? 0, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td style="padding: 12px 20px; text-align: center;">
                                                <span style="
                                                    background: #d4edda;
                                                    color: #155724;
                                                    padding: 4px 14px;
                                                    border-radius: 20px;
                                                    font-size: 0.8rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-check-circle"></i> Terminé
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                <i class="fas fa-shopping-cart" style="font-size: 36px; display: block; margin-bottom: 12px; color: #d4c9bb;"></i>
                                <p style="font-size: 1.05rem; margin: 0;">Aucun achat enregistré</p>
                                <p style="font-size: 0.9rem; margin-top: 5px;">Ce fournisseur n'a pas encore effectué d'achat</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Zone de danger (suppression) -->
            <div style="
                background: white;
                border-radius: 12px;
                border: 1px solid #f8d7da;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                overflow: hidden;
            ">
                <div style="
                    padding: 20px 25px;
                    background: #fce4ec;
                    border-bottom: 1px solid #f8d7da;
                ">
                    <h5 style="color: #c62828; margin: 0; font-size: 1.1rem;">
                        <i class="fas fa-exclamation-triangle"></i> Zone de danger
                    </h5>
                </div>
                <div style="padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <p style="color: #721c24; margin: 0; font-weight: 500;">
                            <i class="fas fa-trash-alt" style="color: #dc3545;"></i>
                            Supprimer ce fournisseur
                        </p>
                        <small style="color: #6c757d;">La suppression est irréversible et supprime tous les achats associés</small>
                    </div>
                    <form action="{{ route('admin.fournisseurs.destroy', $fournisseur->id) }}" 
                          method="POST" 
                          style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                style="
                                    background: #dc3545;
                                    color: white;
                                    padding: 10px 30px;
                                    border-radius: 30px;
                                    border: none;
                                    font-weight: 500;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 8px;
                                "
                                onmouseover="this.style.background='#c82333'"
                                onmouseout="this.style.background='#dc3545'"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce fournisseur ? Cette action est irréversible.');">
                            <i class="fas fa-trash-alt"></i> Supprimer ce fournisseur
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 20px 15px !important;
        }
        .container > div > div:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .container > div > div:first-child > div:last-child {
            width: 100%;
        }
        .container > div > div:first-child > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        .container > div > div:nth-child(3) {
            grid-template-columns: 1fr !important;
        }
        .container > div > div:last-child > div:last-child {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .container > div > div:last-child > div:last-child form {
            width: 100%;
        }
        .container > div > div:last-child > div:last-child button {
            width: 100%;
            justify-content: center;
        }
        table {
            font-size: 0.85rem !important;
        }
        th, td {
            padding: 8px 12px !important;
        }
        .container > div > div:nth-child(3) > div:first-child > div:last-child table td {
            padding: 6px 0 !important;
        }
    }
</style>
@endpush