<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport - La Réserve Naturelle</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Lato:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Lato', Arial, sans-serif;
            color: #2F2A22;
            margin: 0;
            padding: 30px 40px;
        }

        h1, h2 {
            font-family: 'Playfair Display', Georgia, serif;
            color: #2d5a27;
            margin: 0;
        }

        .print-toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }

        .print-toolbar button {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-print { background: #2d5a27; color: white; }
        .btn-close { background: #e8e0d5; color: #2F2A22; }

        .entete {
            margin-bottom: 20px;
        }

        .entete p {
            color: #6c757d;
            margin: 5px 0 0 0;
        }

        .periode {
            padding: 12px 18px;
            background: #faf8f5;
            border: 1px solid #e8e0d5;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .kpi-card {
            border: 1px solid #e8e0d5;
            border-radius: 10px;
            padding: 18px 20px;
        }

        .kpi-card .label { color: #6c757d; font-size: 0.9rem; }
        .kpi-card .value { font-size: 1.6rem; font-weight: 800; margin-top: 6px; }
        .value.gold { color: #b8860b; }
        .value.danger { color: #c62828; }
        .value.success { color: #2e7d32; }

        section.print-page {
            page-break-before: always;
            break-before: page;
        }

        section.print-page:first-of-type {
            page-break-before: auto;
            break-before: auto;
        }
        section.print-page.chart-page {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
        }

        .chart-page .section-title {
            text-align: center;
            width: 100%;
        }

        .section-title {
            font-family: 'Playfair Display', Georgia, serif;
            color: #2d5a27;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

            .chart-wrap {
            border: 1px solid #e8e0d5;
            border-radius: 10px;
            padding: 30px;
            width: 85%;
            max-width: 850px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        thead th {
            text-align: left;
            padding: 10px 12px;
            border-bottom: 2px solid #2d5a27;
            color: #2d5a27;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #eee;
        }

        .montant { color: #c62828; font-weight: 700; }
        .montant.ca { color: #2e7d32; }

        @media print {
            .print-toolbar { display: none !important; }
            body { padding: 0 20px; }
        }
    </style>
</head>
<body>

    <div class="print-toolbar">
        <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
        <button class="btn-close" onclick="window.close()">Fermer</button>
    </div>

    <!-- PAGE 1 : En-tête + KPI -->
    <section class="print-page">
        <div class="entete">
            <h1><i class="fas fa-chart-line"></i> Rapports</h1>
            <p>Bilan CA, dépenses et marge par période</p>
        </div>

        <div class="periode">
            <i class="fas fa-calendar-alt"></i>
            Période analysée : <strong>{{ $dateFrom->format('d/m/Y') }}</strong> au <strong>{{ $dateTo->format('d/m/Y') }}</strong>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="label"><i class="fas fa-money-bill-wave"></i> Chiffre d'affaires</div>
                <div class="value gold">{{ number_format($ca, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="kpi-card">
                <div class="label"><i class="fas fa-wallet"></i> Dépenses (achats)</div>
                <div class="value danger">{{ number_format($depenses, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="kpi-card">
                <div class="label"><i class="fas fa-{{ $marge >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}"></i> Marge {{ $marge >= 0 ? 'bénéficiaire' : 'déficitaire' }}</div>
                <div class="value {{ $marge >= 0 ? 'success' : 'danger' }}">{{ number_format($marge, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </section>

    <!-- PAGE 2 : Graphique seul, jamais coupé -->
    <section class="print-page">
        <div class="section-title"><i class="fas fa-chart-area"></i> Évolution du CA et des dépenses</div>
        <div class="chart-wrap">
            <canvas id="evolutionChart" height="90"></canvas>
        </div>
    </section>

    <!-- PAGE 3 : Ventes -->
    <section class="print-page">
        <div class="section-title"><i class="fas fa-shopping-cart"></i> Ventes ({{ $ventes->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $vente)
                    <tr>
                        <td>#{{ $vente->num_order }}</td>
                        <td>{{ $vente->user->prenom ?? '' }} {{ $vente->user->nom ?? 'Client supprimé' }}</td>
                        <td class="montant ca">{{ number_format($vente->montant_ttc, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $vente->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Aucune vente sur cette période.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <!-- PAGE 4 : Achats -->
    <section class="print-page">
        <div class="section-title"><i class="fas fa-truck"></i> Achats ({{ $achats->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Fournisseur</th>
                    <th>Montant</th>
                    <th>Date achat</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achats as $achat)
                    <tr>
                        <td>{{ $achat->numero }}</td>
                        <td>{{ $achat->fournisseur->nom ?? '—' }}</td>
                        <td class="montant">{{ number_format($achat->mt_total, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $achat->date_achat->format('d/m/Y') }}</td>
                        <td>{{ $achat->statut }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Aucun achat sur cette période.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: @json($evolution->pluck('label')),
                datasets: [
                    {
                        label: 'CA',
                        data: @json($evolution->pluck('ca')),
                        borderColor: '#2d5a27',
                        backgroundColor: 'rgba(45, 90, 39, 0.1)',
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Dépenses',
                        data: @json($evolution->pluck('depenses')),
                        borderColor: '#c62828',
                        backgroundColor: 'rgba(198, 40, 40, 0.08)',
                        fill: true,
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.4,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => new Intl.NumberFormat('fr-FR').format(value) + ' FCFA'
                        }
                    }
                }
            }
        });

        // Ouvre directement le dialogue d'impression une fois le graphique rendu
        window.addEventListener('load', () => setTimeout(() => window.print(), 300));
    </script>
</body>
</html>