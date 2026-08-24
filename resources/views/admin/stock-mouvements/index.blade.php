{{-- resources/views/admin/stock-mouvements/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Historique des Mouvements de Stock - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-exchange-alt" style="color: #b8860b; margin-right: 10px;"></i>
                        Historique des mouvements de stock
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Consultez tous les mouvements de stock de votre boutique</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.dashboard') }}" style="
                        background: #e8e0d5;
                        color: #2d5a27;
                        padding: 10px 24px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.stock-ajustements.create') }}" style="
                    background: #b8860b;
                    color: white;
                    padding: 10px 24px;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#9a7209'" onmouseout="this.style.background='#b8860b'">
                    <i class="fas fa-sliders-h"></i> Nouvel ajustement
                </a>
                    <a href="{{ route('admin.produits.index') }}" style="
                        background: #2d5a27;
                        color: white;
                        padding: 10px 24px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-boxes"></i> Voir les produits
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

            <!-- Formulaire de filtre -->
            <div style="
                background: #f8f5f0;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 1px solid #e8e0d5;
            ">
                <form method="GET" action="{{ route('admin.stock-mouvements.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <!-- Produit -->
                    <div style="flex: 1; min-width: 180px;">
                        <label for="product_id" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-box" style="color: #b8860b; margin-right: 5px;"></i> Produit
                        </label>
                        <select name="product_id" id="product_id" style="
                            width: 100%;
                            padding: 10px 15px;
                            border: 1px solid #e8e0d5;
                            border-radius: 8px;
                            font-size: 0.95rem;
                            background: white;
                            transition: border-color 0.3s;
                            outline: none;
                            cursor: pointer;
                        " onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            <option value="">Tous les produits</option>
                            @foreach ($produits as $produit)
                                <option value="{{ $produit->id }}" {{ request('product_id') == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->designation }} ({{ $produit->reference_prod ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div style="flex: 0 0 180px; min-width: 140px;">
                        <label for="type" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-tag" style="color: #b8860b; margin-right: 5px;"></i> Type
                        </label>
                        <select name="type" id="type" style="
                            width: 100%;
                            padding: 10px 15px;
                            border: 1px solid #e8e0d5;
                            border-radius: 8px;
                            font-size: 0.95rem;
                            background: white;
                            transition: border-color 0.3s;
                            outline: none;
                            cursor: pointer;
                        " onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            <option value="">Tous les types</option>
                            @foreach (['entree_achat' => 'Entrée achat', 'sortie_commande' => 'Sortie commande', 'retour_fournisseur' => 'Retour fournisseur', 'retour_client' => 'Retour client', 'ajustement' => 'Ajustement', 'inventaire' => 'Inventaire', 'perte_casse' => 'Perte / Casse'] as $value => $label)
                                <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sens -->
                    <div style="flex: 0 0 150px; min-width: 120px;">
                        <label for="sens" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-arrows-h" style="color: #b8860b; margin-right: 5px;"></i> Sens
                        </label>
                        <select name="sens" id="sens" style="
                            width: 100%;
                            padding: 10px 15px;
                            border: 1px solid #e8e0d5;
                            border-radius: 8px;
                            font-size: 0.95rem;
                            background: white;
                            transition: border-color 0.3s;
                            outline: none;
                            cursor: pointer;
                        " onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            <option value="">Entrée/Sortie</option>
                            <option value="entree" {{ request('sens') === 'entree' ? 'selected' : '' }}>📥 Entrée</option>
                            <option value="sortie" {{ request('sens') === 'sortie' ? 'selected' : '' }}>📤 Sortie</option>
                        </select>
                    </div>

                    <!-- Date du -->
                    <div style="flex: 0 0 160px; min-width: 130px;">
                        <label for="du" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-calendar-alt" style="color: #b8860b; margin-right: 5px;"></i> Du
                        </label>
                        <input type="date" 
                               id="du" 
                               name="du" 
                               value="{{ request('du') }}"
                               style="
                                   width: 100%;
                                   padding: 10px 15px;
                                   border: 1px solid #e8e0d5;
                                   border-radius: 8px;
                                   font-size: 0.95rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: white;
                               "
                               onfocus="this.style.borderColor='#2d5a27'"
                               onblur="this.style.borderColor='#e8e0d5'">
                    </div>

                    <!-- Date au -->
                    <div style="flex: 0 0 160px; min-width: 130px;">
                        <label for="au" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-calendar-alt" style="color: #b8860b; margin-right: 5px;"></i> Au
                        </label>
                        <input type="date" 
                               id="au" 
                               name="au" 
                               value="{{ request('au') }}"
                               style="
                                   width: 100%;
                                   padding: 10px 15px;
                                   border: 1px solid #e8e0d5;
                                   border-radius: 8px;
                                   font-size: 0.95rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: white;
                               "
                               onfocus="this.style.borderColor='#2d5a27'"
                               onblur="this.style.borderColor='#e8e0d5'">
                    </div>
                    
                    <div style="flex: 0 0 auto; display: flex; gap: 10px;">
                        <button type="submit" style="
                            background: #2d5a27;
                            color: white;
                            padding: 10px 25px;
                            border-radius: 30px;
                            border: none;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.stock-mouvements.index') }}" style="
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
                        " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-undo"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tableau des mouvements -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                overflow: hidden;
                border: 1px solid #e8e0d5;
            ">
                <div style="overflow-x: auto;">
                    <table style="
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 0.9rem;
                    ">
                        <thead style="
                            background: #f8f5f0;
                            border-bottom: 2px solid #e8e0d5;
                        ">
                            <tr>
                                <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27; white-space: nowrap;">
                                    <i class="fas fa-clock" style="color: #b8860b; margin-right: 5px;"></i>
                                    Date
                                </th>
                                <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-box" style="color: #b8860b; margin-right: 5px;"></i>
                                    Produit
                                </th>
                                <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-tag" style="color: #b8860b; margin-right: 5px;"></i>
                                    Type
                                </th>
                                <th style="padding: 12px 15px; text-align: center; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-arrows-h" style="color: #b8860b; margin-right: 5px;"></i>
                                    Sens
                                </th>
                                <th style="padding: 12px 15px; text-align: center; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-cube" style="color: #b8860b; margin-right: 5px;"></i>
                                    Qté
                                </th>
                                <th style="padding: 12px 15px; text-align: center; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-arrow-left" style="color: #b8860b; margin-right: 5px;"></i>
                                    Stock avant
                                </th>
                                <th style="padding: 12px 15px; text-align: center; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-arrow-right" style="color: #b8860b; margin-right: 5px;"></i>
                                    Stock après
                                </th>
                                <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-link" style="color: #b8860b; margin-right: 5px;"></i>
                                    Source
                                </th>
                                <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-user" style="color: #b8860b; margin-right: 5px;"></i>
                                    Utilisateur
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mouvements as $mvt)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 12px 15px; white-space: nowrap; font-size: 0.85rem; color: #6c757d;">
                                        <i class="far fa-calendar-alt" style="color: #b8860b; margin-right: 5px;"></i>
                                        {{ $mvt->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td style="padding: 12px 15px; font-weight: 500; color: #2d5a27;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="
                                                width: 32px;
                                                height: 32px;
                                                border-radius: 6px;
                                                background: #e8f5e9;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                flex-shrink: 0;
                                            ">
                                                <i class="fas fa-box" style="color: #b8860b; font-size: 14px;"></i>
                                            </div>
                                            <span>{{ $mvt->product->designation ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <span style="
                                            background: #f8f5f0;
                                            color: #2d5a27;
                                            padding: 4px 12px;
                                            border-radius: 20px;
                                            font-size: 0.75rem;
                                            font-weight: 500;
                                            display: inline-block;
                                            white-space: nowrap;
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $mvt->type)) }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center;">
                                        @if($mvt->sens === 'entree')
                                            <span style="
                                                background: #d4edda;
                                                color: #155724;
                                                padding: 4px 12px;
                                                border-radius: 20px;
                                                font-size: 0.75rem;
                                                font-weight: 600;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 4px;
                                            ">
                                                <i class="fas fa-arrow-up"></i> Entrée
                                            </span>
                                        @else
                                            <span style="
                                                background: #f8d7da;
                                                color: #721c24;
                                                padding: 4px 12px;
                                                border-radius: 20px;
                                                font-size: 0.75rem;
                                                font-weight: 600;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 4px;
                                            ">
                                                <i class="fas fa-arrow-down"></i> Sortie
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center; font-weight: 700; font-size: 1.05rem;">
                                        <span style="color: {{ $mvt->sens === 'entree' ? '#28a745' : '#dc3545' }};">
                                            {{ $mvt->sens === 'entree' ? '+' : '-' }}{{ $mvt->quantite }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center; color: #6c757d;">
                                        {{ $mvt->stock_avant }}
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center; font-weight: 600; color: #2d5a27;">
                                        {{ $mvt->stock_apres }}
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        @if ($mvt->source)
                                            <span style="
                                                background: #e8f5e9;
                                                color: #2d5a27;
                                                padding: 2px 10px;
                                                border-radius: 12px;
                                                font-size: 0.75rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                {{ class_basename($mvt->source_type) }} #{{ $mvt->source_id }}
                                            </span>
                                        @else
                                            <span style="color: #adb5bd; font-size: 0.85rem;">—</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <div style="
                                                width: 28px;
                                                height: 28px;
                                                border-radius: 50%;
                                                background: #2d5a27;
                                                color: white;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                font-weight: bold;
                                                font-size: 11px;
                                                flex-shrink: 0;
                                            ">
                                                {{ strtoupper(substr($mvt->user->nom ?? 'U', 0, 1)) }}
                                            </div>
                                            <span style="font-size: 0.85rem; color: #2d5a27;">{{ $mvt->user->nom ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-exchange-alt" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucun mouvement de stock enregistré</p>
                                        <p style="margin-top: 5px;">Les mouvements apparaîtront ici dès qu'ils seront effectués</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                {{ $mouvements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Personnalisation de la pagination pour correspondre au thème */
    .pagination {
        display: flex;
        list-style: none;
        gap: 5px;
        padding: 0;
        margin: 0;
    }
    .pagination li {
        display: inline-block;
    }
    .pagination li a, .pagination li span {
        display: inline-block;
        padding: 8px 16px;
        background: white;
        border: 1px solid #e8e0d5;
        border-radius: 6px;
        color: #2d5a27;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .pagination li a:hover {
        background: #2d5a27;
        color: white;
        border-color: #2d5a27;
    }
    .pagination li.active span {
        background: #2d5a27;
        color: white;
        border-color: #2d5a27;
    }
    .pagination li.disabled span {
        color: #adb5bd;
        background: #f8f5f0;
        border-color: #e8e0d5;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        table {
            font-size: 0.8rem !important;
        }
        th, td {
            padding: 8px 10px !important;
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
            flex-direction: column !important;
        }
        .container > div > div:first-child > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        form {
            flex-direction: column !important;
        }
        form > div {
            width: 100% !important;
            flex: 1 1 auto !important;
        }
        form > div:last-child {
            flex-direction: column !important;
        }
        form > div:last-child button,
        form > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        .pagination li a, .pagination li span {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        .container > div > div:last-child {
            flex-direction: column !important;
            align-items: center !important;
        }
        .container > div > div:last-child > div:first-child {
            text-align: center;
        }
    }
</style>
@endpush