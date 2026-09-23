{{-- resources/views/admin/rapports/_entete_minimal.blade.php --}}
@php
    $titresOnglets = [
        'vue-ensemble' => 'Vue d\'ensemble',
        'mouvements'   => 'Mouvements de stock',
        'produits'     => 'Produits',
        'achats'       => 'Achats',
        'fournisseurs' => 'Fournisseurs',
        'users'        => 'Utilisateurs',
        'ventes'       => 'Total ventes',
    ];
@endphp

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
    <div>
        <a href="{{ route('admin.rapports.stock') }}" style="color: #6c757d; font-size: 0.85rem; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Retour aux rapports
        </a>
        <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.7rem; margin: 8px 0 0 0;">
            <i class="fas fa-chart-bar" style="color: #b8860b; margin-right: 10px;"></i>
            {{ $titresOnglets[$onglet] ?? ucfirst($onglet) }}
        </h1>
        <p style="color: #6c757d; margin: 5px 0 0 0;">
            Période du {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.rapports.stock_pdf', ['onglet' => $onglet, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="
            background: #2d5a27; color: white; padding: 12px 22px; border-radius: 30px;
            text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
            <i class="fas fa-file-pdf"></i> Exporter en PDF
        </a>
        <button type="button" onclick="window.print()" style="
            background: #f0ebe5; color: #2d5a27; padding: 12px 22px; border-radius: 30px; border: none;
            font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
            <i class="fas fa-print"></i> Imprimer
        </button>
    </div>
</div>