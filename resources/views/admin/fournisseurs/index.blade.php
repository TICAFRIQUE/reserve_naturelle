{{-- resources/views/admin/fournisseurs/index.blade.php --}}
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
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 20%;">Nom complet</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Téléphone</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 22%;">Adresse</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 13%;">Ville</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 13%;">Date d'ajout</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 10%;">Achats</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 13%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fournisseurs as $fournisseur)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <!-- Avatar avec initiales -->
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
                                            <!-- Nom complet -->
                                            <div>
                                                <span style="font-weight: 600; color: #2d5a27;">{{ $fournisseur->full_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; color: #2d5a27;">
                                        <a href="tel:{{ $fournisseur->tel }}" style="color: #2d5a27; text-decoration: none;">
                                            <i class="fas fa-phone" style="color: #2d5a27; margin-right: 5px;"></i>
                                            {{ $fournisseur->tel }}
                                        </a>
                                    </td>
                                    <td style="padding: 15px 20px; color: #6c757d;">
                                        <i class="fas fa-map-pin" style="color: #2d5a27; margin-right: 5px;"></i>
                                        {{ Str::limit($fournisseur->adress, 40) }}
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
                                        @if($fournisseur->date_ajout instanceof \Carbon\Carbon)
                                            {{ $fournisseur->date_ajout->format('d/m/Y') }}
                                        @else
                                            {{ date('d/m/Y', strtotime($fournisseur->date_ajout)) }}
                                        @endif
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
                                            <!-- Voir -->
                                            <a href="{{ route('admin.fournisseurs.show', $fournisseur->id) }}" style="
                                                background: #e8f5e9;
                                                color: #2d5a27;
                                                padding: 6px 12px;
                                                border-radius: 20px;
                                                text-decoration: none;
                                                font-size: 0.8rem;
                                                transition: all 0.2s;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 4px;
                                                border: 1px solid #c8e6c9;
                                            " onmouseover="this.style.background='#c8e6c9'" onmouseout="this.style.background='#e8f5e9'">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Modifier -->
                                            <a href="{{ route('admin.fournisseurs.edit', $fournisseur->id) }}" style="
                                                background: #fff3cd;
                                                color: #856404;
                                                padding: 6px 12px;
                                                border-radius: 20px;
                                                text-decoration: none;
                                                font-size: 0.8rem;
                                                transition: all 0.2s;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 4px;
                                                border: 1px solid #ffeaa7;
                                            " onmouseover="this.style.background='#ffeaa7'" onmouseout="this.style.background='#fff3cd'">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Supprimer -->
                                            <form action="{{ route('admin.fournisseurs.destroy', $fournisseur->id) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  id="delete-form-{{ $fournisseur->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn-supprimer"
                                                        data-id="{{ $fournisseur->id }}"
                                                        data-nom="{{ $fournisseur->full_name }}"
                                                        style="
                                                            background: #f8d7da;
                                                            color: #721c24;
                                                            padding: 6px 12px;
                                                            border-radius: 20px;
                                                            border: 1px solid #f5c6cb;
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
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding: 60px 20px; text-align: center; color: #6c757d;">
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
            <br>
            <br>
            <br>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
                <!-- Informations -->
                <div class="text-muted small">
                    <i class="fas fa-info-circle text-success"></i>
                    @if ($fournisseurs->total() > 0)
                        Affichage de
                        <strong>{{ $fournisseurs->firstItem() }}</strong>
                        à
                        <strong>{{ $fournisseurs->lastItem() }}</strong>
                        sur
                        <strong>{{ $fournisseurs->total() }}</strong>
                        fournisseurs
                    @else
                        Aucun fournisseur trouvé
                    @endif
                </div>

                <!-- Pagination Bootstrap 5 -->
                @if ($fournisseurs->hasPages())
                    <nav aria-label="Pagination des fournisseurs">
                        <ul class="pagination mb-0">
                            {{-- Précédent --}}
                            @if ($fournisseurs->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $fournisseurs->previousPageUrl() }}">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pages --}}
                            @foreach ($fournisseurs->getUrlRange(1, $fournisseurs->lastPage()) as $page => $url)
                                @if ($page == $fournisseurs->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Suivant --}}
                            @if ($fournisseurs->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $fournisseurs->nextPageUrl() }}">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
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
            flex-direction: column !important;
            align-items: center !important;
        }
        .container > div > div:last-child > div:first-child {
            text-align: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire d'événements pour la suppression
    document.querySelectorAll('.btn-supprimer').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            
            Swal.fire({
                title: '🗑️ Supprimer ce fournisseur ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #721c24; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Vous êtes sur le point de supprimer le fournisseur :
                        </p>
                        <p style="font-weight: 600; color: #2d5a27; background: #f8f5f0; padding: 10px; border-radius: 5px; text-align: center;">
                            <i class="fas fa-user"></i> <strong>{{ $fournisseur->full_name ?? '' }}</strong>
                        </p>
                        <p style="color: #721c24; font-weight: 500; background: #f8d7da; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <i class="fas fa-exclamation-circle"></i>
                            Cette action est irréversible !
                        </p>
                    </div>
                `,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un loader
                    Swal.fire({
                        title: 'Suppression en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Soumettre le formulaire
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });

    // Messages flash avec SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès !',
            text: "{{ session('success') }}",
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur !',
            text: "{{ session('error') }}",
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Attention !',
            text: "{{ session('warning') }}",
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Information',
            text: "{{ session('info') }}",
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif
});
</script>
@endpush