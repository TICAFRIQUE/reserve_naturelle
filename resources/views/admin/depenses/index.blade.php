@extends('layouts.admin')

@section('title', 'Dépenses - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- Entête --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-wallet" style="margin-right: 10px;"></i>
                        Dépenses
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Paiements réellement effectués aux fournisseurs</p>
                </div>
            </div>

            {{-- Messages flash --}}
            @if(session('success'))
                <div style="background:#d4edda;color:#155724;padding:12px 20px;border-radius:8px;border-left:4px solid #28a745;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-check-circle" style="font-size:20px;"></i> {{ session('success') }}
                </div>
            @endif

            {{-- 3 indicateurs --}}
            <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:25px;">
                <div style="flex:1;min-width:220px;background:#f8f5f0;border:1px solid #e8e0d5;border-radius:12px;padding:20px 24px;">
                    <div style="color:#6c757d;font-size:0.85rem;font-weight:600;margin-bottom:6px;">Total payé</div>
                    <div style="font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:#721c24;">
                        {{ number_format($totalPeriode, 0, ',', ' ') }} <span style="font-size:1rem;font-weight:500;">FCFA</span>
                    </div>
                </div>
                <div style="flex:1;min-width:220px;background:#f8f5f0;border:1px solid #e8e0d5;border-radius:12px;padding:20px 24px;">
                    <div style="color:#6c757d;font-size:0.85rem;font-weight:600;margin-bottom:6px;">Nombre de paiements</div>
                    <div style="font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:#2d5a27;">
                        {{ $nombrePaiements }}
                    </div>
                </div>
                <div style="flex:1;min-width:220px;background:#f8f5f0;border:1px solid #e8e0d5;border-radius:12px;padding:20px 24px;">
                    <div style="color:#6c757d;font-size:0.85rem;font-weight:600;margin-bottom:6px;">Paiement moyen</div>
                    <div style="font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:#2d5a27;">
                        {{ number_format($paiementMoyen, 0, ',', ' ') }} <span style="font-size:1rem;font-weight:500;">FCFA</span>
                    </div>
                </div>
            </div>

            {{-- Graphique Style Aire Lissée (Rouge) --}}
            <div style="background:white;border:1px solid #e8e0d5;border-radius:12px;padding:20px;margin-bottom:25px;">
                @if($evolution->isEmpty())
                    <p style="color:#6c757d;text-align:center;padding:30px 0;margin:0;">Aucun paiement à afficher pour cette sélection.</p>
                @else
                    <div style="position:relative; height:350px; width:100%;">
                        <canvas id="depensesChart"></canvas>
                    </div>
                @endif
            </div>

            {{-- Filtres --}}
            <div style="background:#f8f5f0;padding:20px;border-radius:12px;margin-bottom:25px;border:1px solid #e8e0d5;">
                <form action="{{ route('admin.depenses.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:15px;align-items:flex-end;">
                    <div style="flex:1;min-width:200px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:5px;">
                            <i class="fas fa-truck-loading" style="margin-right:5px;"></i> Fournisseur
                        </label>
                        <select name="fournisseur_id" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;">
                            <option value="">Tous les fournisseurs</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                    {{ $fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;min-width:160px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:5px;">
                            <i class="fas fa-calendar" style="margin-right:5px;"></i> Payé depuis le
                        </label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;">
                    </div>
                    <div style="flex:1;min-width:160px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:5px;">
                            <i class="fas fa-calendar" style="margin-right:5px;"></i> Jusqu'au
                        </label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;">
                    </div>
                    <div style="flex:0 0 auto;display:flex;gap:10px;">
                        <button type="submit" style="background:#2d5a27;color:white;padding:10px 20px;border:none;border-radius:8px;font-weight:500;cursor:pointer;">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        @if(request()->anyFilled(['fournisseur_id','date_from','date_to']))
                            <a href="{{ route('admin.depenses.index') }}" style="padding:10px 20px;border:1px solid #e8e0d5;border-radius:8px;color:#6c757d;text-decoration:none;">Réinitialiser</a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div style="background:white;border-radius:12px;border:1px solid #e8e0d5;overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8f5f0;text-align:left;">
                            <th style="padding:14px 20px;color:#2d5a27;font-size:0.85rem;">N° Achat</th>
                            <th style="padding:14px 20px;color:#2d5a27;font-size:0.85rem;">Fournisseur</th>
                            <th style="padding:14px 20px;color:#2d5a27;font-size:0.85rem;">Date de paiement</th>
                            <th style="padding:14px 20px;color:#2d5a27;font-size:0.85rem;text-align:right;">Montant total achat</th>
                            <th style="padding:14px 20px;color:#2d5a27;font-size:0.85rem;text-align:right;">Payé</th>
                            <th style="padding:14px 20px;color:#2d5a27;font-size:0.85rem;text-align:center;">Détail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depenses as $index => $achat)
                            <tr style="border-top:1px solid #f0f0f0;{{ $index % 2 === 1 ? 'background:#fbfaf8;' : '' }}">
                                <td style="padding:14px 20px;font-weight:600;color:#2d5a27;">{{ $achat->numero }}</td>
                                <td style="padding:14px 20px;">{{ $achat->fournisseur->nom ?? '—' }}</td>
                                <td style="padding:14px 20px;">{{ $achat->date_paiement?->format('d/m/Y') ?? '—' }}</td>
                                <td style="padding:14px 20px;text-align:right;color:#6c757d;">{{ number_format($achat->mt_total, 0, ',', ' ') }} FCFA</td>
                                <td style="padding:14px 20px;text-align:right;font-weight:700;color:#721c24;">{{ number_format($achat->mt_paye, 0, ',', ' ') }} FCFA</td>
                                <td style="padding:14px 20px;text-align:center;">
                                    <a href="{{ route('admin.achats.show', $achat) }}" style="color:#2d5a27;text-decoration:none;"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="padding:30px 20px;text-align:center;color:#6c757d;">Aucune dépense trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top:20px;">{{ $depenses->links() }}</div>
        </div>
    </div>
</div>

@if($evolution->isNotEmpty())
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('depensesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($evolution->pluck('date')),
                datasets: [{
                    label: 'Dépenses (FCFA)',
                    data: @json($evolution->pluck('montant')),
                    borderColor: '#c62828', // Rouge foncé
                    borderWidth: 2.5,
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) {
                            return 'rgba(198, 40, 40, 0.1)';
                        }
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(198, 40, 40, 0.25)'); // Rouge clair
                        gradient.addColorStop(1, 'rgba(198, 40, 40, 0)');    // Transparent
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4, // Courbe lissée
                    pointRadius: 4,
                    pointBackgroundColor: '#c62828',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#b8860b',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Important pour que la hauteur soit respectée
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#2d5a27',
                            font: {
                                weight: '600',
                                size: 13
                            },
                            boxWidth: 20,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(198, 40, 40, 0.95)', // Tooltip rouge
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 15,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d',
                            font: {
                                size: 12
                            },
                            autoSkip: false, // Affiche toutes les dates
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        grid: { 
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            color: '#6c757d',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'k';
                                }
                                return new Intl.NumberFormat('fr-FR').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection