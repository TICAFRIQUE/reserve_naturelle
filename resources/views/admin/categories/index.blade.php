@extends('layouts.auth')

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

            <!-- Message de succès -->
            @if(session('success'))
                <div style="
                    background: #d4edda;
                    color: #155724;
                    padding: 12px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #28a745;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                    {{ session('success') }}
                </div>
            @endif

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
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27;">#</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Nom</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Description</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Statut</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Ordre</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px 20px; color: #6c757d;">{{ $category->id }}</td>
                                    <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                        <i class="fas fa-folder" style="color: #b8860b; margin-right: 8px;"></i>
                                        {{ $category->nom }}
                                    </td>
                                    <td style="padding: 15px 20px; color: #6c757d; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $category->description ?: '—' }}
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
                                        <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" style="
                                                background: #f0ebe5;
                                                color: #2d5a27;
                                                padding: 6px 14px;
                                                border-radius: 20px;
                                                text-decoration: none;
                                                font-size: 0.85rem;
                                                transition: all 0.2s;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 5px;
                                            " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="
                                                    background: #f8d7da;
                                                    color: #721c24;
                                                    padding: 6px 14px;
                                                    border-radius: 20px;
                                                    border: none;
                                                    font-size: 0.85rem;
                                                    cursor: pointer;
                                                    transition: all 0.2s;
                                                    display: inline-flex;
                                                    align-items: center;
                                                    gap: 5px;
                                                " onmouseover="this.style.background='#f5c6cb'" onmouseout="this.style.background='#f8d7da'">
                                                    <i class="fas fa-trash-alt"></i> Supprimer
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

            <!-- Pagination -->
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $categories->links() }}
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
    }
</style>
@endpush