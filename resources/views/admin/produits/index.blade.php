{{-- resources/views/admin/produits/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestion des Produits - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-boxes" style="color: #2d5a27; margin-right: 10px;"></i>
                        Gestion des produits
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les produits de votre catalogue</p>
                </div>
                <a href="{{ route('admin.produits.create') }}" style="
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
                    <i class="fas fa-plus-circle"></i> Nouveau produit
                </a>
            </div>

            <!-- Filtre -->
            <div style="
                background: #f8f5f0;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 1px solid #e8e0d5;
            ">
                <form action="{{ route('admin.produits.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
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
                               placeholder="Référence, désignation..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div style="flex: 0 0 200px; min-width: 160px;">
                        <label for="category_id" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-folder" style="color: #2d5a27; margin-right: 5px;"></i> Catégorie
                        </label>
                        <select name="category_id" id="category_id" style="
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
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    ⚡ {{ $category->nom }}
                                </option>
                                @foreach($category->sousCategories as $sousCategory)
                                    <option value="{{ $sousCategory->id }}" {{ request('category_id') == $sousCategory->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;└ {{ $sousCategory->nom }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 0 0 160px; min-width: 130px;">
                        <label for="stock_filter" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-cubes" style="color: #2d5a27; margin-right: 5px;"></i> Stock
                        </label>
                        <select name="stock_filter" id="stock_filter" style="
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
                            <option value="">Tous les stocks</option>
                            <option value="in_stock" {{ request('stock_filter') == 'in_stock' ? 'selected' : '' }}>✅ En stock</option>
                            <option value="out_of_stock" {{ request('stock_filter') == 'out_of_stock' ? 'selected' : '' }}>❌ Rupture</option>
                        </select>
                    </div>

                    <div style="flex: 0 0 130px; min-width: 110px;">
                        <label for="price_min" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-money-bill-wave" style="color: #2d5a27; margin-right: 5px;"></i> Prix min
                        </label>
                        <input type="number" 
                               name="price_min" 
                               id="price_min"
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
                               placeholder="Min"
                               value="{{ request('price_min') }}">
                    </div>

                    <div style="flex: 0 0 130px; min-width: 110px;">
                        <label for="price_max" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-money-bill-wave" style="color: #2d5a27; margin-right: 5px;"></i> Prix max
                        </label>
                        <input type="number" 
                               name="price_max" 
                               id="price_max"
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
                               placeholder="Max"
                               value="{{ request('price_max') }}">
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
                        <a href="{{ route('admin.produits.index') }}" style="
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
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 10%;">Référence</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 20%;">Désignation</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Catégorie</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Sous-catégorie</th>
                                <th style="padding: 15px 20px; text-align: right; font-weight: 600; color: #2d5a27; width: 12%;">Prix</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 10%;">Stock</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 18%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px;">
                                        <span style="
                                            background: #e8f5e9;
                                            color: #2d5a27;
                                            padding: 4px 12px;
                                            border-radius: 20px;
                                            font-size: 0.8rem;
                                            font-weight: 600;
                                            display: inline-block;
                                        ">
                                            {{ $product->reference_prod }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="
                                                width: 38px;
                                                height: 38px;
                                                border-radius: 8px;
                                                background: #f8f5f0;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                flex-shrink: 0;
                                                overflow: hidden;
                                            ">
                                                @if($product->image_path)
                                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                         alt="{{ $product->designation }}" 
                                                         style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-box" style="color: #b8860b; font-size: 18px;"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div style="font-weight: 500; color: #2d5a27;">{{ $product->designation }}</div>
                                                @if($product->description)
                                                    <div style="color: #6c757d; font-size: 0.8rem; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ Str::limit($product->description, 35) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <span style="
                                            background: #f8f5f0;
                                            color: #2d5a27;
                                            padding: 4px 12px;
                                            border-radius: 20px;
                                            font-size: 0.8rem;
                                            display: inline-block;
                                        ">
                                            <i class="fas fa-folder" style="color: #b8860b;"></i>
                                            {{ $product->category->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        @if($product->sousCategory)
                                            <span style="
                                                background: #e8f5e9;
                                                color: #1e6b3a;
                                                padding: 4px 12px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                display: inline-block;
                                            ">
                                                <i class="fas fa-sitemap" style="color: #b8860b;"></i>
                                                {{ $product->sousCategory->nom }}
                                            </span>
                                        @else
                                            <span style="color: #adb5bd; font-size: 0.8rem;">
                                                <i class="fas fa-minus-circle"></i> Aucune
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right; font-weight: 600; color: #2d5a27;">
                                        {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        @php
                                            $stock = $product->qte_dispo ?? 0;
                                            $stockClass = $stock > ($product->stock_minimum ?? 5) ? '#28a745' : ($stock > 0 ? '#ffc107' : '#dc3545');
                                        @endphp
                                        <span style="
                                            background: {{ $stockClass }};
                                            color: white;
                                            padding: 4px 14px;
                                            border-radius: 20px;
                                            font-size: 0.85rem;
                                            font-weight: 500;
                                            display: inline-block;
                                            min-width: 30px;
                                        ">
                                            {{ $stock }}
                                        </span>
                                        @if($stock <= ($product->stock_minimum ?? 5) && $stock > 0)
                                            <div style="color: #ffc107; font-size: 0.65rem; margin-top: 2px;">
                                                <i class="fas fa-exclamation-triangle"></i> Stock faible
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.produits.show', $product) }}" style="
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
                                            
                                            <a href="{{ route('admin.produits.edit', $product) }}" style="
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
                                            
                                            <form action="{{ route('admin.produits.destroy', $product) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  id="delete-form-{{ $product->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn-supprimer"
                                                        data-id="{{ $product->id }}"
                                                        data-nom="{{ $product->designation }}"
                                                        data-reference="{{ $product->reference_prod }}"
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
                                        <i class="fas fa-box-open" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucun produit trouvé</p>
                                        <p style="margin-top: 5px;">Cliquez sur "Nouveau produit" pour en ajouter un</p>
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
                    @if ($products->total() > 0)
                        Affichage de
                        <strong>{{ $products->firstItem() }}</strong>
                        à
                        <strong>{{ $products->lastItem() }}</strong>
                        sur
                        <strong>{{ $products->total() }}</strong>
                        produits
                    @else
                        Aucun produit trouvé
                    @endif
                </div>
                <div style="display: flex; justify-content: center;">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
    document.querySelectorAll('.btn-supprimer').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            const reference = this.dataset.reference;
            
            Swal.fire({
                title: '🗑️ Supprimer ce produit ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #721c24; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Vous êtes sur le point de supprimer le produit :
                        </p>
                        <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                            <p style="font-weight: 600; color: #2d5a27; margin: 0;">
                                <i class="fas fa-box" style="color: #b8860b;"></i> 
                                <strong>${nom}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 0.9rem;">
                                <i class="fas fa-tag" style="color: #b8860b;"></i>
                                Référence : <strong>${reference}</strong>
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
                    Swal.fire({
                        title: 'Suppression en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });

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