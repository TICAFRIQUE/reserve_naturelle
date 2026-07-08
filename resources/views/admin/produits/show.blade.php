@extends('layouts.admin')

@section('title', 'Détails du produit - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et boutons -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-box" style="color: #2d5a27; margin-right: 10px;"></i>
                        Détails du produit
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i>
                        Consultation des informations du produit
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.produits.edit', $product) }}" style="
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
                    <a href="{{ route('admin.produits.index') }}" style="
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

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
                <!-- Colonne gauche -->
                <div>
                    <!-- Informations générales -->
                    <div style="
                        background: white;
                        border-radius: 12px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                        overflow: hidden;
                        margin-bottom: 25px;
                    ">
                        <div style="
                            padding: 20px 25px;
                            border-bottom: 1px solid #e8e0d5;
                            background: #faf8f5;
                        ">
                            <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                                <i class="fas fa-info-circle" style="color: #2d5a27; margin-right: 8px;"></i>
                                Informations générales
                            </h5>
                        </div>
                        <div style="padding: 20px 25px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27; width: 40%;">Référence</td>
                                            <td style="padding: 8px 0;">
                                                <span style="
                                                    background: #e8f5e9;
                                                    color: #2d5a27;
                                                    padding: 4px 14px;
                                                    border-radius: 20px;
                                                    font-size: 0.85rem;
                                                    font-weight: 600;
                                                    display: inline-block;
                                                ">
                                                    {{ $product->reference_prod }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Désignation</td>
                                            <td style="padding: 8px 0; color: #2d5a27; font-weight: 500;">{{ $product->designation }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Catégorie</td>
                                            <td style="padding: 8px 0;">
                                                <span style="
                                                    background: #f8f5f0;
                                                    color: #2d5a27;
                                                    padding: 4px 14px;
                                                    border-radius: 20px;
                                                    font-size: 0.85rem;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-folder"></i>
                                                    {{ $product->category->nom ?? 'Non catégorisé' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Prix de vente</td>
                                            <td style="padding: 8px 0;">
                                                <span style="font-size: 1.3rem; font-weight: 700; color: #2d5a27;">
                                                    {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27; width: 40%;">Stock</td>
                                            <td style="padding: 8px 0;">
                                                @php
                                                    $stockClass = $product->qte_dispo > 10 ? '#28a745' : ($product->qte_dispo > 0 ? '#ffc107' : '#dc3545');
                                                    $stockLabel = $product->qte_dispo > 10 ? 'Disponible' : ($product->qte_dispo > 0 ? 'Stock faible' : 'Rupture');
                                                @endphp
                                                <span style="
                                                    background: {{ $stockClass }};
                                                    color: white;
                                                    padding: 4px 14px;
                                                    border-radius: 20px;
                                                    font-size: 0.85rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    {{ $product->qte_dispo }} unités
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Statut</td>
                                            <td style="padding: 8px 0;">
                                                <span style="
                                                    background: #d4edda;
                                                    color: #155724;
                                                    padding: 4px 14px;
                                                    border-radius: 20px;
                                                    font-size: 0.85rem;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-check-circle"></i> Actif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Date d'ajout</td>
                                            <td style="padding: 8px 0; color: #6c757d;">{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Modifié le</td>
                                            <td style="padding: 8px 0; color: #6c757d;">{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div style="
                        background: white;
                        border-radius: 12px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                        overflow: hidden;
                        margin-bottom: 25px;
                    ">
                        <div style="
                            padding: 20px 25px;
                            border-bottom: 1px solid #e8e0d5;
                            background: #faf8f5;
                        ">
                            <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                                <i class="fas fa-align-left" style="color: #2d5a27; margin-right: 8px;"></i>
                                Description
                            </h5>
                        </div>
                        <div style="padding: 20px 25px;">
                            <p style="color: #6c757d; margin: 0; line-height: 1.8;">
                                {{ $product->description ?? 'Aucune description disponible pour ce produit.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Statistiques -->
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
                        ">
                            <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                                <i class="fas fa-chart-bar" style="color: #2d5a27; margin-right: 8px;"></i>
                                Statistiques
                            </h5>
                        </div>
                        <div style="padding: 20px 25px;">
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                                <div style="
                                    text-align: center;
                                    padding: 15px;
                                    background: #f8f5f0;
                                    border-radius: 8px;
                                ">
                                    <div style="color: #6c757d; font-size: 0.85rem;">Total vendu</div>
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #2d5a27;">{{ $totalVendu ?? 0 }}</div>
                                </div>
                                <div style="
                                    text-align: center;
                                    padding: 15px;
                                    background: #f8f5f0;
                                    border-radius: 8px;
                                ">
                                    <div style="color: #6c757d; font-size: 0.85rem;">Stock actuel</div>
                                    <div style="font-size: 1.5rem; font-weight: 700; color: {{ $product->qte_dispo > 10 ? '#28a745' : ($product->qte_dispo > 0 ? '#ffc107' : '#dc3545') }};">
                                        {{ $product->qte_dispo }}
                                    </div>
                                </div>
                                <div style="
                                    text-align: center;
                                    padding: 15px;
                                    background: #f8f5f0;
                                    border-radius: 8px;
                                ">
                                    <div style="color: #6c757d; font-size: 0.85rem;">Valeur en stock</div>
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #b8860b;">
                                        {{ number_format($product->prix_vente * $product->qte_dispo, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div>
                    <!-- Image -->
                    <div style="
                        background: white;
                        border-radius: 12px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                        overflow: hidden;
                        margin-bottom: 25px;
                    ">
                        <div style="
                            padding: 20px 25px;
                            border-bottom: 1px solid #e8e0d5;
                            background: #faf8f5;
                        ">
                            <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                                <i class="fas fa-image" style="color: #2d5a27; margin-right: 8px;"></i>
                                Image
                            </h5>
                        </div>
                        <div style="padding: 20px; text-align: center;">
                            @if($product->image_path && file_exists(public_path('storage/' . $product->image_path)))
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                     alt="{{ $product->designation }}" 
                                     style="
                                         max-width: 100%;
                                         max-height: 300px;
                                         border-radius: 8px;
                                         object-fit: cover;
                                     ">
                            @elseif($product->image_path && Storage::disk('public')->exists($product->image_path))
                                <img src="{{ Storage::url($product->image_path) }}" 
                                     alt="{{ $product->designation }}" 
                                     style="
                                         max-width: 100%;
                                         max-height: 300px;
                                         border-radius: 8px;
                                         object-fit: cover;
                                     ">
                            @else
                                <div style="
                                    padding: 40px 20px;
                                    background: #faf8f5;
                                    border-radius: 8px;
                                    color: #adb5bd;
                                ">
                                    <i class="fas fa-image" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                                    <p style="margin: 0;">Aucune image disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions rapides -->
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
                        ">
                            <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                                <i class="fas fa-bolt" style="color: #2d5a27; margin-right: 8px;"></i>
                                Actions rapides
                            </h5>
                        </div>
                        <div style="padding: 20px 25px;">
                            <!-- Mise à jour du stock -->
                            <form action="{{ route('admin.produits.update-stock', $product) }}" method="POST" style="margin-bottom: 15px;">
                                @csrf
                                <div style="display: flex; gap: 10px;">
                                    <input type="number" 
                                           name="qte_dispo" 
                                           value="{{ $product->qte_dispo }}" 
                                           min="0"
                                           style="
                                               flex: 1;
                                               padding: 10px 15px;
                                               border: 2px solid #e8e0d5;
                                               border-radius: 8px;
                                               font-size: 1rem;
                                               outline: none;
                                               transition: border-color 0.3s;
                                           "
                                           onfocus="this.style.borderColor='#2d5a27'"
                                           onblur="this.style.borderColor='#e8e0d5'">
                                    <button type="submit" style="
                                        background: #2d5a27;
                                        color: white;
                                        padding: 10px 20px;
                                        border-radius: 30px;
                                        border: none;
                                        font-weight: 500;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                        white-space: nowrap;
                                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                                        <i class="fas fa-sync"></i> Stock
                                    </button>
                                </div>
                            </form>

                            <!-- Suppression -->
                            <form action="{{ route('admin.produits.destroy', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="
                                    background: #dc3545;
                                    color: white;
                                    padding: 10px 20px;
                                    border-radius: 30px;
                                    border: none;
                                    font-weight: 500;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                    width: 100%;
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 8px;
                                " onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'"
                                onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement ce produit ?\n\nCette action est irréversible !');">
                                    <i class="fas fa-trash-alt"></i> Supprimer le produit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Responsive */
    @media (max-width: 992px) {
        .container > div > div:last-child {
            grid-template-columns: 1fr !important;
        }
    }

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
        .container > div > div:last-child > div:first-child > div:first-child > div:last-child > div:first-child {
            grid-template-columns: 1fr !important;
        }
        .container > div > div:last-child > div:first-child > div:last-child > div:last-child {
            grid-template-columns: 1fr 1fr !important;
        }
        .container > div > div:last-child > div:first-child > div:last-child > div:last-child > div:last-child {
            grid-column: span 2;
        }
        .container > div > div:last-child > div:last-child > div:last-child > div:last-child > form > div:first-child {
            flex-direction: column !important;
        }
        .container > div > div:last-child > div:last-child > div:last-child > div:last-child > form > div:first-child button {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .container > div > div:last-child > div:first-child > div:last-child > div:last-child {
            grid-template-columns: 1fr !important;
        }
        .container > div > div:last-child > div:first-child > div:last-child > div:last-child > div:last-child {
            grid-column: span 1;
        }
    }
</style>
@endpush