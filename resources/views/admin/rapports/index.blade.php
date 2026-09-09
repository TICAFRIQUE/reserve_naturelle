@extends('layouts.admin')

@section('title', 'Rapports - La Réserve Naturelle')
@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div style="margin-bottom: 30px;">
                <h1 style="
                    font-family: 'Playfair Display', serif;
                    color: #2d5a27;
                    font-size: 2rem;
                    margin: 0;
                ">
                    <i class="fas fa-chart-line"
                       style="color: #2d5a27; margin-right: 10px;"></i>
                    Rapports
                </h1>
                <p style="
                    color: #6c757d;
                    margin: 5px 0 0 0;
                ">
                    Bilan CA, dépenses et marge par période
                </p>
            </div>
            <!--
            ================================================================
            FILTRE PAR DATE
            ================================================================
            -->
            <form method="GET"
                  action="{{ route('admin.rapports.index') }}"
                  style="
                      display: flex;
                      align-items: end;
                      gap: 15px;
                      margin-bottom: 25px;
                      flex-wrap: wrap;
                  ">
                <!-- Date début -->
                <div>
                    <label for="date_from"
                           style="
                               display: block;
                               color: #2d5a27;
                               font-weight: 600;
                               font-size: 0.85rem;
                               margin-bottom: 6px;
                           ">
                        Date de début
                    </label>
                    <input
                        type="date"
                        id="date_from"
                        name="date_from"
                        value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}"
                        style="
                            padding: 9px 12px;
                            border: 1px solid #e8e0d5;
                            border-radius: 8px;
                            color: #495057;
                            background: white;
                            outline: none;
                        "
                    >
                </div>
                <!-- Date fin -->
                <div>
                    <label for="date_to"
                           style="
                               display: block;
                               color: #2d5a27;
                               font-weight: 600;
                               font-size: 0.85rem;
                               margin-bottom: 6px;
                           ">
                        Date de fin
                    </label>
                    <input
                        type="date"
                        id="date_to"
                        name="date_to"
                        value="{{ request('date_to', $dateTo->format('Y-m-d')) }}"
                        style="
                            padding: 9px 12px;
                            border: 1px solid #e8e0d5;
                            border-radius: 8px;
                            color: #495057;
                            background: white;
                            outline: none;
                        "
                    >
                </div>
                <!-- Bouton filtrer -->
                <button
                    type="submit"
                    style="
                        padding: 9px 20px;
                        border-radius: 8px;
                        border: none;
                        cursor: pointer;
                        background: #2d5a27;
                        color: white;
                        font-weight: 600;
                        font-size: 0.85rem;
                    "
                >
                    <i class="fas fa-filter"></i>
                    Filtrer
                </button>
                <!-- Réinitialiser -->
                @if($hasFilter)
                    <a
                        href="{{ route('admin.rapports.index') }}"
                        style="
                            padding: 9px 20px;
                            border-radius: 8px;
                            border: 1px solid #e8e0d5;
                            background: white;
                            color: #6c757d;
                            text-decoration: none;
                            font-weight: 500;
                            font-size: 0.85rem;
                        "
                    >
                        <i class="fas fa-rotate-left"></i>
                        Réinitialiser
                    </a>
                @endif
            </form>
            <!--
            ================================================================
            PÉRIODE AFFICHÉE
            ================================================================
            -->
            <div style="
                margin-bottom: 30px;
                padding: 12px 18px;
                background: #faf8f5;
                border: 1px solid #e8e0d5;
                border-radius: 8px;
                color: #6c757d;
                font-size: 0.9rem;
            ">
                <i class="fas fa-calendar-alt"
                   style="color: #2d5a27; margin-right: 6px;"></i>
                Période analysée :
                <strong style="color: #2d5a27;">
                    {{ $dateFrom->format('d/m/Y') }}
                </strong>
                au
                <strong style="color: #2d5a27;">
                    {{ $dateTo->format('d/m/Y') }}
                </strong>
            </div>
            <!--
            ================================================================
            KPI CA / DÉPENSES / MARGE
            ================================================================
            -->
            <div style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 20px;
                margin-bottom: 40px;
            ">
                <!-- CA -->
                <div style="
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                ">
                    <div style="
                        color: #6c757d;
                        font-size: 0.9rem;
                        margin-bottom: 8px;
                    ">
                        <i class="fas fa-money-bill-wave"
                           style="color: #b8860b;"></i>

                        Chiffre d'affaires
                    </div>
                    <div style="
                        font-size: 1.8rem;
                        font-weight: 700;
                        color: #b8860b;
                    ">
                        {{ number_format($ca, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <!-- Dépenses -->
                <div style="
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                ">
                    <div style="
                        color: #6c757d;
                        font-size: 0.9rem;
                        margin-bottom: 8px;
                    ">
                        <i class="fas fa-wallet"
                           style="color: #c62828;"></i>
                        Dépenses (achats)
                    </div>
                    <div style="
                        font-size: 1.8rem;
                        font-weight: 700;
                        color: #c62828;
                    ">
                        {{ number_format($depenses, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <!-- Marge -->
                <div style="
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                ">
                    <div style="
                        color: #6c757d;
                        font-size: 0.9rem;
                        margin-bottom: 8px;
                    ">
                        <i class="fas fa-{{ $marge >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}"
                           style="
                               color: {{ $marge >= 0 ? '#28a745' : '#dc3545' }};
                           ">
                        </i>
                        Marge
                        {{ $marge >= 0 ? 'bénéficiaire' : 'déficitaire' }}

                    </div>
                    <div style="
                        font-size: 1.8rem;
                        font-weight: 700;
                        color: {{ $marge >= 0 ? '#28a745' : '#dc3545' }};
                    ">
                        {{ $marge >= 0 ? '+' : '' }}
                        {{ number_format($marge, 0, ',', ' ') }}
                        FCFA
                    </div>
                </div>
            </div>
            <!--
            ================================================================
            GRAPHIQUE CA VS DÉPENSES
            ================================================================
            -->
            <div style="
                background: white;
                border-radius: 12px;
                padding: 25px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                margin-bottom: 40px;
            ">
                <h4 style="
                    color: #2d5a27;
                    font-weight: 600;
                    margin-bottom: 20px;
                ">
                    <i class="fas fa-chart-area"></i>
                    Évolution du CA et des dépenses
                </h4>
                <canvas id="evolutionChart" height="80"></canvas>
            </div>
            <!--
            ================================================================
            VENTES
            ================================================================
            -->

            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                overflow: hidden;
                margin-bottom: 30px;
            ">
                <div style="
                    padding: 18px 25px;
                    background: #faf8f5;
                    border-bottom: 1px solid #e8e0d5;
                ">
                    <h2 style="
                        font-size: 1.2rem;
                        color: #2d5a27;
                        margin: 0;
                    ">
                        <i class="fas fa-shopping-cart"></i>
                        Ventes ({{ $ventes->total() }})
                    </h2>
                </div>
                <div style="overflow-x: auto;">
                    <table style="
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 0.9rem;
                    ">
                        <thead style="background: #f8f5f0;">
                            <tr>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: left;
                                    color: #2d5a27;
                                ">
                                    Référence
                                </th>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: left;
                                    color: #2d5a27;
                                ">
                                    Client
                                </th>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: right;
                                    color: #2d5a27;
                                ">
                                    Montant
                                </th>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: center;
                                    color: #2d5a27;
                                ">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventes as $vente)
                                <tr style="
                                    border-bottom: 1px solid #f0ebe5;
                                ">
                                    <td style="
                                        padding: 10px 20px;
                                        font-weight: 600;
                                        color: #2d5a27;
                                    ">
                                        #{{ $vente->num_order }}
                                    </td>
                                    <td style="padding: 10px 20px;">
                                        {{ $vente->user->full_name ?? 'N/A' }}
                                    </td>
                                    <td style="
                                        padding: 10px 20px;
                                        text-align: right;
                                        font-weight: 600;
                                        color: #2d5a27;
                                    ">
                                        {{ number_format($vente->montant_ttc, 0, ',', ' ') }}
                                        FCFA
                                    </td>
                                    <td style="
                                        padding: 10px 20px;
                                        text-align: center;
                                        color: #6c757d;
                                    ">
                                        {{ $vente->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        style="
                                            padding: 25px;
                                            text-align: center;
                                            color: #6c757d;
                                        ">
                                        Aucune vente sur cette période
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="padding: 15px 25px;">
                    {{ $ventes->appends(request()->query())->links() }}
                </div>
            </div>
            <!--
            ================================================================
            ACHATS
            ================================================================
            -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                overflow: hidden;
            ">
                <div style="
                    padding: 18px 25px;
                    background: #faf8f5;
                    border-bottom: 1px solid #e8e0d5;
                ">
                    <h2 style="
                        font-size: 1.2rem;
                        color: #2d5a27;
                        margin: 0;
                    ">
                        <i class="fas fa-truck"></i>
                        Achats ({{ $achats->total() }})
                    </h2>
                </div>
                <div style="overflow-x: auto;">
                    <table style="
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 0.9rem;
                    ">
                        <thead style="background: #f8f5f0;">
                            <tr>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: left;
                                    color: #2d5a27;
                                ">
                                    N°
                                </th>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: left;
                                    color: #2d5a27;
                                ">
                                    Fournisseur
                                </th>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: right;
                                    color: #2d5a27;
                                ">
                                    Montant
                                </th>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: center;
                                    color: #2d5a27;
                                ">
                                    Date achat
                                </th>
                                <th style="
                                    padding: 10px 20px;
                                    text-align: center;
                                    color: #2d5a27;
                                ">
                                    Statut
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($achats as $achat)
                                <tr style="
                                    border-bottom: 1px solid #f0ebe5;
                                ">
                                    <td style="
                                        padding: 10px 20px;
                                        font-weight: 600;
                                        color: #2d5a27;
                                    ">
                                        {{ $achat->numero }}
                                    </td>
                                    <td style="padding: 10px 20px;">
                                        {{ $achat->fournisseur->nom ?? 'N/A' }}
                                    </td>
                                    <td style="
                                        padding: 10px 20px;
                                        text-align: right;
                                        font-weight: 600;
                                        color: #c62828;
                                    ">
                                        {{ number_format($achat->mt_total, 0, ',', ' ') }}
                                        FCFA
                                    </td>
                                    <td style="
                                        padding: 10px 20px;
                                        text-align: center;
                                        color: #6c757d;
                                    ">
                                        {{ $achat->date_achat?->format('d/m/Y') }}
                                    </td>
                                    <td style="
                                        padding: 10px 20px;
                                        text-align: center;
                                        color: #6c757d;
                                    ">
                                        {{ $achat->statut }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        style="
                                            padding: 25px;
                                            text-align: center;
                                            color: #6c757d;
                                        ">
                                        Aucun achat sur cette période
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="padding: 15px 25px;">
                    {{ $achats->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

<script>

const evolutionData = @json($evolution);
new Chart(document.getElementById('evolutionChart'), {
    type: 'line',
    data: {
        labels: evolutionData.map(item => item.label),
        datasets: [
            {
                label: 'CA',
                data: evolutionData.map(item => item.ca),
                borderColor: '#2d5a27',
                backgroundColor: 'rgba(45,90,39,0.08)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Dépenses',
                data: evolutionData.map(item => item.depenses),
                borderColor: '#c62828',
                backgroundColor: 'rgba(198,40,40,0.06)',
                fill: true,
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                display: true
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': '
                            + new Intl.NumberFormat('fr-FR').format(context.parsed.y)
                            + ' FCFA';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('fr-FR').format(value)
                            + ' FCFA';
                    }
                }
            }
        }
    }
});
</script>
@endpush