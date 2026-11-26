@extends('layouts.admin')

@section('title', ($role->exists ? 'Modifier' : 'Créer') . ' un rôle - La Réserve Naturelle')

@section('content')
@php
    $editing = $role->exists;
    $locked  = $editing && in_array($role->name, \App\Http\Controllers\Admin\RoleController::PROTECTED_ROLES, true);
    $checked = old('permissions', $selected);
    $label   = fn ($key, $fallback) => \Illuminate\Support\Facades\Lang::has($key) ? __($key) : $fallback;
@endphp

<div class="container" style="padding: 40px 0;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                <i class="fas fa-user-lock" style="color: #b8860b; margin-right: 10px;"></i>
                {{ $editing ? 'Modifier le rôle' : 'Créer un rôle' }}
            </h1>
            @if($editing)
                <p style="color: #6c757d; margin: 5px 0 0 0;">Rôle : <strong style="color: #2d5a27;">{{ \App\Models\User::roleLabel($role->name) }}</strong></p>
            @endif
        </div>
        <a href="{{ route('admin.roles.index') }}" style="background: #e8e0d5; color: #2d5a27; padding: 12px 24px; border-radius: 30px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px;">
            <strong>Des erreurs sont survenues :</strong>
            <ul style="margin: 5px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $editing ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
        @csrf
        @if($editing) @method('PUT') @endif

        {{-- Nom --}}
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 25px 30px; margin-bottom: 20px;">
            <label for="name" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px;">
                <i class="fas fa-tag" style="color: #b8860b; margin-right: 5px;"></i> Nom du rôle <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $role->name) }}"
                   {{ $locked ? 'disabled' : 'required' }}
                   placeholder="Ex : magasinier"
                   style="width: 100%; max-width: 400px; padding: 12px 16px; border: 2px solid {{ $errors->has('name') ? '#dc3545' : '#e8e0d5' }}; border-radius: 8px; font-size: 1rem; outline: none; background: #faf8f5;">
            <div style="color: #6c757d; font-size: 0.8rem; margin-top: 5px;">
                <i class="fas fa-info-circle"></i>
                @if($locked)
                    Rôle système : le nom n'est pas modifiable, seules les permissions le sont.
                @else
                    Converti automatiquement en minuscules sans accents ni espaces (ex : « Chef de caisse » → chef-de-caisse).
                @endif
            </div>
        </div>

        {{-- Accès admin --}}
        <div style="background: #f8f5f0; border-radius: 12px; border: 1px solid #e8e0d5; border-left: 4px solid #b8860b; padding: 18px 25px; margin-bottom: 20px;">
            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                <input type="checkbox" name="permissions[]" value="acces-admin"
                       {{ in_array('acces-admin', $checked) ? 'checked' : '' }}
                       style="margin-top: 4px; width: 18px; height: 18px; accent-color: #2d5a27;">
                <span>
                    <strong style="color: #2d5a27;">{{ __('permissions.acces-admin') }}</strong><br>
                    <span style="color: #6c757d; font-size: 0.85rem;">Obligatoire pour que les utilisateurs de ce rôle puissent entrer dans le back-office. Sans cette case, les permissions ci-dessous n'ont aucun effet.</span>
                </span>
            </label>
        </div>

        {{-- Permissions par module --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; margin-bottom: 25px;">
            @foreach($groups as $module => $perms)
                <div style="background: white; border-radius: 12px; border: 1px solid #e8e0d5; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden;">
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px 18px; background: #faf8f5; border-bottom: 1px solid #e8e0d5; cursor: pointer; font-weight: 600; color: #2d5a27;">
                        <input type="checkbox" class="mod-toggle" data-mod="{{ $module }}" style="width: 17px; height: 17px; accent-color: #2d5a27;">
                        {{ $label('permissions.modules.' . $module, ucfirst($module)) }}
                    </label>
                    <div style="padding: 10px 18px 14px;">
                        @foreach($perms as $perm)
                            @php $action = explode('.', $perm->name)[1] ?? $perm->name; @endphp
                            <label style="display: flex; align-items: center; gap: 10px; padding: 5px 0; cursor: pointer; color: #374151; font-size: 0.92rem;">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" data-mod="{{ $module }}"
                                       {{ in_array($perm->name, $checked) ? 'checked' : '' }}
                                       style="width: 16px; height: 16px; accent-color: #2d5a27;">
                                {{ $label('permissions.actions.' . $action, ucfirst($action)) }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('admin.roles.index') }}" style="background: #e8e0d5; color: #2d5a27; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" style="background: #2d5a27; color: white; padding: 12px 35px; border-radius: 30px; border: none; font-weight: 500; cursor: pointer; font-size: 1rem;">
                <i class="fas fa-save"></i> {{ $editing ? 'Mettre à jour' : 'Créer le rôle' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.mod-toggle').forEach(function (toggle) {
        const boxes = document.querySelectorAll('input[name="permissions[]"][data-mod="' + toggle.dataset.mod + '"]');

        const sync = function () {
            const all = Array.from(boxes).every(function (b) { return b.checked; });
            const some = Array.from(boxes).some(function (b) { return b.checked; });
            toggle.checked = all;
            toggle.indeterminate = some && !all;
        };

        toggle.addEventListener('change', function () {
            boxes.forEach(function (b) { b.checked = toggle.checked; });
            sync();
        });
        boxes.forEach(function (b) { b.addEventListener('change', sync); });
        sync();
    });
});
</script>
@endpush