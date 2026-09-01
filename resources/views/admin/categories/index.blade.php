{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestion des catégories - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton ajout -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-tags" style="color: #b8860b; margin-right: 10px;"></i>
                        Gestion des catégories
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les catégories de produits de votre boutique</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" style="
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
                    <i class="fas fa-plus-circle"></i> Nouvelle catégorie
                </a>
            </div>

            <!-- Formulaire de recherche et filtres -->
            <div style="
                background: #f8f5f0;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 1px solid #e8e0d5;
            ">
                <form action="{{ route('admin.categories.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <div style="flex: 1; min-width: 200px;">
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
                               placeholder="Nom de la catégorie..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div style="flex: 0 0 170px; min-width: 140px;">
                        <label for="statut" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-circle" style="color: #2d5a27; margin-right: 5px;"></i> Statut
                        </label>
                        <select name="statut" id="statut" style="
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
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
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
                        <a href="{{ route('admin.categories.index') }}" style="
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

            <!-- Tableau des catégories -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.08);
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
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 25%;">Nom</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 30%;">Description</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 15%;">Statut</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 10%;">Ordre</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 10%;">Sous-cat.</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <!-- Icône de catégorie -->
                                            <div style="
                                                width: 36px;
                                                height: 36px;
                                                border-radius: 8px;
                                                background: #e8f5e9;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                flex-shrink: 0;
                                            ">
                                                <i class="fas fa-folder" style="color: #b8860b; font-size: 16px;"></i>
                                            </div>
                                            <span>{{ $category->nom }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; color: #6c757d; max-width: 300px;">
                                        <div style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $category->description ?: '—' }}
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        @if($category->statut == 'actif' || $category->statut == 1)
                                            <span style="
                                                background: #d4edda;
                                                color: #155724;
                                                padding: 4px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #28a745;"></i>
                                                Actif
                                            </span>
                                        @else
                                            <span style="
                                                background: #f8d7da;
                                                color: #721c24;
                                                padding: 4px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #dc3545;"></i>
                                                Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; font-weight: 500; color: #2d5a27;">
                                        {{ $category->ordre ?? 0 }}
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
                                            {{ $category->sousCategories->count() }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <!-- Modifier -->
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" style="
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
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  id="delete-form-{{ $category->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn-supprimer"
                                                        data-id="{{ $category->id }}"
                                                        data-nom="{{ $category->nom }}"
                                                        data-sous-categories="{{ $category->sousCategories->count() }}"
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
                                    <td colspan="6" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-folder-open" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucune catégorie trouvée</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouvelle catégorie" pour en créer une</p>
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
                    @if ($categories->total() > 0)
                        Affichage de
                        <strong>{{ $categories->firstItem() }}</strong>
                        à
                        <strong>{{ $categories->lastItem() }}</strong>
                        sur
                        <strong>{{ $categories->total() }}</strong>
                        catégories
                    @else
                        Aucune catégorie trouvée
                    @endif
                </div>

                <!-- Pagination Bootstrap 5 -->
                @if ($categories->hasPages())
                    <nav aria-label="Pagination des catégories">
                        <ul class="pagination mb-0">
                            {{-- Précédent --}}
                            @if ($categories->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $categories->previousPageUrl() }}">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pages --}}
                            @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                @if ($page == $categories->currentPage())
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
                            @if ($categories->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $categories->nextPageUrl() }}">
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
            const sousCategories = parseInt(this.dataset.sousCategories) || 0;
            
            // Construire le message HTML
            let html = `
                <div style="text-align: left;">
                    <p style="color: #721c24; font-weight: 500;">
                        <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                        Vous êtes sur le point de supprimer la catégorie :
                    </p>
                    <p style="font-weight: 600; color: #2d5a27; background: #f8f5f0; padding: 10px; border-radius: 5px; text-align: center;">
                        <i class="fas fa-tag"></i> <strong>${nom}</strong>
                    </p>
            `;
            
            // Afficher un avertissement si la catégorie a des sous-catégories
            if (sousCategories > 0) {
                html += `
                    <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #ffeaa7;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Cette catégorie contient 
                        <strong>${sousCategories}</strong> sous-catégorie(s) 
                        qui seront également supprimées.
                    </div>
                `;
            }
            
            html += `
                    <p style="color: #721c24; font-weight: 500; background: #f8d7da; padding: 10px; border-radius: 5px; margin-top: 10px;">
                        <i class="fas fa-exclamation-circle"></i>
                        Cette action est irréversible !
                    </p>
                </div>
            `;
            
            Swal.fire({
                title: '🗑️ Supprimer cette catégorie ?',
                html: html,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                width: '550px'
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