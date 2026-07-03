@extends('layouts.admin')

@section('title', 'Gestion des Fournisseurs - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton ajout -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-truck" style="color: #2d5a27; margin-right: 10px;"></i>
                        Gestion des fournisseurs
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les fournisseurs de votre boutique</p>
                </div>
                <a href="{{ route('admin.fournisseurs.create') }}" style="
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
                    border: none;
                    cursor: pointer;
                " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                    <i class="fas fa-plus-circle"></i> Nouveau fournisseur
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
                <form action="{{ route('admin.fournisseurs.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
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
                               placeholder="Nom, prénom, téléphone..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div style="flex: 0 0 180px; min-width: 140px;">
                        <label for="ville" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-map-marker-alt" style="color: #2d5a27; margin-right: 5px;"></i> Ville
                        </label>
                        <select name="ville" id="ville" style="
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
                            <option value="">Toutes les villes</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                    {{ $ville }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 0 0 170px; min-width: 140px;">
                        <label for="date_debut" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-calendar" style="color: #2d5a27; margin-right: 5px;"></i> Date d'ajout (de)
                        </label>
                        <input type="date" 
                               name="date_debut" 
                               id="date_debut"
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
                               value="{{ request('date_debut') }}">
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
                        <a href="{{ route('admin.fournisseurs.index') }}" style="
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

            <!-- Tableau des fournisseurs -->
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
                        font-size: 0.95rem;
                    ">
                        <thead style="
                            background: #f8f5f0;
                            border-bottom: 2px solid #e8e0d5;
                        ">
                            <tr>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 5%;">#</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 18%;">Nom complet</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Téléphone</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 20%;">Adresse</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Ville</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Date d'ajout</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 10%;">Achats</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 13%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fournisseurs as $fournisseur)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s; {{ $fournisseur->trashed() ? 'opacity: 0.6;' : '' }}" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='{{ $fournisseur->trashed() ? 'rgba(248, 215, 218, 0.3)' : 'transparent' }}'">
                                    <td style="padding: 15px 20px; color: #6c757d; font-weight: 500;">{{ $fournisseur->id }}</td>
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="
                                                width: 38px;
                                                height: 38px;
                                                border-radius: 50%;
                                                background: #2d5a27;
                                                color: white;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                font-weight: bold;
                                                font-size: 14px;
                                                flex-shrink: 0;
                                            ">
                                                {{ strtoupper(substr($fournisseur->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($fournisseur->nom ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span>{{ $fournisseur->full_name }}</span>
                                                @if($fournisseur->trashed())
                                                    <span style="
                                                        background: #f8d7da;
                                                        color: #721c24;
                                                        padding: 2px 10px;
                                                        border-radius: 12px;
                                                        font-size: 0.7rem;
                                                        margin-left: 8px;
                                                        display: inline-block;
                                                    ">
                                                        <i class="fas fa-trash-alt"></i> Supprimé
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; color: #2d5a27;">
                                        <a href="tel:{{ $fournisseur->tel }}" style="color: #2d5a27; text-decoration: none;">
                                            <i class="fas fa-phone" style="color: #2d5a27; margin-right: 5px; width: 16px;"></i>
                                            {{ $fournisseur->tel }}
                                        </a>
                                    </td>
                                    <td style="padding: 15px 20px; color: #6c757d;">
                                        <i class="fas fa-map-pin" style="color: #2d5a27; margin-right: 5px;"></i>
                                        {{ Str::limit($fournisseur->adress, 35) }}
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <span style="
                                            background: #e8f5e9;
                                            color: #2d5a27;
                                            padding: 4px 14px;
                                            border-radius: 20px;
                                            font-size: 0.8rem;
                                            font-weight: 500;
                                            display: inline-block;
                                        ">
                                            <i class="fas fa-city" style="margin-right: 4px;"></i>
                                            {{ $fournisseur->ville }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; color: #6c757d; font-size: 0.9rem;">
                                        {{ $fournisseur->date_ajout->format('d/m/Y') }}
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <span style="
                                            background: #f8f5f0;
                                            color: #2d5a27;
                                            padding: 4px 12px;
                                            border-radius: 20px;
                                            font-size: 0.8rem;
                                            font-weight: 600;
                                            display: inline-block;
                                        ">
                                            {{ $fournisseur->achats()->count() }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.fournisseurs.show', $fournisseur->id) }}" style="
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
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('admin.fournisseurs.edit', $fournisseur->id) }}" style="
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
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if($fournisseur->trashed())
                                                <form action="{{ route('admin.fournisseurs.restore', $fournisseur->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Restaurer ce fournisseur ?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" style="
                                                        background: #d4edda;
                                                        color: #155724;
                                                        padding: 6px 12px;
                                                        border-radius: 20px;
                                                        border: none;
                                                        font-size: 0.8rem;
                                                        cursor: pointer;
                                                        transition: all 0.2s;
                                                        display: inline-flex;
                                                        align-items: center;
                                                        gap: 4px;
                                                    " onmouseover="this.style.background='#c3e6cb'" onmouseout="this.style.background='#d4edda'">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.fournisseurs.destroy', $fournisseur->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Supprimer ce fournisseur ?');">
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
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-truck" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucun fournisseur trouvé</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouveau fournisseur" pour en ajouter un</p>
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
                    Affichage de {{ $fournisseurs->firstItem() ?? 0 }} à {{ $fournisseurs->lastItem() ?? 0 }} sur {{ $fournisseurs->total() }} fournisseurs
                </div>
                <div style="display: flex; justify-content: center;">
                    {{ $fournisseurs->appends(request()->query())->links() }}
                </div>
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