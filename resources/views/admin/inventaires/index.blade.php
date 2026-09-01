{{-- resources/views/admin/inventaires/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestion des Inventaires - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton ajout -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-clipboard-list" style="color: #b8860b; margin-right: 10px;"></i>
                        Gestion des inventaires
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les inventaires de votre stock</p>
                </div>
                <a href="{{ route('admin.inventaires.create') }}" style="
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
                    <i class="fas fa-plus-circle"></i> Nouvel inventaire
                </a>
            </div>

            <!-- Messages flash avec SweetAlert -->
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
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
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
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
                    });
                </script>
            @endif

            <!-- Formulaire de filtre -->
            <div style="
                background: #f8f5f0;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 1px solid #e8e0d5;
            ">
                <form method="GET" action="{{ route('admin.inventaires.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <div style="flex: 0 0 180px; min-width: 140px;">
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

                    <div style="flex: 0 0 180px; min-width: 140px;">
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

                    <div style="flex: 0 0 180px; min-width: 140px;">
                        <label for="statut" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-circle" style="color: #b8860b; margin-right: 5px;"></i> Statut
                        </label>
                        <select id="statut" name="statut" style="
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
                            <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="valide" {{ request('statut') === 'valide' ? 'selected' : '' }}>Validé</option>
                            <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
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
                        <a href="{{ route('admin.inventaires.index') }}" style="
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

            <!-- Tableau des inventaires -->
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
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 20%;">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_inventaire', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       style="color: #2d5a27; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                        Référence / Date
                                        @if(request('sort') === 'date_inventaire')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}" style="font-size: 12px;"></i>
                                        @else
                                            <i class="fas fa-sort" style="font-size: 12px; color: #b8860b;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'statut', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       style="color: #2d5a27; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                        Statut
                                        @if(request('sort') === 'statut')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}" style="font-size: 12px;"></i>
                                        @else
                                            <i class="fas fa-sort" style="font-size: 12px; color: #b8860b;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Créé par</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Total produits</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 30%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventaires as $inventaire)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="
                                                width: 40px;
                                                height: 40px;
                                                border-radius: 8px;
                                                background: #e8f5e9;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                flex-shrink: 0;
                                            ">
                                                <i class="fas fa-clipboard-list" style="color: #b8860b; font-size: 18px;"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 600; color: #2d5a27;">{{ $inventaire->reference }}</div>
                                                <div style="font-size: 0.85rem; color: #6c757d;">
                                                    <i class="far fa-calendar-alt" style="margin-right: 4px;"></i>
                                                    {{ $inventaire->date_inventaire->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        @if($inventaire->statut === 'en_cours')
                                            <span style="
                                                background: #fff3cd;
                                                color: #856404;
                                                padding: 4px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-clock" style="margin-right: 4px;"></i> En cours
                                            </span>
                                        @elseif($inventaire->statut === 'valide')
                                            <span style="
                                                background: #d4edda;
                                                color: #155724;
                                                padding: 4px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-check-circle" style="margin-right: 4px;"></i> Validé
                                            </span>
                                        @elseif($inventaire->statut === 'annule')
                                            <span style="
                                                background: #f8d7da;
                                                color: #721c24;
                                                padding: 4px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-times-circle" style="margin-right: 4px;"></i> Annulé
                                            </span>
                                        @else
                                            <span style="
                                                background: #e9ecef;
                                                color: #495057;
                                                padding: 4px 14px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                {{ $inventaire->statut }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px;">
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
                                                {{ strtoupper(substr($inventaire->user->nom ?? 'U', 0, 1)) }}
                                            </div>
                                            <span style="color: #2d5a27;">{{ $inventaire->user->nom ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <span style="
                                            background: #f8f5f0;
                                            color: #2d5a27;
                                            padding: 4px 16px;
                                            border-radius: 20px;
                                            font-size: 0.9rem;
                                            font-weight: 600;
                                            display: inline-block;
                                        ">
                                            {{ $inventaire->produits->count() ?? 0 }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <!-- Voir -->
                                            <a href="{{ route('admin.inventaires.show', $inventaire) }}" style="
                                                background: #e8f5e9;
                                                color: #2d5a27;
                                                padding: 6px 14px;
                                                border-radius: 20px;
                                                text-decoration: none;
                                                font-size: 0.8rem;
                                                transition: all 0.2s;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 4px;
                                                border: 1px solid #c8e6c9;
                                            " onmouseover="this.style.background='#c8e6c9'" onmouseout="this.style.background='#e8f5e9'">
                                                <i class="fas fa-eye"></i>Voir
                                            </a>

                                            <!-- Valider (seulement si en cours) -->
                                            @if($inventaire->statut === 'en_cours')
                                                <form method="POST" action="{{ route('admin.inventaires.valider', $inventaire) }}"
                                                      style="display: inline-block;"
                                                      id="valider-form-{{ $inventaire->id }}">
                                                    @csrf
                                                    <button type="button" 
                                                            onclick="confirmValidation({{ $inventaire->id }})" 
                                                            style="
                                                                background: #d4edda;
                                                                color: #155724;
                                                                padding: 6px 14px;
                                                                border-radius: 20px;
                                                                border: 1px solid #b7d7b3;
                                                                font-size: 0.8rem;
                                                                cursor: pointer;
                                                                transition: all 0.2s;
                                                                display: inline-flex;
                                                                align-items: center;
                                                                gap: 4px;
                                                            " onmouseover="this.style.background='#b7d7b3'" onmouseout="this.style.background='#d4edda'">
                                                        <i class="fas fa-check-circle"></i>Valider
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Annuler (seulement si en cours) -->
                                            @if($inventaire->statut === 'en_cours')
                                                <form method="POST" action="{{ route('admin.inventaires.annuler', $inventaire) }}"
                                                      style="display: inline-block;"
                                                      id="annuler-form-{{ $inventaire->id }}">
                                                    @csrf
                                                    <button type="button" 
                                                            onclick="confirmAnnulation({{ $inventaire->id }})" 
                                                            style="
                                                                background: #f8d7da;
                                                                color: #721c24;
                                                                padding: 6px 14px;
                                                                border-radius: 20px;
                                                                border: 1px solid #f5c6cb;
                                                                font-size: 0.8rem;
                                                                cursor: pointer;
                                                                transition: all 0.2s;
                                                                display: inline-flex;
                                                                align-items: center;
                                                                gap: 4px;
                                                            " onmouseover="this.style.background='#f5c6cb'" onmouseout="this.style.background='#f8d7da'">
                                                        <i class="fas fa-times-circle"></i> Annuler
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Supprimer (uniquement si annulé ou validé) -->
                                            @if($inventaire->statut === 'annule' || $inventaire->statut === 'valide')
                                                <form action="{{ route('admin.inventaires.destroy', $inventaire) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      id="delete-form-{{ $inventaire->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            onclick="confirmDelete({{ $inventaire->id }})" 
                                                            style="
                                                                background: #f8d7da;
                                                                color: #721c24;
                                                                padding: 6px 14px;
                                                                border-radius: 20px;
                                                                border: 1px solid #f5c6cb;
                                                                font-size: 0.8rem;
                                                                cursor: pointer;
                                                                transition: all 0.2s;
                                                                display: inline-flex;
                                                                align-items: center;
                                                                gap: 4px;
                                                            " onmouseover="this.style.background='#f5c6cb'" onmouseout="this.style.background='#f8d7da'">
                                                        <i class="fas fa-trash-alt"></i>Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-clipboard-list" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucun inventaire trouvé</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouvel inventaire" pour en créer un</p>
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
                    Affichage de {{ $inventaires->firstItem() ?? 0 }} à {{ $inventaires->lastItem() ?? 0 }} sur {{ $inventaires->total() }} inventaires
                </div>
                <div style="display: flex; justify-content: center;">
                    {{ $inventaires->appends(request()->query())->links() }}
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

    /* Animation pour les boutons d'action */
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
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
    // Fonction pour la confirmation de validation
    window.confirmValidation = function(id) {
        Swal.fire({
            title: '✅ Valider cet inventaire ?',
            html: `
                <div style="text-align: left;">
                    <p style="color: #155724; font-weight: 500;">
                        <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                        Le stock sera ajusté selon les écarts constatés.
                    </p>
                    <p style="color: #721c24; font-weight: 500; background: #f8d7da; padding: 10px; border-radius: 5px;">
                        <i class="fas fa-exclamation-circle"></i>
                        Cette action est irréversible !
                    </p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '✅ Oui, valider',
            cancelButtonText: '❌ Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Afficher un loader
                Swal.fire({
                    title: 'Validation en cours...',
                    text: 'Veuillez patienter',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Soumettre le formulaire
                document.getElementById('valider-form-' + id).submit();
            }
        });
    };

    // Fonction pour la confirmation d'annulation
    window.confirmAnnulation = function(id) {
        Swal.fire({
            title: '❌ Annuler cet inventaire ?',
            html: `
                <div style="text-align: left;">
                    <p style="color: #721c24; font-weight: 500;">
                        <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                        Toutes les données saisies seront perdues.
                    </p>
                    <p style="color: #721c24; font-weight: 500; background: #f8d7da; padding: 10px; border-radius: 5px;">
                        <i class="fas fa-exclamation-circle"></i>
                        Cette action est irréversible !
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '❌ Oui, annuler',
            cancelButtonText: 'Non, garder',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Afficher un loader
                Swal.fire({
                    title: 'Annulation en cours...',
                    text: 'Veuillez patienter',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Soumettre le formulaire
                document.getElementById('annuler-form-' + id).submit();
            }
        });
    };

    // Fonction pour la confirmation de suppression
    window.confirmDelete = function(id) {
        Swal.fire({
            title: '🗑️ Supprimer définitivement ?',
            html: `
                <div style="text-align: left;">
                    <p style="color: #721c24; font-weight: 500;">
                        <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                        Vous êtes sur le point de supprimer cet inventaire.
                    </p>
                    <p style="color: #721c24; font-weight: 500; background: #f8d7da; padding: 10px; border-radius: 5px;">
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
    };
});
</script>
@endpush