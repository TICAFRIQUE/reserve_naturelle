{{-- resources/views/admin/categorie_depenses/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Catégories de dépenses - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-tags" style="color: #b8860b; margin-right: 10px;"></i>
                        Catégories de dépenses
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les catégories utilisées pour classer vos dépenses</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.depenses.index') }}" style="
                        background: #f0ebe5; color: #2d5a27; padding: 12px 24px; border-radius: 30px;
                        text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                    " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                        <i class="fas fa-wallet"></i> Dépenses
                    </a>
                    <a href="{{ route('admin.categorie-depenses.create') }}" style="
                        background: #2d5a27; color: white; padding: 12px 24px; border-radius: 30px;
                        text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        border: none; cursor: pointer;
                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-plus-circle"></i> Nouvelle catégorie
                    </a>
                </div>
            </div>

            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5;">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                            <tr>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 60%;">Nom</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 20%;">Dépenses liées</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $categorie)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="width: 36px; height: 36px; border-radius: 8px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <i class="fas fa-tag" style="color: #b8860b; font-size: 16px;"></i>
                                            </div>
                                            <span>{{ $categorie->nom }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <span style="background: #f8f5f0; color: #2d5a27; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                            {{ $categorie->depenses_count }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.categorie-depenses.edit', $categorie->id) }}" style="
                                                background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 20px;
                                                text-decoration: none; font-size: 0.8rem; border: 1px solid #ffeaa7;
                                            " onmouseover="this.style.background='#ffeaa7'" onmouseout="this.style.background='#fff3cd'">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.categorie-depenses.destroy', $categorie->id) }}" method="POST" style="display: inline-block;" id="delete-form-{{ $categorie->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-supprimer" data-id="{{ $categorie->id }}" data-nom="{{ $categorie->nom }}" data-count="{{ $categorie->depenses_count }}" style="
                                                    background: #f8d7da; color: #721c24; padding: 6px 12px; border-radius: 20px;
                                                    border: 1px solid #f5c6cb; font-size: 0.8rem; cursor: pointer;
                                                " onmouseover="this.style.background='#f5c6cb'" onmouseout="this.style.background='#f8d7da'">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 60px 20px; text-align: center; color: #6c757d;">
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-supprimer').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            const count = parseInt(this.dataset.count) || 0;

            let html = `<p style="font-weight: 600; color: #2d5a27; background: #f8f5f0; padding: 10px; border-radius: 5px; text-align: center;">
                            <i class="fas fa-tag"></i> <strong>${nom}</strong>
                        </p>`;

            if (count > 0) {
                html += `<div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #ffeaa7;">
                            <i class="fas fa-exclamation-triangle"></i>
                            Cette catégorie contient <strong>${count}</strong> dépense(s). Suppression impossible tant qu'elle est utilisée.
                         </div>`;
            } else {
                html += `<p style="color: #721c24; font-weight: 500;">Cette action est irréversible !</p>`;
            }

            Swal.fire({
                title: '🗑️ Supprimer cette catégorie ?',
                html: html,
                icon: count > 0 ? 'warning' : 'error',
                showCancelButton: count === 0,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: count > 0 ? 'Compris' : '🗑️ Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && count === 0) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Succès !', text: "{{ session('success') }}", timer: 4000, timerProgressBar: true, showConfirmButton: false, toast: true, position: 'top-end' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Erreur !', text: "{{ session('error') }}", timer: 5000, timerProgressBar: true, showConfirmButton: false, toast: true, position: 'top-end' });
    @endif
});
</script>
@endpush