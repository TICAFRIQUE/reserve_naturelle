@extends('layouts.admin')

@section('title', 'Rapports - La Réserve Naturelle')

@section('content')

<div class="container" style="padding: 40px 0;">

    {{-- ============================= --}}
    {{-- EN-TÊTE --}}
    {{-- ============================= --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px;">
        <div>
            <h1 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:2rem;margin:0;">
                <i class="fas fa-chart-line" style="color:#b8860b;margin-right:10px;"></i>
                Rapports
            </h1>
            <p style="color:#6c757d;margin:5px 0 0 0;">
                Consultez les performances de votre activité et suivez vos indicateurs clés.
            </p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('admin.rapports.print', request()->only(['date_from', 'date_to'])) }}"
               target="_blank"
               style="background:#e8e0d5;color:#2d5a27;padding:10px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;transition:all .3s;"
               onmouseover="this.style.background='#d4c9bb'"
               onmouseout="this.style.background='#e8e0d5'">
                <i class="fas fa-print"></i> Imprimer
            </a>

            <a href="{{ route('admin.rapports.print', array_merge(request()->only(['date_from', 'date_to']), ['pdf' => 1])) }}"
               target="_blank"
               style="background:#2d5a27;color:white;padding:10px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;transition:all .3s;"
               onmouseover="this.style.background='#1e3d1a'"
               onmouseout="this.style.background='#2d5a27'">
                <i class="fas fa-file-pdf"></i> Exporter PDF
            </a>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- FILTRES --}}
    {{-- ============================= --}}
    <div style="background:#f8f5f0;padding:20px;border-radius:12px;margin-bottom:25px;border:1px solid #e8e0d5;">
        <form method="GET" action="{{ route('admin.rapports.index') }}">
            <div style="display:flex;flex-wrap:wrap;gap:15px;align-items:flex-end;">

                <div style="flex:1;min-width:180px;">
                    <label style="display:block;font-weight:600;color:#2d5a27;font-size:.9rem;margin-bottom:5px;">
                        <i class="fas fa-calendar-alt" style="color:#b8860b;margin-right:5px;"></i> Date de début
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;font-size:.95rem;background:white;outline:none;"
                           onfocus="this.style.borderColor='#2d5a27'"
                           onblur="this.style.borderColor='#e8e0d5'">
                </div>

                <div style="flex:1;min-width:180px;">
                    <label style="display:block;font-weight:600;color:#2d5a27;font-size:.9rem;margin-bottom:5px;">
                        <i class="fas fa-calendar-alt" style="color:#b8860b;margin-right:5px;"></i> Date de fin
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;font-size:.95rem;background:white;outline:none;"
                           onfocus="this.style.borderColor='#2d5a27'"
                           onblur="this.style.borderColor='#e8e0d5'">
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit"
                            style="background:#2d5a27;color:white;padding:10px 25px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:all .3s;"
                            onmouseover="this.style.background='#1e3d1a'"
                            onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>

                    <a href="{{ route('admin.rapports.index') }}"
                       style="background:#e8e0d5;color:#2d5a27;padding:10px 25px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;transition:all .3s;"
                       onmouseover="this.style.background='#d4c9bb'"
                       onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- ============================= --}}
    {{-- PÉRIODE --}}
    {{-- ============================= --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
        <div>
            <span style="color:#6c757d;">Période analysée : </span>
            <strong style="color:#2d5a27;">
                {{ $dateFrom->format('d/m/Y') }} - {{ $dateTo->format('d/m/Y') }}
            </strong>
        </div>

        @if($hasFilter)
            <span style="background:#d4edda;color:#155724;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:500;">
                <i class="fas fa-filter"></i> Filtre actif
            </span>
        @endif
    </div>

    {{-- ============================= --}}
    {{-- KPI PRINCIPAUX --}}
    {{-- ============================= --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-bottom:30px;">

        {{-- CA --}}
        <div style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div style="color:#6c757d;font-size:.9rem;margin-bottom:8px;">
                        <i class="fas fa-money-bill-wave" style="color:#b8860b;"></i> Chiffre d'affaires
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;color:#b8860b;">
                        {{ number_format($ca, 0, ',', ' ') }} <small style="font-size:1rem;">FCFA</small>
                    </div>
                </div>
                <div style="width:46px;height:46px;border-radius:12px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;font-size:21px;color:#2d5a27;">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
            <div style="color:{{ $variations['ca'] >= 0 ? '#28a745' : '#dc3545' }};font-weight:600;margin-top:8px;">
                <i class="fas fa-{{ $variations['ca'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($variations['ca']) }} %
                <small style="color:#6c757d;font-weight:400;">vs période précédente</small>
            </div>
        </div>

        {{-- DÉPENSES --}}
        <div style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div style="color:#6c757d;font-size:.9rem;margin-bottom:8px;">
                        <i class="fas fa-wallet" style="color:#c62828;"></i> Dépenses d'achat
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;color:#c62828;">
                        {{ number_format($depenses, 0, ',', ' ') }} <small style="font-size:1rem;">FCFA</small>
                    </div>
                </div>
                <div style="width:46px;height:46px;border-radius:12px;background:#fce4ec;display:flex;align-items:center;justify-content:center;font-size:21px;color:#c62828;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <small style="color:#6c757d;">Total des achats sur la période</small>
        </div>

        {{-- MARGE --}}
        <div style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div style="color:#6c757d;font-size:.9rem;margin-bottom:8px;">
                        <i class="fas fa-{{ $marge >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}" style="color:{{ $marge >= 0 ? '#28a745' : '#dc3545' }};"></i>
                        Marge {{ $marge >= 0 ? 'bénéficiaire' : 'déficitaire' }}
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;color:{{ $marge >= 0 ? '#28a745' : '#dc3545' }};">
                        {{ $marge >= 0 ? '+' : '' }}{{ number_format($marge, 0, ',', ' ') }} <small style="font-size:1rem;">FCFA</small>
                    </div>
                </div>
                <div style="width:46px;height:46px;border-radius:12px;background:{{ $marge >= 0 ? '#e8f5e9' : '#fce4ec' }};display:flex;align-items:center;justify-content:center;font-size:21px;color:{{ $marge >= 0 ? '#28a745' : '#dc3545' }};">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div style="color:{{ $variations['marge'] >= 0 ? '#28a745' : '#dc3545' }};font-weight:600;margin-top:8px;">
                <i class="fas fa-{{ $variations['marge'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($variations['marge']) }} %
                <small style="color:#6c757d;font-weight:400;">vs période précédente</small>
            </div>
        </div>

        {{-- COMMANDES --}}
        <div style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div style="color:#6c757d;font-size:.9rem;margin-bottom:8px;">
                        <i class="fas fa-box" style="color:#1565c0;"></i> Commandes livrées
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;color:#1565c0;">
                        {{ number_format($nombreCommandes, 0, ',', ' ') }}
                    </div>
                </div>
                <div style="width:46px;height:46px;border-radius:12px;background:#e3f2fd;display:flex;align-items:center;justify-content:center;font-size:21px;color:#1565c0;">
                    <i class="fas fa-box-seam"></i>
                </div>
            </div>
            <div style="color:{{ $variations['commandes'] >= 0 ? '#28a745' : '#dc3545' }};font-weight:600;margin-top:8px;">
                <i class="fas fa-{{ $variations['commandes'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($variations['commandes']) }} %
                <small style="color:#6c757d;font-weight:400;">vs période précédente</small>
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- KPI SECONDAIRES --}}
    {{-- ============================= --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-bottom:30px;">

        {{-- CLIENTS --}}
        <div style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;">
            <div style="display:flex;align-items:center;gap:15px;margin-bottom:15px;">
                <div style="width:46px;height:46px;border-radius:12px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;font-size:21px;color:#2d5a27;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div style="color:#6c757d;font-size:.85rem;font-weight:500;">Clients actifs</div>
                    <div style="font-size:1.6rem;font-weight:700;color:#2d5a27;">{{ number_format($nombreClients, 0, ',', ' ') }}</div>
                </div>
            </div>
            <div style="color:{{ $variations['clients'] >= 0 ? '#28a745' : '#dc3545' }};font-weight:600;">
                <i class="fas fa-{{ $variations['clients'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($variations['clients']) }} %
                <small style="color:#6c757d;font-weight:400;">vs période précédente</small>
            </div>
        </div>

        {{-- CATÉGORIE TOP --}}
        <div style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;">
            <div style="display:flex;align-items:center;gap:15px;margin-bottom:15px;">
                <div style="width:46px;height:46px;border-radius:12px;background:#fff8e1;display:flex;align-items:center;justify-content:center;font-size:21px;color:#b8860b;">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <div style="color:#6c757d;font-size:.85rem;font-weight:500;">Catégorie la plus vendue</div>
                    @if($categorieTop)
                        <div style="font-size:1.2rem;font-weight:700;color:#2d5a27;">{{ $categorieTop->nom }}</div>
                    @else
                        <div style="font-size:1rem;color:#6c757d;">Aucune donnée</div>
                    @endif
                </div>
            </div>
            @if($categorieTop)
                <div style="color:#2d5a27;font-weight:600;">
                    <i class="fas fa-cube"></i>
                    {{ number_format($categorieTop->total_qte, 0, ',', ' ') }} unités vendues
                </div>
            @endif
        </div>

        {{-- MEILLEUR CLIENT --}}
        <div style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;">
            <div style="display:flex;align-items:center;gap:15px;margin-bottom:15px;">
                <div style="width:46px;height:46px;border-radius:12px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;font-size:21px;color:#b8860b;">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <div style="color:#6c757d;font-size:.85rem;font-weight:500;">Meilleur client</div>
                    @if($clientTop && $clientTop->user)
                        <div style="font-size:1.2rem;font-weight:700;color:#2d5a27;">
                            {{ $clientTop->user->nom }} {{ $clientTop->user->prenom }}
                        </div>
                    @else
                        <div style="font-size:1rem;color:#6c757d;">Aucune donnée</div>
                    @endif
                </div>
            </div>
            @if($clientTop)
                <div style="color:#b8860b;font-weight:600;">
                    <i class="fas fa-coins"></i>
                    {{ number_format($clientTop->total_achete, 0, ',', ' ') }} FCFA
                </div>
            @endif
        </div>
    </div>

    {{-- ============================= --}}
    {{-- VENTES --}}
    {{-- ============================= --}}
    <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;overflow:hidden;margin-bottom:30px;">

        <div style="padding:20px 25px;background:#faf8f5;border-bottom:1px solid #e8e0d5;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
            <div>
                <h2 style="font-size:1.2rem;color:#2d5a27;margin:0;">
                    <i class="fas fa-shopping-bag" style="color:#b8860b;"></i> Ventes et commandes
                </h2>
                <small style="color:#6c757d;">Commandes livrées pendant la période sélectionnée</small>
            </div>
            <span style="background:#d4edda;color:#155724;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:500;">
                {{ $ventes->total() }} commande(s)
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
                <thead style="background:#f8f5f0;">
                    <tr>
                        <th style="padding:12px 20px;text-align:left;color:#2d5a27;font-weight:600;">Date</th>
                        <th style="padding:12px 20px;text-align:left;color:#2d5a27;font-weight:600;">Client</th>
                        <th style="padding:12px 20px;text-align:left;color:#2d5a27;font-weight:600;">Commande</th>
                        <th style="padding:12px 20px;text-align:center;color:#2d5a27;font-weight:600;">Statut</th>
                        <th style="padding:12px 20px;text-align:right;color:#2d5a27;font-weight:600;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventes as $vente)
                        <tr style="border-bottom:1px solid #f0ebe5;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                            <td style="padding:12px 20px;">
                                <div style="font-weight:600;color:#2d5a27;">{{ $vente->created_at->format('d/m/Y') }}</div>
                                <small style="color:#6c757d;">{{ $vente->created_at->format('H:i') }}</small>
                            </td>
                            <td style="padding:12px 20px;">
                                @if($vente->user)
                                    <div style="font-weight:600;color:#2d5a27;">{{ $vente->user->nom }} {{ $vente->user->prenom }}</div>
                                    @if(!empty($vente->user->email))
                                        <small style="color:#6c757d;">{{ $vente->user->email }}</small>
                                    @endif
                                @else
                                    <span style="color:#6c757d;">Client supprimé</span>
                                @endif
                            </td>
                            <td style="padding:12px 20px;">
                                <span style="background:#e8f5e9;color:#2d5a27;padding:4px 14px;border-radius:20px;font-size:.8rem;font-weight:600;">
                                    #{{ $vente->num_order ?? $vente->id }}
                                </span>
                            </td>
                            <td style="padding:12px 20px;text-align:center;">
                                <span style="background:#d4edda;color:#155724;padding:4px 14px;border-radius:20px;font-size:.8rem;font-weight:500;">
                                    <i class="fas fa-check-circle"></i> Livrée
                                </span>
                            </td>
                            <td style="padding:12px 20px;text-align:right;font-weight:700;color:#b8860b;">
                                {{ number_format($vente->montant_ttc, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:60px 20px;text-align:center;color:#6c757d;">
                                <i class="fas fa-inbox" style="font-size:48px;display:block;margin-bottom:15px;color:#d4c9bb;"></i>
                                <p style="font-size:1.1rem;margin:0;">Aucune vente trouvée pour cette période</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ventes instanceof \Illuminate\Pagination\LengthAwarePaginator && $ventes->hasPages())
            <div style="padding:20px 25px;border-top:1px solid #e8e0d5;">
                {{ $ventes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- ============================= --}}
    {{-- ACHATS --}}
    {{-- ============================= --}}
    <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;overflow:hidden;margin-bottom:30px;">

        <div style="padding:20px 25px;background:#faf8f5;border-bottom:1px solid #e8e0d5;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
            <div>
                <h2 style="font-size:1.2rem;color:#2d5a27;margin:0;">
                    <i class="fas fa-truck" style="color:#b8860b;"></i> Achats fournisseurs
                </h2>
                <small style="color:#6c757d;">Achats enregistrés pendant la période sélectionnée</small>
            </div>
            <span style="background:#fff3cd;color:#856404;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:500;">
                {{ $achats->total() }} achat(s)
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
                <thead style="background:#f8f5f0;">
                    <tr>
                        <th style="padding:12px 20px;text-align:left;color:#2d5a27;font-weight:600;">Date</th>
                        <th style="padding:12px 20px;text-align:left;color:#2d5a27;font-weight:600;">Référence</th>
                        <th style="padding:12px 20px;text-align:left;color:#2d5a27;font-weight:600;">Fournisseur</th>
                        <th style="padding:12px 20px;text-align:center;color:#2d5a27;font-weight:600;">Statut</th>
                        <th style="padding:12px 20px;text-align:right;color:#2d5a27;font-weight:600;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($achats as $achat)
                        <tr style="border-bottom:1px solid #f0ebe5;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                            <td style="padding:12px 20px;">
                                <div style="font-weight:600;color:#2d5a27;">
                                    {{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td style="padding:12px 20px;">
                                <span style="background:#f8f5f0;color:#2d5a27;padding:4px 14px;border-radius:20px;font-size:.8rem;font-weight:600;">
                                    {{ $achat->numero ?? '#' . $achat->id }}
                                </span>
                            </td>
                            <td style="padding:12px 20px;">
                                @if($achat->fournisseur)
                                    <div style="font-weight:600;color:#2d5a27;">{{ $achat->fournisseur->nom }}</div>
                                @else
                                    <span style="color:#6c757d;">Fournisseur supprimé</span>
                                @endif
                            </td>
                            <td style="padding:12px 20px;text-align:center;">
                                @php
                                    $statutConfig = match($achat->statut) {
                                        'recu_total' => ['bg' => '#d4edda', 'color' => '#155724', 'label' => 'Reçu total'],
                                        'recu_partiel' => ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'Reçu partiel'],
                                        'confirme' => ['bg' => '#cce5ff', 'color' => '#004085', 'label' => 'Confirmé'],
                                        'brouillon' => ['bg' => '#e9ecef', 'color' => '#495057', 'label' => 'Brouillon'],
                                        default => ['bg' => '#e9ecef', 'color' => '#495057', 'label' => ucfirst($achat->statut)],
                                    };
                                @endphp
                                <span style="background:{{ $statutConfig['bg'] }};color:{{ $statutConfig['color'] }};padding:4px 14px;border-radius:20px;font-size:.8rem;font-weight:500;">
                                    {{ $statutConfig['label'] }}
                                </span>
                            </td>
                            <td style="padding:12px 20px;text-align:right;font-weight:700;color:#c62828;">
                                {{ number_format($achat->mt_total, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:60px 20px;text-align:center;color:#6c757d;">
                                <i class="fas fa-cart-x" style="font-size:48px;display:block;margin-bottom:15px;color:#d4c9bb;"></i>
                                <p style="font-size:1.1rem;margin:0;">Aucun achat trouvé pour cette période</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($achats instanceof \Illuminate\Pagination\LengthAwarePaginator && $achats->hasPages())
            <div style="padding:20px 25px;border-top:1px solid #e8e0d5;">
                {{ $achats->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- ============================= --}}
    {{-- RÉSUMÉ --}}
    {{-- ============================= --}}
    <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);border:1px solid #e8e0d5;padding:30px;">

        <div style="display:flex;align-items:center;gap:15px;margin-bottom:25px;">
            <div style="width:46px;height:46px;border-radius:12px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;font-size:21px;color:#2d5a27;">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div>
                <h3 style="font-size:1.2rem;color:#2d5a27;margin:0;">Résumé de la période</h3>
                <small style="color:#6c757d;">Principaux indicateurs de votre activité</small>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">

            <div style="background:#faf8f5;border-radius:12px;padding:20px;">
                <div style="color:#6c757d;font-size:.85rem;margin-bottom:8px;">
                    <i class="fas fa-money-bill-wave" style="color:#b8860b;"></i> Chiffre d'affaires
                </div>
                <div style="font-size:1.4rem;font-weight:700;color:#b8860b;">
                    {{ number_format($ca, 0, ',', ' ') }} FCFA
                </div>
            </div>

            <div style="background:#faf8f5;border-radius:12px;padding:20px;">
                <div style="color:#6c757d;font-size:.85rem;margin-bottom:8px;">
                    <i class="fas fa-wallet" style="color:#c62828;"></i> Total des dépenses
                </div>
                <div style="font-size:1.4rem;font-weight:700;color:#c62828;">
                    {{ number_format($depenses, 0, ',', ' ') }} FCFA
                </div>
            </div>

            <div style="background:#faf8f5;border-radius:12px;padding:20px;">
                <div style="color:#6c757d;font-size:.85rem;margin-bottom:8px;">
                    <i class="fas fa-chart-line" style="color:{{ $marge >= 0 ? '#28a745' : '#dc3545' }};"></i> Marge estimée
                </div>
                <div style="font-size:1.4rem;font-weight:700;color:{{ $marge >= 0 ? '#28a745' : '#dc3545' }};">
                    {{ $marge >= 0 ? '+' : '' }}{{ number_format($marge, 0, ',', ' ') }} FCFA
                </div>
            </div>

        </div>
    </div>

</div>

@endsection