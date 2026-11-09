{{-- resources/views/admin/libelle_depenses/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Libellés de dépenses - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                <i class="fas fa-list" style="color: #b8860b; margin-right: 10px;"></i>
                Libellés de dépenses
            </h1>
            <p style="color: #6c757d; margin: 5px 0 0 0;">Sous-catégories rattachées à chaque catégorie</p>
        </div>
        <a href="{{ route('admin.libelle-depenses.create') }}" style="
            padding: 12px 28px; background: #2d5a27; color: white; border-radius: 30px;
            text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
            <i class="fas fa-plus"></i> Nouveau libellé
        </a>
    </div>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #2d5a27; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fdecea; color: #dc3545; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Filtre --}}
    <form method="GET" style="background: white; border: 1px solid #e8e0d5; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; display: flex; gap: 12px; align-items: center;">
        <select name="categorie_depense_id" style="flex: 1; padding: 10px 14px; border: 2px solid #e8e0d5; border-radius: 8px; outline: none;">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" @selected(request('categorie_depense_id') == $categorie->id)>{{ $categorie->nom }}</option>
            @endforeach
        </select>
        <button type="submit" style="padding: 10px 24px; background: #2d5a27; color: white; border: none; border-radius: 30px; cursor: pointer;">
            <i class="fas fa-filter"></i> Filtrer
        </button>
    </form>

    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f4ee; color: #2d5a27; text-align: left;">
                    <th style="padding: 14px 20px;">Catégorie</th>
                    <th style="padding: 14px 20px;">Libellé</th>
                    <th style="padding: 14px 20px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($libelles as $libelle)
                    <tr style="border-top: 1px solid #f0ebe5;">
                        <td style="padding: 14px 20px; color: #b8860b; font-weight: 600;">{{ $libelle->categorie->nom }}</td>
                        <td style="padding: 14px 20px;">{{ $libelle->nom }}</td>
                        <td style="padding: 14px 20px; text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.libelle-depenses.edit', $libelle) }}" style="color: #2d5a27; margin-right: 14px; text-decoration: none;">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('admin.libelle-depenses.destroy', $libelle) }}" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Supprimer ce libellé ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0;">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 40px; text-align: center; color: #6c757d;">
                            <i class="fas fa-info-circle" style="color: #b8860b;"></i> Aucun libellé enregistré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">{{ $libelles->links() }}</div>
</div>
@endsection