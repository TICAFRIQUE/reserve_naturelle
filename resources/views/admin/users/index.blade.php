{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton ajout -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-users" style="color: #b8860b; margin-right: 10px;"></i>
                        Gestion des utilisateurs
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les utilisateurs de votre boutique</p>
                </div>
                <a href="{{ route('admin.users.create') }}" style="
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
                    <i class="fas fa-plus-circle"></i> Nouvel utilisateur
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
                <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <div style="flex: 1; min-width: 200px;">
                        <label for="search" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-search" style="color: #b8860b; margin-right: 5px;"></i> Recherche
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
                               placeholder="Nom, prénom ou email..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div style="flex: 0 0 200px; min-width: 150px;">
                        <label for="role" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-tag" style="color: #b8860b; margin-right: 5px;"></i> Rôle
                        </label>
                        <select name="role" id="role" style="
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
                            <option value="">Tous les rôles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="fournisseur" {{ request('role') === 'fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Utilisateur</option>
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
                        <a href="{{ route('admin.users.index') }}" style="
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

            <!-- Tableau des utilisateurs -->
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
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 20%;">Nom complet</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 25%;">Email</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Téléphone</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Rôle</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 13%;">Inscription</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="
                                                width: 38px;
                                                height: 38px;
                                                border-radius: 50%;
                                                background: {{ $user->role === 'admin' ? '#2d5a27' : ($user->role === 'fournisseur' ? '#b8860b' : '#6c757d') }};
                                                color: white;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                font-weight: bold;
                                                font-size: 14px;
                                                flex-shrink: 0;
                                            ">
                                                {{ strtoupper(substr($user->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($user->nom ?? '', 0, 1)) }}
                                            </div>
                                            <span>{{ $user->full_name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; color: #2d5a27;">
                                        <a href="mailto:{{ $user->email }}" style="color: #2d5a27; text-decoration: none;">
                                            <i class="fas fa-envelope" style="color: #b8860b; margin-right: 5px; width: 16px;"></i>
                                            {{ $user->email }}
                                        </a>
                                        @if($user->email_verified_at)
                                            <span style="
                                                background: #d4edda;
                                                color: #155724;
                                                padding: 2px 8px;
                                                border-radius: 12px;
                                                font-size: 0.7rem;
                                                margin-left: 5px;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-check-circle" style="font-size: 10px;"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; color: #6c757d;">
                                        @if($user->tel)
                                            <a href="tel:{{ $user->tel }}" style="color: #6c757d; text-decoration: none;">
                                                <i class="fas fa-phone" style="color: #b8860b; margin-right: 5px; width: 16px;"></i>
                                                {{ $user->tel }}
                                            </a>
                                        @else
                                            <span style="color: #adb5bd;">—</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        @php
                                            $roleColors = [
                                                'admin' => ['bg' => '#2d5a27', 'text' => 'white'],
                                                'fournisseur' => ['bg' => '#b8860b', 'text' => 'white'],
                                                'user' => ['bg' => '#e8e0d5', 'text' => '#2d5a27']
                                            ];
                                            $roleIcons = [
                                                'admin' => 'fa-user-shield',
                                                'fournisseur' => 'fa-truck',
                                                'user' => 'fa-user'
                                            ];
                                        @endphp
                                        <span style="
                                            background: {{ $roleColors[$user->role]['bg'] ?? '#6c757d' }};
                                            color: {{ $roleColors[$user->role]['text'] ?? 'white' }};
                                            padding: 5px 16px;
                                            border-radius: 20px;
                                            font-size: 0.8rem;
                                            font-weight: 500;
                                            display: inline-block;
                                        ">
                                            <i class="fas {{ $roleIcons[$user->role] ?? 'fa-user' }}" style="margin-right: 5px;"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="font-size: 0.85rem;">
                                            <div style="color: #2d5a27; font-weight: 500;">{{ $user->created_at->format('d/m/Y') }}</div>
                                            <div style="color: #6c757d; font-size: 0.75rem;">{{ $user->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <!-- Voir -->
                                            <a href="{{ route('admin.users.show', $user->id) }}" style="
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
                                            <a href="{{ route('admin.users.edit', $user->id) }}" style="
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
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  id="delete-form-{{ $user->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn-supprimer"
                                                        data-id="{{ $user->id }}"
                                                        data-nom="{{ $user->full_name }}"
                                                        data-email="{{ $user->email }}"
                                                        data-role="{{ ucfirst($user->role) }}"
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
                                        <i class="fas fa-users-slash" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucun utilisateur trouvé</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouvel utilisateur" pour en créer un</p>
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
                    @if ($users->total() > 0)
                        Affichage de
                        <strong>{{ $users->firstItem() }}</strong>
                        à
                        <strong>{{ $users->lastItem() }}</strong>
                        sur
                        <strong>{{ $users->total() }}</strong>
                        utilisateurs
                    @else
                        Aucun utilisateur trouvé
                    @endif
                </div>
                <div style="display: flex; justify-content: center;">
                    {{ $users->links() }}
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
            const email = this.dataset.email;
            const role = this.dataset.role;
            
            // Déterminer la couleur de l'icône selon le rôle
            const roleIcon = role === 'Admin' ? 'fa-user-shield' : 
                            role === 'Fournisseur' ? 'fa-truck' : 'fa-user';
            
            Swal.fire({
                title: '🗑️ Supprimer cet utilisateur ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #721c24; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Vous êtes sur le point de supprimer l'utilisateur :
                        </p>
                        <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                            <p style="font-weight: 600; color: #2d5a27; margin: 0; font-size: 1.05rem;">
                                <i class="fas fa-user" style="color: #b8860b;"></i> 
                                <strong>${nom}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 0.9rem;">
                                <i class="fas fa-envelope" style="color: #b8860b;"></i>
                                ${email}
                            </p>
                            <p style="color: #6c757d; margin: 3px 0 0 0; font-size: 0.9rem;">
                                <i class="fas ${roleIcon}" style="color: #b8860b;"></i>
                                Rôle : <strong>${role}</strong>
                            </p>
                        </div>
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