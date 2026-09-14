{{-- resources/views/admin/tournees/index.blade.php --}}
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
                        Gestion des livraisons
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

            <!-- Formulaire de filtre -->
            <div style="
                background: #f8f5f0;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 1px solid #e8e0d5;
            ">
                <form action="{{ route('admin.tournees.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
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
                               placeholder="Livreur ou zone..." 
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
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>🔄 En cours</option>
                            <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>✅ Terminée</option>
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
                        <a href="{{ route('admin.tournees.index') }}" style="
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
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 0.95rem;">
                        <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                            <tr>
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
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 12px 10px; color: #2d5a27; font-weight: 500;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="
                                                width: 32px;
                                                height: 32px;
                                                border-radius: 50%;
                                                background: #2d5a27;
                                                color: white;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                font-weight: bold;
                                                font-size: 12px;
                                                flex-shrink: 0;
                                            ">
                                                {{ strtoupper(substr($tournee->livreur->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($tournee->livreur->nom ?? '', 0, 1)) }}
                                            </div>
                                            <span>{{ $tournee->livreur->full_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 10px; color: #2d5a27;">
                                        <span style="
                                            background: #e8f5e9;
                                            color: #2d5a27;
                                            padding: 4px 12px;
                                            border-radius: 20px;
                                            font-size: 0.8rem;
                                            display: inline-block;
                                        ">
                                            <i class="fas fa-map-marker-alt" style="margin-right: 4px;"></i>
                                            {{ $tournee->zone->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 10px; color: #2d5a27;">
                                        <i class="far fa-calendar-alt" style="color: #b8860b; margin-right: 5px;"></i>
                                        {{ $tournee->date_tournee->format('d/m/Y') }}
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <span style="
                                            background: #f8f5f0;
                                            color: #2d5a27;
                                            padding: 4px 14px;
                                            border-radius: 20px;
                                            font-size: 0.85rem;
                                            font-weight: 600;
                                        ">
                                            {{ $tournee->orders->count() }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        @if($tournee->statut === 'en_cours')
                                            <span style="
                                                background: #fff3cd;
                                                color: #856404;
                                                padding: 6px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-truck-loading" style="margin-right: 5px;"></i> En cours
                                            </span>
                                        @else
                                            <span style="
                                                background: #d4edda;
                                                color: #155724;
                                                padding: 6px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-check-circle" style="margin-right: 5px;"></i> Terminée
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <!-- Voir Détails -->
                                            <a href="{{ route('admin.tournees.show', $tournee) }}" style="
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
                                            
                                            <!-- Terminer (seulement si en cours) -->
                                            @if($tournee->statut === 'en_cours')
                                                <form action="{{ route('admin.tournees.terminer', $tournee) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      id="terminer-form-{{ $tournee->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" 
                                                            class="btn-terminer"
                                                            data-id="{{ $tournee->id }}"
                                                            data-livreur="{{ $tournee->livreur->full_name ?? 'N/A' }}"
                                                            data-zone="{{ $tournee->zone->nom ?? 'N/A' }}"
                                                            data-commandes="{{ $tournee->orders->count() }}"
                                                            style="
                                                                background: #d4edda;
                                                                color: #155724;
                                                                padding: 6px 12px;
                                                                border-radius: 20px;
                                                                border: 1px solid #b7d7b3;
                                                                font-size: 0.8rem;
                                                                cursor: pointer;
                                                                transition: all 0.2s;
                                                                display: inline-flex;
                                                                align-items: center;
                                                                gap: 4px;
                                                            " onmouseover="this.style.background='#b7d7b3'" onmouseout="this.style.background='#d4edda'">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <!-- Supprimer -->
                                            <form action="{{ route('admin.tournees.destroy', $tournee) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  id="delete-form-{{ $tournee->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn-supprimer"
                                                        data-id="{{ $tournee->id }}"
                                                        data-livreur="{{ $tournee->livreur->full_name ?? 'N/A' }}"
                                                        data-zone="{{ $tournee->zone->nom ?? 'N/A' }}"
                                                        data-date="{{ $tournee->date_tournee->format('d/m/Y') }}"
                                                        data-commandes="{{ $tournee->orders->count() }}"
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
                                        <i class="fas fa-route" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucune tournée créée</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouvelle tournée" pour en créer une</p>
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
                    @if ($tournees->total() > 0)
                        Affichage de
                        <strong>{{ $tournees->firstItem() }}</strong>
                        à
                        <strong>{{ $tournees->lastItem() }}</strong>
                        sur
                        <strong>{{ $tournees->total() }}</strong>
                        tournées
                    @else
                        Aucune tournée trouvée
                    @endif
                </div>
                <div style="display: flex; justify-content: center;">
                    {{ $tournees->appends(request()->query())->links() }}
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
            flex-direction: column !important;
            align-items: center !important;
        }
        .container > div > div:last-child > div:first-child {
            text-align: center;
        }
        td:last-child > div {
            flex-direction: column !important;
            align-items: center !important;
        }
        td:last-child > div > a,
        td:last-child > div > form {
            width: 100%;
        }
        td:last-child > div > a,
        td:last-child > div > form button {
            justify-content: center !important;
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire d'événements pour la terminaison de tournée
    document.querySelectorAll('.btn-terminer').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const livreur = this.dataset.livreur;
            const zone = this.dataset.zone;
            const commandes = this.dataset.commandes;
            
            Swal.fire({
                title: '✅ Terminer cette tournée ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #155724; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Vous êtes sur le point de terminer la tournée :
                        </p>
                        <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                            <p style="font-weight: 600; color: #2d5a27; margin: 0;">
                                <i class="fas fa-user" style="color: #b8860b;"></i> 
                                <strong>${livreur}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 0.9rem;">
                                <i class="fas fa-map-marker-alt" style="color: #b8860b;"></i>
                                Zone : <strong>${zone}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 3px 0 0 0; font-size: 0.9rem;">
                                <i class="fas fa-box" style="color: #b8860b;"></i>
                                Commandes : <strong>${commandes}</strong>
                            </p>
                        </div>
                        <p style="color: #155724; font-weight: 500; background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <i class="fas fa-info-circle"></i>
                            Une fois terminée, cette tournée ne pourra plus être modifiée.
                        </p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '✅ Oui, terminer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                width: '550px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un loader
                    Swal.fire({
                        title: 'Terminaison en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Soumettre le formulaire
                    document.getElementById('terminer-form-' + id).submit();
                }
            });
        });
    });

    // Gestionnaire d'événements pour la suppression
    document.querySelectorAll('.btn-supprimer').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const livreur = this.dataset.livreur;
            const zone = this.dataset.zone;
            const date = this.dataset.date;
            const commandes = this.dataset.commandes;
            
            // Message d'avertissement si la tournée a des commandes
            let commandesWarning = '';
            if (parseInt(commandes) > 0) {
                commandesWarning = `
                    <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #ffeaa7;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> Cette tournée contient 
                        <strong>${commandes} commande(s)</strong> qui seront également supprimées.
                    </div>
                `;
            }
            
            Swal.fire({
                title: '🗑️ Supprimer cette tournée ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #721c24; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Vous êtes sur le point de supprimer la tournée :
                        </p>
                        <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                            <p style="font-weight: 600; color: #2d5a27; margin: 0;">
                                <i class="fas fa-user" style="color: #b8860b;"></i> 
                                <strong>${livreur}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 0.9rem;">
                                <i class="fas fa-map-marker-alt" style="color: #b8860b;"></i>
                                Zone : <strong>${zone}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 3px 0 0 0; font-size: 0.9rem;">
                                <i class="far fa-calendar-alt" style="color: #b8860b;"></i>
                                Date : <strong>${date}</strong>
                            </p>
                        </div>
                        ${commandesWarning}
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