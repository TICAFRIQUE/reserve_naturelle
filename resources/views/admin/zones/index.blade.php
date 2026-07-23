@extends('layouts.admin')

@section('title', 'Gestion des Zones - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-map-marked-alt" style="color: #2d5a27; margin-right: 10px;"></i>
                        Gestion des zones de livraison
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les zones et leurs tarifs de livraison</p>
                </div>
                <a href="{{ route('admin.zones.create') }}" style="
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
                    <i class="fas fa-plus-circle"></i> Nouvelle zone
                </a>
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
                <form action="{{ route('admin.zones.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <div style="flex: 1; min-width: 180px;">
                        <label for="search" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-search" style="color: #2d5a27; margin-right: 5px;"></i> Rechercher
                        </label>
                        <input type="text" 
                               name="search" 
                               id="search" 
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
                               onblur="this.style.borderColor='#e8e0d5'"
                               placeholder="Nom de la zone..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div style="flex: 0 0 170px; min-width: 140px;">
                        <label for="type" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i> Type
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
                            <option value="local" {{ request('type') == 'local' ? 'selected' : '' }}>🏙️ Livraison locale</option>
                            <option value="expedition" {{ request('type') == 'expedition' ? 'selected' : '' }}>🚚 Expédition</option>
                        </select>
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
                        <a href="{{ route('admin.zones.index') }}" style="
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

            <!-- Tableau -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5;">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                            <tr>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 5%;">#</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 30%;">Nom de la zone</th>
                                <th style="padding: 15px 20px; text-align: right; font-weight: 600; color: #2d5a27; width: 20%;">Tarif</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 20%;">Type</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($zones as $zone)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px; color: #6c757d; font-weight: 500;">{{ $zone->id }}</td>
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="
                                                width: 38px;
                                                height: 38px;
                                                border-radius: 50%;
                                                background: #b8860b;
                                                color: white;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                font-size: 16px;
                                                flex-shrink: 0;
                                            ">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <span>{{ $zone->nom }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right; font-weight: 700; color: #2d5a27; font-size: 1.05rem;">
                                        {{ number_format($zone->tarif ?? $zone->prix ?? 0, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        @if(($zone->est_expedition ?? $zone->type) == 'expedition' || ($zone->est_expedition ?? false) == true)
                                            <span style="
                                                background: #fff3cd;
                                                color: #856404;
                                                padding: 6px 16px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-shipping-fast" style="margin-right: 5px;"></i> Expédition
                                            </span>
                                        @else
                                            <span style="
                                                background: #e8f5e9;
                                                color: #2d5a27;
                                                padding: 6px 16px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-city" style="margin-right: 5px;"></i> Livraison locale
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.zones.edit', $zone->id) }}" style="
                                                background: #f0ebe5;
                                                color: #2d5a27;
                                                padding: 6px 12px;
                                                border-radius: 20px;
                                                text-decoration: none;
                                                font-size: 0.8rem;
                                                transition: all 0.2s;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 4px;
                                            " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            
                                            <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="
                                                    background: #f8d7da;
                                                    color: #721c24;
                                                    padding: 6px 12px;
                                                    border-radius: 20px;
                                                    border: none;
                                                    font-size: 0.8rem;
                                                    cursor: pointer;
                                                    transition: all 0.2s;
                                                    display: inline-flex;
                                                    align-items: center;
                                                    gap: 4px;
                                                " onmouseover="this.style.background='#f5c6cb'" onmouseout="this.style.background='#f8d7da'">
                                                    <i class="fas fa-trash-alt"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-map-marked-alt" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucune zone enregistrée</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouvelle zone" pour en ajouter une</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="color: #6c757d; font-size: 0.9rem;">
                    <i class="fas fa-info-circle" style="color: #2d5a27;"></i>
                    Affichage de {{ $zones->firstItem() ?? 0 }} à {{ $zones->lastItem() ?? 0 }} sur {{ $zones->total() }} zones
                </div>
                <div style="display: flex; justify-content: center;">
                    {{ $zones->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Personnalisation de la pagination */
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
        table {
            font-size: 0.85rem !important;
        }
        th, td {
            padding: 10px 12px !important;
        }
        .container > div > div:last-child {
            padding: 15px !important;
        }
    }
</style>
@endpush