{{-- resources/views/admin/rapports/_entete.blade.php --}}
{{-- Utilisé UNIQUEMENT par vue-ensemble.blade.php (via _layout_principal) --}}
{{-- Entête --}}
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
            <i class="fas fa-chart-bar" style="color: #b8860b; margin-right: 10px;"></i>
            Rapports - Gestion de stock
        </h1>
        <p style="color: #6c757d; margin: 5px 0 0 0;">Consultez les rapports et statistiques de votre stock</p>
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

{{-- Filtre dates --}}
<div style="background: #f8f5f0; padding: 18px 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e8e0d5;">
    <form action="{{ route('admin.rapports.stock') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">Du</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" style="width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem; outline: none; background: white;">
        </div>
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">Au</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" style="width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem; outline: none; background: white;">
        </div>
        <button type="submit" style="background: #2d5a27; color: white; padding: 10px 25px; border-radius: 30px; border: none; font-weight: 500; cursor: pointer;">
            <i class="fas fa-filter"></i> Filtrer
        </button>
    </form>
</div>

{{-- Cartes KPI --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px;">
        <div style="width: 46px; height: 46px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
            <i class="fas fa-box" style="color: #2d5a27; font-size: 18px;"></i>
        </div>
        <div style="color: #6c757d; font-size: 0.85rem;">Valeur du stock actuel</div>
        <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ number_format($valeur_stock, 0, ',', ' ') }} FCFA</div>
    </div>
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px;">
        <div style="width: 46px; height: 46px; border-radius: 10px; background: #e3f2fd; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
            <i class="fas fa-cart-arrow-down" style="color: #1565c0; font-size: 18px;"></i>
        </div>
        <div style="color: #6c757d; font-size: 0.85rem;">Total des entrées (achats)</div>
        <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ number_format($entrees, 0, ',', ' ') }} FCFA</div>
        @if(!is_null($entrees_var))
            <div style="font-size: 0.8rem; color: {{ $entrees_var >= 0 ? '#2e7d32' : '#c62828' }};">
                <i class="fas fa-arrow-{{ $entrees_var >= 0 ? 'up' : 'down' }}"></i> {{ abs($entrees_var) }}% vs période précédente
            </div>
        @endif
    </div>
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px;">
        <div style="width: 46px; height: 46px; border-radius: 10px; background: #fff3e0; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
            <i class="fas fa-exchange-alt" style="color: #e65100; font-size: 18px;"></i>
        </div>
        <div style="color: #6c757d; font-size: 0.85rem;">Total des sorties (ventes)</div>
        <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ number_format($sorties, 0, ',', ' ') }} FCFA</div>
        @if(!is_null($sorties_var))
            <div style="font-size: 0.8rem; color: {{ $sorties_var >= 0 ? '#2e7d32' : '#c62828' }};">
                <i class="fas fa-arrow-{{ $sorties_var >= 0 ? 'up' : 'down' }}"></i> {{ abs($sorties_var) }}% vs période précédente
            </div>
        @endif
    </div>
    <a href="{{ route('admin.depenses.index', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="text-decoration: none; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: block; transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
        <div style="width: 46px; height: 46px; border-radius: 10px; background: #f8d7da; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
            <i class="fas fa-wallet" style="color: #721c24; font-size: 18px;"></i>
        </div>
        <div style="color: #6c757d; font-size: 0.85rem;">Dépenses</div>
        <div style="color: #721c24; font-size: 1.3rem; font-weight: 700;">{{ number_format($depenses, 0, ',', ' ') }} FCFA</div>
        @if(!is_null($depenses_var))
            <div style="font-size: 0.8rem; color: {{ $depenses_var <= 0 ? '#2e7d32' : '#c62828' }};">
                <i class="fas fa-arrow-{{ $depenses_var >= 0 ? 'up' : 'down' }}"></i> {{ abs($depenses_var) }}% vs période précédente
            </div>
        @endif
        <div style="font-size: 0.75rem; color: #b8860b; margin-top: 4px;"><i class="fas fa-arrow-right"></i> Voir le détail</div>
    </a>
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px;">
        <div style="width: 46px; height: 46px; border-radius: 10px; background: #fdecea; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
            <i class="fas fa-exclamation-triangle" style="color: #c62828; font-size: 18px;"></i>
        </div>
        <div style="color: #6c757d; font-size: 0.85rem;">Produits en alerte (stock faible)</div>
        <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ $produits_alerte }}</div>
        <div style="font-size: 0.8rem; color: #c62828;">{{ $produits_rupture }} produit(s) en rupture</div>
    </div>
</div>

{{-- Onglets — chaque clic ouvre la page dans un nouvel onglet navigateur --}}
<div style="display: flex; gap: 5px; border-bottom: 2px solid #e8e0d5; margin-bottom: 25px; flex-wrap: wrap;">
    <a href="{{ route('admin.rapports.stock', ['onglet' => 'vue-ensemble', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="
        padding: 12px 18px; text-decoration: none; font-weight: 600; font-size: 0.92rem;
        color: #2d5a27; border-bottom: 3px solid #2d5a27; margin-bottom: -2px;
    ">Vue d'ensemble</a>
    @foreach([
        'mouvements'   => ['Mouvements de stock', route('admin.rapports.stock', ['onglet' => 'mouvements', 'date_from' => $dateFrom, 'date_to' => $dateTo])],
        'produits'     => ['Produits', route('admin.rapports.stock', ['onglet' => 'produits', 'date_from' => $dateFrom, 'date_to' => $dateTo])],
        'achats'       => ['Achats', route('admin.rapports.stock', ['onglet' => 'achats', 'date_from' => $dateFrom, 'date_to' => $dateTo])],
        'fournisseurs' => ['Fournisseurs', route('admin.rapports.stock', ['onglet' => 'fournisseurs', 'date_from' => $dateFrom, 'date_to' => $dateTo])],
        'users'        => ['Utilisateurs', route('admin.rapports.stock', ['onglet' => 'users', 'date_from' => $dateFrom, 'date_to' => $dateTo])],
        'ventes'       => ['Total ventes', route('admin.rapports.stock', ['onglet' => 'ventes', 'date_from' => $dateFrom, 'date_to' => $dateTo])],
        'depenses'     => ['Dépenses', route('admin.rapports.stock', ['onglet' => 'depenses', 'date_from' => $dateFrom, 'date_to' => $dateTo])],
    ] as $key => [$label, $url])
        <a href="{{ $url }}" target="_blank" rel="noopener" style="
            padding: 12px 18px; text-decoration: none; font-weight: 600; font-size: 0.92rem;
            color: #6c757d; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: color 0.2s;
        ">{{ $label }} <i class="fas fa-external-link-alt" style="font-size: 0.7em; opacity: 0.6;"></i></a>
    @endforeach
</div>