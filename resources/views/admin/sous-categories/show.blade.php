@extends('layouts.admin')

@section('title', 'Détails de la sous-catégorie - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et boutons -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-folder-open" style="color: #2d5a27; margin-right: 10px;"></i>
                        Détails de la sous-catégorie
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i>
                        Consultation des informations de la sous-catégorie
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.sous-categories.edit', $sousCategory->id) }}" style="
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
                    <a href="{{ route('admin.sous-categories.index') }}" style="
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

            <!-- Carte d'informations -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                overflow: hidden;
                max-width: 800px;
                margin: 0 auto;
            ">
                <!-- En-tête de la carte -->
                <div style="
                    padding: 20px 25px;
                    border-bottom: 1px solid #e8e0d5;
                    background: #faf8f5;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                ">
                    <div style="
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: #2d5a27;
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 20px;
                    ">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div>
                        <h4 style="color: #2d5a27; margin: 0; font-size: 1.2rem;">
                            {{ $sousCategory->nom }}
                        </h4>
                        <span style="color: #6c757d; font-size: 0.85rem;">
                            <i class="fas fa-folder" style="color: #2d5a27;"></i>
                            {{ $sousCategory->category->nom ?? 'Non rattachée' }}
                        </span>
                    </div>
                    <div style="margin-left: auto;">
                        @if($sousCategory->statut == 'actif' || $sousCategory->statut == 1)
                            <span style="
                                background: #d4edda;
                                color: #155724;
                                padding: 6px 18px;
                                border-radius: 20px;
                                font-size: 0.85rem;
                                font-weight: 500;
                                display: inline-block;
                            ">
                                <i class="fas fa-circle" style="font-size: 8px; color: #28a745;"></i>
                                Actif
                            </span>
                        @else
                            <span style="
                                background: #f8d7da;
                                color: #721c24;
                                padding: 6px 18px;
                                border-radius: 20px;
                                font-size: 0.85rem;
                                font-weight: 500;
                                display: inline-block;
                            ">
                                <i class="fas fa-circle" style="font-size: 8px; color: #dc3545;"></i>
                                Inactif
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Corps de la carte -->
                <div style="padding: 25px 30px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27; width: 30%;">
                                <i class="fas fa-hashtag" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                ID
                            </td>
                            <td style="padding: 10px 0; color: #6c757d;">#{{ $sousCategory->id }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-tag" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Nom
                            </td>
                            <td style="padding: 10px 0; color: #2d5a27; font-weight: 500;">{{ $sousCategory->nom }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-folder" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Catégorie parente
                            </td>
                            <td style="padding: 10px 0;">
                                <span style="
                                    background: #e8f5e9;
                                    color: #2d5a27;
                                    padding: 4px 14px;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    display: inline-block;
                                ">
                                    <i class="fas fa-folder"></i>
                                    {{ $sousCategory->category->nom ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-toggle-on" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Statut
                            </td>
                            <td style="padding: 10px 0;">
                                @if($sousCategory->statut == 'actif' || $sousCategory->statut == 1)
                                    <span style="
                                        background: #d4edda;
                                        color: #155724;
                                        padding: 4px 14px;
                                        border-radius: 20px;
                                        font-size: 0.85rem;
                                        font-weight: 500;
                                        display: inline-block;
                                    ">
                                        <i class="fas fa-check-circle"></i> Actif
                                    </span>
                                @else
                                    <span style="
                                        background: #f8d7da;
                                        color: #721c24;
                                        padding: 4px 14px;
                                        border-radius: 20px;
                                        font-size: 0.85rem;
                                        font-weight: 500;
                                        display: inline-block;
                                    ">
                                        <i class="fas fa-times-circle"></i> Inactif
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-sort-numeric-down" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Ordre
                            </td>
                            <td style="padding: 10px 0; color: #2d5a27; font-weight: 500;">{{ $sousCategory->ordre ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-align-left" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Description
                            </td>
                            <td style="padding: 10px 0; color: #6c757d;">
                                {{ $sousCategory->description ?? 'Aucune description disponible' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-calendar-plus" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Créé le
                            </td>
                            <td style="padding: 10px 0; color: #6c757d;">{{ $sousCategory->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-clock" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Modifié le
                            </td>
                            <td style="padding: 10px 0; color: #6c757d;">{{ $sousCategory->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Pied de carte avec actions -->
                <div style="
                    padding: 15px 25px;
                    border-top: 1px solid #e8e0d5;
                    background: #faf8f5;
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                ">
                    <a href="{{ route('admin.sous-categories.edit', $sousCategory->id) }}" style="
                        background: #2d5a27;
                        color: white;
                        padding: 10px 25px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 0.95rem;
                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('admin.sous-categories.index') }}" style="
                        background: #e8e0d5;
                        color: #2d5a27;
                        padding: 10px 25px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 0.95rem;
                    " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
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
            flex: 1;
            justify-content: center;
        }
        .container > div > div:last-child {
            margin: 0 !important;
        }
        .container > div > div:last-child > div:first-child {
            flex-wrap: wrap !important;
        }
        .container > div > div:last-child > div:last-child table td {
            padding: 8px 0 !important;
        }
        .container > div > div:last-child > div:last-child {
            padding: 20px !important;
        }
        .container > div > div:last-child > div:last-child table td:first-child {
            width: 40% !important;
        }
        .container > div > div:last-child > div:last-child table td:last-child {
            word-break: break-word;
        }
    }
</style>
@endpush