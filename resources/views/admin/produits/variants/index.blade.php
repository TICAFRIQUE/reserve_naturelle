@extends('layouts.admin')

@section('title', 'Variantes de ' . $product->designation . ' - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- En-tête --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px;">
                <div>
                    <h1 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:2rem;margin:0;">
                        <i class="fas fa-layer-group" style="color:#b8860b;margin-right:10px;"></i>
                        Variantes de {{ $product->designation }}
                    </h1>
                    <p style="color:#6c757d;margin:5px 0 0 0;">
                        Référence produit : <strong>{{ $product->reference_prod }}</strong>
                    </p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('admin.produits.index') }}" style="background:#e8e0d5;color:#2d5a27;padding:12px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-arrow-left"></i> Retour aux produits
                    </a>
                    <a href="{{ route('admin.produits.variants.create', $product) }}" style="background:#2d5a27;color:white;padding:12px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-plus-circle"></i> Nouvelle variante
                    </a>
                </div>
            </div>

            {{-- Messages flash --}}
            @if(session('success'))
                <div style="background:#d4edda;color:#155724;padding:12px 20px;border-radius:8px;border-left:4px solid #28a745;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-check-circle" style="font-size:20px;"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#f8d7da;color:#721c24;padding:12px 20px;border-radius:8px;border-left:4px solid #dc3545;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-exclamation-circle" style="font-size:20px;"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Tableau --}}
            <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;border:1px solid #e8e0d5;">
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:0.95rem;">
                        <thead style="background:#f8f5f0;border-bottom:2px solid #e8e0d5;">
                            <tr>
                                <th style="padding:15px 20px;text-align:left;font-weight:600;color:#2d5a27;">Référence (SKU)</th>
                                <th style="padding:15px 20px;text-align:left;font-weight:600;color:#2d5a27;">Conditionnement</th>
                                <th style="padding:15px 20px;text-align:right;font-weight:600;color:#2d5a27;">Prix vente</th>
                                <th style="padding:15px 20px;text-align:center;font-weight:600;color:#2d5a27;">Stock dispo</th>
                                <th style="padding:15px 20px;text-align:right;font-weight:600;color:#2d5a27;">CMP</th>
                                <th style="padding:15px 20px;text-align:center;font-weight:600;color:#2d5a27;">Statut</th>
                                <th style="padding:15px 20px;text-align:center;font-weight:600;color:#2d5a27;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variants as $v)
                                <tr style="border-bottom:1px solid #f0ebe5;transition:background 0.2s;"
                                    onmouseover="this.style.background='#fafaf8'" onmouseout="this.style.background='white'">
                                    <td style="padding:15px 20px;font-weight:600;color:#2d5a27;">
                                        {{ $v->reference_prod }}
                                    </td>
                                    <td style="padding:15px 20px;">
                                        <span style="background:#e8f5e9;color:#2d5a27;padding:3px 10px;border-radius:12px;font-size:0.8rem;font-weight:600;">
                                            {{ $v->conditionnement }}
                                        </span>
                                    </td>
                                    <td style="padding:15px 20px;text-align:right;font-weight:600;color:#2d5a27;">
                                        {{ number_format($v->prix_vente, 0, ',', ' ') }} F
                                    </td>
                                    <td style="padding:15px 20px;text-align:center;">
                                        @if($v->qte_dispo <= 0)
                                            <span style="background:#f8d7da;color:#721c24;padding:3px 12px;border-radius:12px;font-weight:600;">
                                                Rupture
                                            </span>
                                        @elseif($v->sous_seuil)
                                            <span style="background:#fff3cd;color:#856404;padding:3px 12px;border-radius:12px;font-weight:600;">
                                                {{ $v->qte_dispo }} (faible)
                                            </span>
                                        @else
                                            <span style="background:#d4edda;color:#155724;padding:3px 12px;border-radius:12px;font-weight:600;">
                                                {{ $v->qte_dispo }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:15px 20px;text-align:right;color:#6c757d;">
                                        {{ number_format($v->cmp, 0, ',', ' ') }} F
                                    </td>
                                    <td style="padding:15px 20px;text-align:center;">
                                        @if($v->actif)
                                            <span style="background:#d4edda;color:#155724;padding:3px 12px;border-radius:12px;font-size:0.8rem;font-weight:600;">
                                                <i class="fas fa-check"></i> Actif
                                            </span>
                                        @else
                                            <span style="background:#f8d7da;color:#721c24;padding:3px 12px;border-radius:12px;font-size:0.8rem;font-weight:600;">
                                                <i class="fas fa-times"></i> Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:15px 20px;text-align:center;">
                                        <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
                                            <a href="{{ route('admin.variants.edit', $v) }}" style="background:#f0ebe5;color:#2d5a27;padding:6px 14px;border-radius:20px;text-decoration:none;font-size:0.85rem;transition:all 0.2s;display:inline-flex;align-items:center;gap:4px;"
                                                onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>

                                            <form action="{{ route('admin.variants.destroy', $v) }}" method="POST"
                                                  class="delete-variant-form" data-ref="{{ $v->reference_prod }}"
                                                  style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-delete-variant" style="background:#f8d7da;color:#721c24;padding:6px 14px;border-radius:20px;border:none;font-size:0.85rem;cursor:pointer;display:inline-flex;align-items:center;gap:4px;"
                                                    onmouseover="this.style.background='#f5c6cb'" onmouseout="this.style.background='#f8d7da'">
                                                    <i class="fas fa-trash-alt"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:60px 20px;text-align:center;color:#6c757d;">
                                        <i class="fas fa-layer-group" style="font-size:48px;display:block;margin-bottom:15px;color:#d4c9bb;"></i>
                                        <p style="font-size:1.1rem;margin:0;">Aucune variante pour ce produit</p>
                                        <p style="margin-top:5px;">Cliquez sur "Nouvelle variante" pour en créer une</p>
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
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-variant').forEach(function (button) {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-variant-form');
            const ref = form.dataset.ref;

            Swal.fire({
                title: '🗑️ Supprimer cette variante ?',
                html: '<div style="text-align: left;">' +
                      '<p style="color: #721c24; font-weight: 500;">' +
                      '<i class="fas fa-exclamation-triangle" style="color: #856404;"></i> ' +
                      'Cette action est <strong>irréversible</strong>.</p>' +
                      '<p style="font-weight: 600; color: #2d5a27; background: #f8f5f0; padding: 10px; border-radius: 5px; text-align: center; margin-top: 10px;">' +
                      '<strong>' + ref + '</strong></p>' +
                      '</div>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Suppression en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush