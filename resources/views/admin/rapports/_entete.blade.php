{{-- resources/views/admin/rapports/_entete.blade.php --}}
{{-- Utilisé UNIQUEMENT par vue-ensemble.blade.php (via _layout_principal) --}}

<style>
    .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-title { font-family:'Playfair Display', serif; color:#2d5a27; font-size:2rem; margin:0; }
    .page-subtitle { color:#6c757d; margin:5px 0 0 0; }
    .btn-actions { display:flex; gap:10px; flex-wrap:wrap; }
    .btn-primary { background:#2d5a27; color:white; padding:12px 22px; border-radius:30px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px; border:none; cursor:pointer; }
    .btn-primary:hover { background:#1e3d1a; }
    .btn-secondary { background:#f0ebe5; color:#2d5a27; padding:12px 22px; border-radius:30px; border:none; font-weight:500; cursor:pointer; display:inline-flex; align-items:center; gap:8px; }
    .btn-secondary:hover { background:#e0d6c8; }

    .filter-box { background:#f8f5f0; padding:18px 20px; border-radius:12px; margin-bottom:20px; border:1px solid #e8e0d5; }
    .filter-form { display:flex; flex-wrap:wrap; gap:15px; align-items:flex-end; }
    .filter-field { flex:1; min-width:150px; }
    .filter-label { display:block; font-weight:600; color:#2d5a27; font-size:0.9rem; margin-bottom:5px; }
    .filter-input { width:100%; padding:10px 15px; border:1px solid #e8e0d5; border-radius:8px; font-size:0.95rem; outline:none; background:white; }

    .kpi-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:25px; }
    .kpi-card { background:white; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); border:1px solid #e8e0d5; padding:22px; text-decoration:none; display:block; transition:transform 0.15s; }
    .kpi-card.clickable:hover { transform:translateY(-3px); }
    .kpi-icon { width:46px; height:46px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:12px; font-size:18px; }
    .kpi-label { color:#6c757d; font-size:0.85rem; }
    .kpi-value { font-size:1.3rem; font-weight:700; }
    .kpi-delta { font-size:0.8rem; }
    .kpi-delta .sub { color:#6c757d; }
    .kpi-link { font-size:0.75rem; color:#b8860b; margin-top:4px; }

    .tabs { display:flex; gap:5px; border-bottom:2px solid #e8e0d5; margin-bottom:25px; flex-wrap:wrap; }
    .tab { padding:12px 18px; text-decoration:none; font-weight:600; font-size:0.92rem; color:#6c757d; border-bottom:3px solid transparent; margin-bottom:-2px; transition:color 0.2s; }
    .tab.active { color:#2d5a27; border-bottom-color:#2d5a27; }
    .tab i { font-size:0.7em; opacity:0.6; }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-chart-bar" style="color:#b8860b; margin-right:10px;"></i> Rapports - Gestion de stock</h1>
        <p class="page-subtitle">Consultez les rapports et statistiques de votre stock</p>
    </div>
    <div class="btn-actions">
        <a href="{{ route('admin.rapports.stock_pdf', ['onglet' => $onglet, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="btn-primary">
            <i class="fas fa-file-pdf"></i> Exporter en PDF
        </a>
        <button type="button" onclick="window.print()" class="btn-secondary">
            <i class="fas fa-print"></i> Imprimer
        </button>
    </div>
</div>

<div class="filter-box">
    <form action="{{ route('admin.rapports.stock') }}" method="GET" class="filter-form">
        <input type="hidden" name="onglet" value="{{ $onglet }}">
        <div class="filter-field">
            <label class="filter-label">Du</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="filter-input">
        </div>
        <div class="filter-field">
            <label class="filter-label">Au</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="filter-input">
        </div>
        <button type="submit" class="btn-primary" style="padding:10px 25px;"><i class="fas fa-filter"></i> Filtrer</button>
    </form>
</div>

@php
    $delta = function ($v, $inverse = false) {
        if (is_null($v)) return '';
        $bon = $inverse ? $v <= 0 : $v >= 0;
        $color = $bon ? '#2e7d32' : '#c62828';
        $arrow = $v >= 0 ? 'up' : 'down';
        $sign = $v > 0 ? '+' : '';
        return sprintf('<div class="kpi-delta" style="color:%s;"><i class="fas fa-arrow-%s"></i> %s%s%% <span class="sub">vs période précédente</span></div>', $color, $arrow, $sign, $v);
    };

    $resultat = (float) ($resultat_exploitation ?? 0);
    $resultatPositif = $resultat >= 0;
@endphp

<div class="kpi-grid">

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#e8f5e9;"><i class="fas fa-box" style="color:#2d5a27;"></i></div>
        <div class="kpi-label">Valeur du stock actuel</div>
        <div class="kpi-value" style="color:#2d5a27;">{{ number_format($valeur_stock ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#e3f2fd;"><i class="fas fa-cart-arrow-down" style="color:#1565c0;"></i></div>
        <div class="kpi-label">Total des entrées (achats)</div>
        <div class="kpi-value" style="color:#2d5a27;">{{ number_format($entrees ?? 0, 0, ',', ' ') }} FCFA</div>
        {!! $delta($entrees_var ?? null) !!}
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fff3e0;"><i class="fas fa-exchange-alt" style="color:#e65100;"></i></div>
        <div class="kpi-label">Total des sorties (ventes)</div>
        <div class="kpi-value" style="color:#2d5a27;">{{ number_format($sorties ?? 0, 0, ',', ' ') }} FCFA</div>
        {!! $delta($sorties_var ?? null) !!}
    </div>

    <a href="{{ route('admin.depenses.index', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="kpi-card clickable">
        <div class="kpi-icon" style="background:#f8d7da;"><i class="fas fa-wallet" style="color:#721c24;"></i></div>
        <div class="kpi-label">Dépenses</div>
        <div class="kpi-value" style="color:#721c24;">{{ number_format($depenses ?? 0, 0, ',', ' ') }} FCFA</div>
        {!! $delta($depenses_var ?? null, inverse: true) !!}
        <div class="kpi-link"><i class="fas fa-arrow-right"></i> Voir le détail</div>
    </a>

    <a href="{{ route('admin.rapports.compte_exploitation', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="kpi-card clickable">
        <div class="kpi-icon" style="background:{{ $resultatPositif ? '#e8f5e9' : '#fdecea' }};"><i class="fas fa-balance-scale" style="color:{{ $resultatPositif ? '#2d5a27' : '#c62828' }};"></i></div>
        <div class="kpi-label">Résultat d'exploitation</div>
        <div class="kpi-value" style="color:{{ $resultatPositif ? '#2d5a27' : '#c62828' }};">{{ number_format($resultat, 0, ',', ' ') }} FCFA</div>
        {!! $delta($resultat_exploitation_var ?? null) !!}
        <div class="kpi-link"><i class="fas fa-arrow-right"></i> Voir le détail</div>
    </a>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fdecea;"><i class="fas fa-exclamation-triangle" style="color:#c62828;"></i></div>
        <div class="kpi-label">Produits en alerte (stock faible)</div>
        <div class="kpi-value" style="color:#2d5a27;">{{ $produits_alerte ?? 0 }}</div>
        <div style="font-size:0.8rem; color:#c62828;">{{ $produits_rupture ?? 0 }} produit(s) en rupture</div>
    </div>

</div>

<div class="tabs">
    <a href="{{ route('admin.rapports.stock', ['onglet' => 'vue-ensemble', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="tab active">Vue d'ensemble</a>

    @foreach([
        'mouvements'   => 'Mouvements de stock',
        'produits'     => 'Produits',
        'achats'       => 'Achats',
        'fournisseurs' => 'Fournisseurs',
        'users'        => 'Utilisateurs',
        'ventes'       => 'Total ventes',
        'depenses'     => 'Dépenses',
    ] as $key => $label)
        <a href="{{ route('admin.rapports.stock', ['onglet' => $key, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" target="_blank" rel="noopener" class="tab">
            {{ $label }} <i class="fas fa-external-link-alt"></i>
        </a>
    @endforeach
</div>