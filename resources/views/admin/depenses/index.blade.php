{{-- resources/views/admin/depenses/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestion des dépenses - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-wallet" style="color: #b8860b; margin-right: 10px;"></i>
                        Gestion des dépenses
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Suivez et enregistrez les dépenses de la boutique</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.categorie-depenses.index') }}" style="
                        background: #f0ebe5; color: #2d5a27; padding: 12px 24px; border-radius: 30px;
                        text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                        <i class="fas fa-tags"></i> Catégories
                    </a>
                    <a href="{{ route('admin.depenses.create') }}" style="
                        background: #2d5a27; color: white; padding: 12px 24px; border-radius: 30px;
                        text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        transition: all 0.3s ease; border: none; cursor: pointer;
                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-plus-circle"></i> Nouvelle dépense
                    </a>
                </div>
            </div>

            <!-- Cartes statistiques -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-coins" style="color: #b8860b; font-size: 20px;"></i>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem;">Total période</div>
                        <div style="color: #2d5a27; font-size: 1.4rem; font-weight: 700;">{{ number_format($totalPeriode, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-receipt" style="color: #b8860b; font-size: 20px;"></i>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem;">Nombre de dépenses</div>
                        <div style="color: #2d5a27; font-size: 1.4rem; font-weight: 700;">{{ $nombreDepenses }}</div>
                    </div>
                </div>
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-calculator" style="color: #b8860b; font-size: 20px;"></i>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem;">Dépense moyenne</div>
                        <div style="color: #2d5a27; font-size: 1.4rem; font-weight: 700;">{{ number_format($depenseMoyenne, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div style="background: #f8f5f0; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #e8e0d5;">
                <form action="{{ route('admin.depenses.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <div style="flex: 1; min-width: 160px;">
                        <label for="date_from" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-calendar" style="color: #2d5a27; margin-right: 5px;"></i> Du
                        </label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" style="
                            width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px;
                            font-size: 0.95rem; outline: none; background: white;
                        ">
                    </div>
                    <div style="flex: 1; min-width: 160px;">
                        <label for="date_to" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-calendar" style="color: #2d5a27; margin-right: 5px;"></i> Au
                        </label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" style="
                            width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px;
                            font-size: 0.95rem; outline: none; background: white;
                        ">
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label for="categorie_depense_id" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i> Catégorie
                        </label>
                        <select name="categorie_depense_id" id="categorie_depense_id" style="
                            width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px;
                            font-size: 0.95rem; background: white; cursor: pointer; outline: none;
                        ">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ request('categorie_depense_id') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 0 0 auto; display: flex; gap: 10px;">
                        <button type="submit" style="
                            background: #2d5a27; color: white; padding: 10px 25px; border-radius: 30px; border: none;
                            font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.depenses.index') }}" style="
                            background: #e8e0d5; color: #2d5a27; padding: 10px 25px; border-radius: 30px;
                            text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                        " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-undo"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tableau -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5;">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                            <tr>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Date</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Libellé</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Catégorie</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Mode</th>
                                <th style="padding: 15px 20px; text-align: right; font-weight: 600; color: #2d5a27;">Montant</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depenses as $depense)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px; color: #6c757d;">{{ $depense->date_depense->format('d/m/Y') }}</td>
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        {{ $depense->libelle }}
                                        @if($depense->reference)
                                            <div style="color: #adb5bd; font-size: 0.8rem;">Réf: {{ $depense->reference }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <span style="background: #f8f5f0; color: #2d5a27; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                            {{ $depense->categorie->nom }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; color: #6c757d; text-transform: capitalize;">{{ str_replace('_', ' ', $depense->mode_paiement) }}</td>
                                    <td style="padding: 15px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.depenses.edit', $depense->id) }}" style="
                                                background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 20px;
                                                text-decoration: none; font-size: 0.8rem; border: 1px solid #ffeaa7;
                                            " onmouseover="this.style.background='#ffeaa7'" onmouseout="this.style.background='#fff3cd'">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.depenses.destroy', $depense->id) }}" method="POST" style="display: inline-block;" id="delete-form-{{ $depense->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-supprimer" data-id="{{ $depense->id }}" data-nom="{{ $depense->libelle }}" style="
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
                                    <td colspan="6" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-wallet" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucune dépense trouvée</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouvelle dépense" pour en créer une</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($depenses->isNotEmpty())
                            <tfoot>
                                <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                    <td colspan="4" style="padding: 15px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total période</td>
                                    <td style="padding: 15px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($totalPeriode, 0, ',', ' ') }} FCFA</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
                <div class="text-muted small">
                    <i class="fas fa-info-circle text-success"></i>
                    @if ($depenses->total() > 0)
                        Affichage de <strong>{{ $depenses->firstItem() }}</strong> à <strong>{{ $depenses->lastItem() }}</strong> sur <strong>{{ $depenses->total() }}</strong> dépenses
                    @else
                        Aucune dépense trouvée
                    @endif
                </div>
                @if ($depenses->hasPages())
                    <nav aria-label="Pagination des dépenses">
                        <ul class="pagination mb-0">
                            @if ($depenses->onFirstPage())
                                <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $depenses->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                            @endif
                            @foreach ($depenses->getUrlRange(1, $depenses->lastPage()) as $page => $url)
                                @if ($page == $depenses->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                            @if ($depenses->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $depenses->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
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
    .pagination { display: flex; list-style: none; gap: 5px; padding: 0; margin: 0; }
    .pagination li { display: inline-block; }
    .pagination li a, .pagination li span {
        display: inline-block; padding: 8px 16px; background: white; border: 1px solid #e8e0d5;
        border-radius: 6px; color: #2d5a27; text-decoration: none; transition: all 0.2s; font-size: 0.9rem;
    }
    .pagination li a:hover { background: #2d5a27; color: white; border-color: #2d5a27; }
    .pagination li.active span { background: #2d5a27; color: white; border-color: #2d5a27; }
    .pagination li.disabled span { color: #adb5bd; background: #f8f5f0; border-color: #e8e0d5; }

    @media (max-width: 768px) {
        .container { padding: 20px 15px !important; }
        table { font-size: 0.85rem !important; }
        th, td { padding: 10px 12px !important; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-supprimer').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            Swal.fire({
                title: '🗑️ Supprimer cette dépense ?',
                html: `<p style="font-weight: 600; color: #2d5a27; background: #f8f5f0; padding: 10px; border-radius: 5px;">${nom}</p>
                       <p style="color: #721c24; font-weight: 500;">Cette action est irréversible !</p>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
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