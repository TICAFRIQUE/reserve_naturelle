@extends('layouts.admin')

@section('title', 'Stock - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                    <i class="fas fa-warehouse" style="color: #2d5a27; margin-right: 10px;"></i>
                    Stock
                </h1>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.stock-mouvements.index') }}" style="
                        background: white;
                        color: #2d5a27;
                        padding: 10px 22px;
                        border-radius: 30px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 0.9rem;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#f8f5f0'" onmouseout="this.style.background='white'">
                        <i class="fas fa-clock-rotate-left"></i> Historique des mouvements
                    </a>
                    <a href="{{ route('admin.stock-ajustements.create') }}" style="
                        background: #b8860b;
                        color: white;
                        padding: 10px 22px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 0.9rem;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#9a7209'" onmouseout="this.style.background='#b8860b'">
                        <i class="fas fa-sliders-h"></i> Casse / ajustement
                    </a>
                </div>
            </div>

            <!-- KPI -->
            <div style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 20px;
                margin-bottom: 25px;
            ">
                <div style="background: white; padding: 22px 25px; border-radius: 12px; border: 1px solid #e8e0d5; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <p style="color: #6c757d; margin: 0 0 6px 0; font-size: 0.9rem;">Pièces en stock</p>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: #2d5a27; margin: 0;">{{ $stats['pieces_stock'] }}</h3>
                </div>
                <div style="background: white; padding: 22px 25px; border-radius: 12px; border: 1px solid #e8e0d5; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <p style="color: #6c757d; margin: 0 0 6px 0; font-size: 0.9rem;">Valeur du stock (CMP)</p>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: #b8860b; margin: 0;">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} F</h3>
                </div>
                <div style="background: white; padding: 22px 25px; border-radius: 12px; border: 1px solid #e8e0d5; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <p style="color: #6c757d; margin: 0 0 6px 0; font-size: 0.9rem;">Produits sous seuil</p>
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: #dc3545; margin: 0;">{{ $stats['produits_sous_seuil'] }}</h3>
                </div>
            </div>

            <!-- Filtre -->
            <form action="{{ route('admin.stock.index') }}" method="GET" style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; color: #2d5a27; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="sous_seuil" value="1" onchange="this.form.submit()" {{ request()->boolean('sous_seuil') ? 'checked' : '' }}
                        style="width: 16px; height: 16px; cursor: pointer;">
                    Sous le seuil d'alerte uniquement
                </label>
            </form>

            <!-- Tableau -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                overflow: hidden;
            ">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                            <tr>
                                <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Produit</th>
                                <th style="padding: 14px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Quantité</th>
                                <th style="padding: 14px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Seuil d'alerte</th>
                                <th style="padding: 14px 20px; text-align: right; font-weight: 600; color: #2d5a27;">Coût moyen pondéré</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                @php $sousSeuil = $product->qte_dispo <= $product->stock_minimum; @endphp
                                <tr style="border-bottom: 1px solid #f0ebe5; {{ $sousSeuil ? 'background: #fdeeee;' : '' }}">
                                    <td style="padding: 13px 20px; color: #2d5a27; font-weight: 500;">
                                        {{ $product->designation }}
                                        <span style="color: #b8860b; font-size: 0.8rem; margin-left: 6px;">{{ $product->reference_prod }}</span>
                                    </td>
                                    <td style="padding: 13px 20px; text-align: center;">
                                        {{ $product->qte_dispo }} pièces
                                        @if($sousSeuil)
                                            <i class="fas fa-triangle-exclamation" style="color: #dc3545; margin-left: 4px;"></i>
                                        @endif
                                    </td>
                                    <td style="padding: 13px 20px; text-align: center; color: #6c757d;">
                                        {{ $product->stock_minimum }}
                                    </td>
                                    <td style="padding: 13px 20px; text-align: right; color: #2d5a27; font-weight: 500;">
                                        {{ number_format($product->cmp, 0, ',', ' ') }} F
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-box-open" style="font-size: 36px; display: block; margin-bottom: 12px; color: #d4c9bb;"></i>
                                        Aucun produit trouvé
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