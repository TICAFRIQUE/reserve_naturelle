@extends('layouts.admin')

@section('title', 'Rôles et permissions - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                <i class="fas fa-user-lock" style="color: #b8860b; margin-right: 10px;"></i>
                Rôles et permissions
            </h1>
            <p style="color: #6c757d; margin: 5px 0 0 0;">Créez des rôles et choisissez ce que chacun peut faire</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" style="background: #2d5a27; color: white; padding: 12px 24px; border-radius: 30px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus-circle"></i> Nouveau rôle
        </a>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                    <tr>
                        <th style="padding: 15px 20px; text-align: left; color: #2d5a27;">Rôle</th>
                        <th style="padding: 15px 20px; text-align: center; color: #2d5a27;">Utilisateurs</th>
                        <th style="padding: 15px 20px; text-align: center; color: #2d5a27;">Permissions</th>
                        <th style="padding: 15px 20px; text-align: center; color: #2d5a27;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        @php
                            $style  = \App\Models\User::styleForRole($role->name);
                            $system = in_array($role->name, \App\Http\Controllers\Admin\RoleController::PROTECTED_ROLES, true);
                        @endphp
                        <tr style="border-bottom: 1px solid #f0ebe5;">
                            <td style="padding: 15px 20px;">
                                <span style="background: {{ $style['bg'] }}; color: {{ $style['text'] }}; padding: 5px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; display: inline-block;">
                                    <i class="fas {{ $style['icon'] }}" style="margin-right: 5px;"></i>
                                    {{ \App\Models\User::roleLabel($role->name) }}
                                </span>
                                @if($system)
                                    <span style="color: #adb5bd; font-size: 0.75rem; margin-left: 6px;">système</span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px; text-align: center; color: #2d5a27; font-weight: 600;">{{ $role->users_count }}</td>
                            <td style="padding: 15px 20px; text-align: center; color: #6c757d;">
                                @if($role->name === 'admin')
                                    <em>Toutes</em>
                                @else
                                    {{ $role->permissions_count }}
                                @endif
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    @if($role->name !== 'admin')
                                        <a href="{{ route('admin.roles.edit', $role) }}" title="Modifier" style="background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 20px; text-decoration: none; font-size: 0.8rem; border: 1px solid #ffeaa7;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @unless($system)
                                        <form id="delete-role-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-supprimer-role"
                                                    data-id="{{ $role->id }}"
                                                    data-nom="{{ \App\Models\User::roleLabel($role->name) }}"
                                                    title="Supprimer"
                                                    style="background: #f8d7da; color: #721c24; padding: 6px 12px; border-radius: 20px; border: 1px solid #f5c6cb; font-size: 0.8rem; cursor: pointer;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p style="color: #6c757d; font-size: 0.85rem; margin-top: 15px;">
        <i class="fas fa-info-circle" style="color: #2d5a27;"></i>
        L'administrateur a tous les droits et n'est pas modifiable. Un rôle ne peut être supprimé que s'il n'a plus aucun utilisateur.
    </p>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-supprimer-role').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Supprimer ce rôle ?',
                html: 'Le rôle <strong>' + this.dataset.nom + '</strong> sera supprimé définitivement.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then(function (r) {
                if (r.isConfirmed) document.getElementById('delete-role-' + id).submit();
            });
        });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Succès !', text: @json(session('success')), timer: 4000, timerProgressBar: true, showConfirmButton: false, toast: true, position: 'top-end' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Erreur !', text: @json(session('error')), timer: 6000, timerProgressBar: true, showConfirmButton: false, toast: true, position: 'top-end' });
    @endif
});
</script>
@endpush