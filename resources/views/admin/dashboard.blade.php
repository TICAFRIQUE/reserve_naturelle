@extends('layouts.admin')

@section('title', 'Dashboard - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-chart-pie" style="color: #2d5a27; margin-right: 10px;"></i>
                        Dashboard
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="far fa-calendar-alt" style="color: #2d5a27;"></i>
                        {{ now()->format('l d F Y') }} - Bienvenue, <strong style="color: #2d5a27;">{{ Auth::user()->full_name ?? 'Administrateur' }}</strong>
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <span style="
                        background: #f8f5f0;
                        color: #2d5a27;
                        padding: 8px 16px;
                        border-radius: 20px;
                        font-size: 0.9rem;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                    ">
                        <i class="fas fa-clock" style="color: #2d5a27;"></i>
                        {{ now()->format('H:i') }}
                    </span>
                </div>
            </div>

            <!-- Filtre période -->
            <form method="GET" style="
                display: flex;
                gap: 15px;
                align-items: center;
                margin-bottom: 25px;
                flex-wrap: wrap;
                background: #f8f5f0;
                padding: 15px 20px;
                border-radius: 12px;
                border: 1px solid #e8e0d5;
            ">
                <label style="font-size: 0.9rem; color: #2d5a27; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calendar"></i> Du
                    <input type="date" name="date_from" value="{{ $dateFrom ?? now()->startOfMonth()->format('Y-m-d') }}" 
                           style="
                               padding: 8px 14px;
                               border-radius: 8px;
                               border: 1px solid #e8e0d5;
                               background: white;
                               font-size: 0.9rem;
                               outline: none;
                               transition: border-color 0.3s;
                           "
                           onfocus="this.style.borderColor='#2d5a27'"
                           onblur="this.style.borderColor='#e8e0d5'">
                </label>
                <label style="font-size: 0.9rem; color: #2d5a27; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    Au
                    <input type="date" name="date_to" value="{{ $dateTo ?? now()->format('Y-m-d') }}" 
                           style="
                               padding: 8px 14px;
                               border-radius: 8px;
                               border: 1px solid #e8e0d5;
                               background: white;
                               font-size: 0.9rem;
                               outline: none;
                               transition: border-color 0.3s;
                           "
                           onfocus="this.style.borderColor='#2d5a27'"
                           onblur="this.style.borderColor='#e8e0d5'">
                </label>
                <button type="submit" style="
                    padding: 8px 24px;
                    border-radius: 30px;
                    border: none;
                    background: #2d5a27;
                    color: white;
                    font-weight: 500;
                    font-size: 0.9rem;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <a href="{{ route('admin.dashboard') }}" style="
                    padding: 8px 20px;
                    border-radius: 30px;
                    border: 1px solid #e8e0d5;
                    background: white;
                    color: #2d5a27;
                    text-decoration: none;
                    font-weight: 500;
                    font-size: 0.9rem;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                " onmouseover="this.style.background='#e8e0d5'" onmouseout="this.style.background='white'">
                    <i class="fas fa-undo"></i> Réinitialiser
                </a>
            </form>

            <!-- ================================= -->
            <!-- STATISTIQUES (KPI) -->
            <!-- ================================= -->
            <div style="margin-bottom: 40px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.5rem; margin: 0;">
                        <i class="fas fa-chart-line" style="color: #2d5a27; margin-right: 10px;"></i>
                        Vue générale
                    </h2>
                    <div style="flex: 1; height: 2px; background: linear-gradient(to right, #e8e0d5, transparent); margin-left: 20px;"></div>
                </div>

                <div style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                    gap: 20px;
                ">
                    <!-- Produits en stock -->
                    <a href="{{ route('admin.produits.index') }}" style="
                        background: white;
                        padding: 25px 20px;
                        border-radius: 12px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                        transition: all 0.3s;
                        display: flex;
                        align-items: center;
                        gap: 18px;
                        text-decoration: none;
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 25px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
                        <div style="
                            width: 60px;
                            height: 60px;
                            border-radius: 12px;
                            background: #e8f5e9;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="fas fa-boxes" style="font-size: 28px; color: #2d5a27;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.8rem; font-weight: 700; color: #2d5a27; margin: 0;">{{ $stats['products'] ?? 0 }}</h3>
                            <p style="color: #6c757d; margin: 0; font-size: 0.9rem;">Produits en stock</p>
                        </div>
                    </a>

                    <!-- Commandes (période) -->
                    <a href="{{ route('admin.orders.index') }}" style="
                        background: white;
                        padding: 25px 20px;
                        border-radius: 12px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                        transition: all 0.3s;
                        display: flex;
                        align-items: center;
                        gap: 18px;
                        text-decoration: none;
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 25px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
                        <div style="
                            width: 60px;
                            height: 60px;
                            border-radius: 12px;
                            background: #e3f2fd;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="fas fa-shopping-cart" style="font-size: 28px; color: #1565c0;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.8rem; font-weight: 700; color: #1565c0; margin: 0;">{{ $stats['orders_period'] ?? 0 }}</h3>
                            <p style="color: #6c757d; margin: 0; font-size: 0.9rem;">
                                Commandes
                                <span style="color: #1565c0; font-weight: 500; font-size: 0.75rem; display: block;">
                                    {{ \Carbon\Carbon::parse($dateFrom ?? now()->startOfMonth())->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo ?? now())->format('d/m/Y') }}
                                </span>
                            </p>
                        </div>
                    </a>

                    <!-- Chiffre d'affaires -->
                    <div style="
                        background: white;
                        padding: 25px 20px;
                        border-radius: 12px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                        display: flex;
                        align-items: center;
                        gap: 18px;
                    ">
                        <div style="
                            width: 60px;
                            height: 60px;
                            border-radius: 12px;
                            background: #fff8e1;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="fas fa-money-bill-wave" style="font-size: 28px; color: #b8860b;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.8rem; font-weight: 700; color: #b8860b; margin: 0;">{{ number_format($stats['revenue'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                            <p style="color: #6c757d; margin: 0; font-size: 0.9rem;">
                                @if(!empty($dateFrom) && !empty($dateTo) && $dateFrom != $dateTo)
                                    CA du {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/y') }} au {{ \Carbon\Carbon::parse($dateTo)->format('d/m/y') }}
                                @else
                                    CA {{ now()->translatedFormat('F Y') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Dépenses -->
                    <div style="
                        background: white;
                        padding: 25px 20px;
                        border-radius: 12px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                        display: flex;
                        align-items: center;
                        gap: 18px;
                    ">
                        <div style="
                            width: 60px;
                            height: 60px;
                            border-radius: 12px;
                            background: #fce4ec;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="fas fa-wallet" style="font-size: 28px; color: #c62828;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.8rem; font-weight: 700; color: #c62828; margin: 0;">{{ number_format($stats['expenses'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                            <p style="color: #6c757d; margin: 0; font-size: 0.9rem;">
                                @if(!empty($dateFrom) && !empty($dateTo) && $dateFrom != $dateTo)
                                    Dépenses du {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/y') }} au {{ \Carbon\Carbon::parse($dateTo)->format('d/m/y') }}
                                @else
                                    Dépenses {{ now()->translatedFormat('F Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================= -->
            <!-- GRAPHIQUE CA -->
            <!-- ================================= -->
            @if(isset($revenueChart) && $revenueChart->count() > 0)
            <div style="margin-bottom: 40px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.5rem; margin: 0;">
                        <i class="fas fa-chart-area" style="color: #2d5a27; margin-right: 10px;"></i>
                        Évolution du Chiffre d'affaires
                    </h2>
                    <div style="flex: 1; height: 2px; background: linear-gradient(to right, #e8e0d5, transparent); margin-left: 20px;"></div>
                </div>

                <div style="
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    padding: 25px;
                    position: relative;
                ">
                    <canvas id="revenueChart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
            @endif

            <!-- ================================= -->
            <!-- STOCK & CATALOGUE -->
            <!-- ================================= -->
            <div style="margin-bottom: 40px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.5rem; margin: 0;">
                        <i class="fas fa-warehouse" style="color: #2d5a27; margin-right: 10px;"></i>
                        Gestion du stock
                    </h2>
                    <div style="flex: 1; height: 2px; background: linear-gradient(to right, #e8e0d5, transparent); margin-left: 20px;"></div>
                </div>

                <div style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                    gap: 25px;
                ">
                    <!-- Aperçu stock -->
                    <div style="background: white; border-radius: 12px; padding: 25px 25px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5;">
                        <h4 style="color: #2d5a27; font-weight: 600; margin-bottom: 20px; font-size: 1.1rem;">
                            <i class="fas fa-chart-simple" style="color: #2d5a27; margin-right: 8px;"></i>
                            Aperçu stock
                        </h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0ebe5;">
                                <span style="color: #6c757d;">Stock actuel</span>
                                <strong style="color: #2d5a27;">{{ $stats['current_stock'] ?? 0 }} unités</strong>
                            </li>
                            <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0ebe5;">
                                <span style="color: #6c757d;">Entrées (mois)</span>
                                <strong style="color: #28a745;">{{ $stats['entries'] ?? 0 }}</strong>
                            </li>
                            <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0ebe5;">
                                <span style="color: #6c757d;">Sorties (mois)</span>
                                <strong style="color: #dc3545;">{{ $stats['exits'] ?? 0 }}</strong>
                            </li>
                            <li style="display: flex; justify-content: space-between; padding: 10px 0;">
                                <span style="color: #6c757d;">Valeur totale</span>
                                <strong style="color: #b8860b; font-size: 1.05rem;">{{ number_format($stats['stock_value'] ?? 0, 0, ',', ' ') }} FCFA</strong>
                            </li>
                        </ul>
                    </div>

                    <!-- État du catalogue -->
                    <div style="background: white; border-radius: 12px; padding: 25px 25px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5;">
                        <h4 style="color: #2d5a27; font-weight: 600; margin-bottom: 20px; font-size: 1.1rem;">
                            <i class="fas fa-book" style="color: #2d5a27; margin-right: 8px;"></i>
                            État du catalogue
                        </h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0ebe5;">
                                <span style="color: #6c757d;"><i class="fas fa-circle" style="color: #28a745; font-size: 10px;"></i> Disponible</span>
                                <strong style="color: #28a745;">{{ $stats['available'] ?? 0 }}</strong>
                            </li>
                            <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0ebe5;">
                                <span style="color: #6c757d;"><i class="fas fa-circle" style="color: #ffc107; font-size: 10px;"></i> Stock faible</span>
                                <strong style="color: #ffc107;">{{ $stats['low_stock'] ?? 0 }}</strong>
                            </li>
                            <li style="display: flex; justify-content: space-between; padding: 10px 0;">
                                <span style="color: #6c757d;"><i class="fas fa-circle" style="color: #dc3545; font-size: 10px;"></i> Rupture</span>
                                <strong style="color: #dc3545;">{{ $stats['out_of_stock'] ?? 0 }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ================================= -->
            <!-- INVENTAIRE PRODUITS -->
            <!-- ================================= -->
            <div style="margin-bottom: 40px;">
                <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; overflow: hidden;">
                    <div style="padding: 20px 25px; border-bottom: 1px solid #e8e0d5; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; background: #faf8f5;">
                        <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.3rem; margin: 0;">
                            <i class="fas fa-boxes" style="color: #2d5a27; margin-right: 10px;"></i>
                            Inventaire Produits
                        </h2>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('admin.produits.create') }}" style="
                                background: #2d5a27; color: white; padding: 8px 20px; border-radius: 30px;
                                text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                                <i class="fas fa-plus"></i> Ajouter
                            </a>
                            <a href="{{ route('admin.produits.index') }}" style="
                                background: #1565c0; color: white; padding: 8px 20px; border-radius: 30px;
                                text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='#0d47a1'" onmouseout="this.style.background='#1565c0'">
                                <i class="fas fa-boxes"></i> Tous les produits
                            </a>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Produit</th>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Catégorie</th>
                                    <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Stock</th>
                                    <th style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">Prix</th>
                                    <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products ?? [] as $product)
                                    <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">
                                            <i class="fas fa-cube" style="color: #b8860b; margin-right: 8px;"></i>
                                            {{ $product->designation }}
                                        </td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $product->category->nom ?? 'Non catégorisé' }}</td>
                                        <td style="padding: 12px 20px; text-align: center; font-weight: 500;">{{ $product->qte_dispo }} unités</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">
                                            {{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center;">
                                            @php 
                                                $stock = $product->qte_dispo ?? 0; 
                                                $seuil = $product->stock_minimum ?? 5; 
                                            @endphp
                                            @if($stock > $seuil)
                                                <span style="background: #d4edda; color: #155724; padding: 4px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; display: inline-block;">
                                                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #28a745;"></i> Disponible
                                                </span>
                                            @elseif($stock > 0)
                                                <span style="background: #fff3cd; color: #856404; padding: 4px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; display: inline-block;">
                                                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #ffc107;"></i> Faible
                                                </span>
                                            @else
                                                <span style="background: #f8d7da; color: #721c24; padding: 4px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; display: inline-block;">
                                                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #dc3545;"></i> Rupture
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                            <i class="fas fa-box-open" style="font-size: 36px; display: block; margin-bottom: 12px; color: #d4c9bb;"></i>
                                            <p style="font-size: 1.05rem; margin: 0;">Aucun produit enregistré</p>
                                            <p style="font-size: 0.9rem; margin-top: 5px;">
                                                <a href="{{ route('admin.produits.create') }}" style="color: #2d5a27; font-weight: 600; text-decoration: none;">
                                                    Cliquez ici pour ajouter un produit
                                                </a>
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================================= -->
            <!-- ALERTES STOCK -->
            <!-- ================================= -->
            @if(isset($alertesStock) && $alertesStock->isNotEmpty())
                <div style="margin-bottom: 40px;">
                    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #f5c6cb; overflow: hidden;">
                        <div style="padding: 20px 25px; border-bottom: 1px solid #f5c6cb; background: #fff8f8; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                            <h2 style="font-family: 'Playfair Display', serif; color: #c62828; font-size: 1.3rem; margin: 0;">
                                <i class="fas fa-exclamation-triangle" style="margin-right: 10px;"></i>
                                Alertes stock ({{ $alertesStock->count() }})
                            </h2>
                            <a href="{{ route('admin.produits.index') }}" style="color: #c62828; text-decoration: none; font-weight: 500; font-size: 0.9rem; padding: 6px 16px; border-radius: 20px; background: #fce4ec;">
                                Voir tous les produits <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                                <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                    <tr>
                                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Produit</th>
                                        <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Stock actuel</th>
                                        <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Seuil minimum</th>
                                        <th style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alertesStock as $produit)
                                        <tr style="border-bottom: 1px solid #f0ebe5;">
                                            <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">
                                                {{ $produit->designation }}
                                                <div style="font-size: 0.8rem; color: #6c757d;">{{ $produit->reference_prod }}</div>
                                            </td>
                                            <td style="padding: 12px 20px; text-align: center; font-weight: 700; color: {{ $produit->qte_dispo <= 0 ? '#dc3545' : '#856404' }};">
                                                {{ $produit->qte_dispo }}
                                            </td>
                                            <td style="padding: 12px 20px; text-align: center; color: #6c757d;">{{ $produit->stock_minimum ?? 5 }}</td>
                                            <td style="padding: 12px 20px; text-align: right;">
                                                <a href="{{ route('admin.achats.create', ['product_id' => $produit->id]) }}" style="color: #2d5a27; font-weight: 600; text-decoration: none; font-size: 0.85rem;">
                                                    <i class="fas fa-cart-plus"></i> Commander
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ================================= -->
            <!-- COMMANDES RÉCENTES -->
            <!-- ================================= -->
            <div style="margin-bottom: 20px;">
                <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; overflow: hidden;">
                    <div style="padding: 20px 25px; border-bottom: 1px solid #e8e0d5; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; background: #faf8f5;">
                        <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.3rem; margin: 0;">
                            <i class="fas fa-clock" style="color: #2d5a27; margin-right: 10px;"></i>
                            Commandes récentes
                        </h2>
                        <a href="{{ route('admin.orders.index') }}" style="
                            color: #2d5a27; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 5px;
                            font-size: 0.9rem; padding: 6px 16px; border-radius: 20px; background: #f0ebe5;
                            transition: all 0.3s;
                        " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                            Voir tout <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27;">N° Commande</th>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Client</th>
                                    <th style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">Montant</th>
                                    <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Date</th>
                                    <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27;">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_orders ?? [] as $order)
                                    <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                        <td style="padding: 12px 20px; font-weight: 600; color: #2d5a27;">
                                            <i class="fas fa-hashtag" style="color: #b8860b; margin-right: 5px;"></i>{{ $order->num_order }}
                                        </td>
                                        <td style="padding: 12px 20px; color: #2d5a27;">
                                            <i class="fas fa-user" style="color: #b8860b; margin-right: 8px;"></i>{{ $order->user->prenom ?? $order->user->name ?? 'N/A' }}
                                        </td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">
                                            {{ number_format($order->mt_total ?? $order->total ?? 0, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center; color: #6c757d;">
                                            @if($order->created_at instanceof \Carbon\Carbon)
                                                {{ $order->created_at->format('d/m/Y') }}
                                            @else
                                                {{ date('d/m/Y', strtotime($order->created_at)) }}
                                            @endif
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center;">
                                            @php
                                                $statusConfig = [
                                                    'livree' => ['bg' => '#d4edda', 'color' => '#155724', 'icon' => 'fa-check-circle', 'label' => 'Livré'],
                                                    'en_livraison' => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'fa-truck', 'label' => 'En livraison'],
                                                    'en_cours' => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'fa-clock', 'label' => 'En cours'],
                                                    'validee' => ['bg' => '#cce5ff', 'color' => '#004085', 'icon' => 'fa-check', 'label' => 'Validée'],
                                                    'en_attente' => ['bg' => '#e3f2fd', 'color' => '#0d47a1', 'icon' => 'fa-hourglass-half', 'label' => 'En attente'],
                                                    'annulee' => ['bg' => '#f8d7da', 'color' => '#721c24', 'icon' => 'fa-times-circle', 'label' => 'Annulée'],
                                                ];
                                                $config = $statusConfig[$order->statut] ?? ['bg' => '#e8e0d5', 'color' => '#6c757d', 'icon' => 'fa-circle', 'label' => ucfirst($order->statut)];
                                            @endphp
                                            <span style="
                                                background: {{ $config['bg'] }};
                                                color: {{ $config['color'] }};
                                                padding: 4px 16px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 500;
                                                display: inline-block;
                                            ">
                                                <i class="fas {{ $config['icon'] }}"></i> {{ $config['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                            <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 12px; color: #d4c9bb;"></i>
                                            <p style="font-size: 1.05rem; margin: 0;">Aucune commande récente</p>
                                            <p style="font-size: 0.9rem; margin-top: 5px;">Les nouvelles commandes apparaîtront ici</p>
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
</div>
@endsection

@push('styles')
<style>
    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 20px 15px !important;
        }
        .container > div > div:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .container > div > div:first-child > div:last-child {
            width: 100%;
        }
        .container > div > div:first-child > div:last-child span {
            width: 100%;
            justify-content: center;
        }
        .container > div > form {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .container > div > form label {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 5px !important;
        }
        .container > div > form label input {
            width: 100% !important;
        }
        .container > div > div:nth-child(2) > div:last-child {
            grid-template-columns: 1fr 1fr !important;
        }
        .container > div > div:nth-child(3) > div:last-child {
            grid-template-columns: 1fr !important;
        }
        .container > div > div:nth-child(4) > div:last-child {
            grid-template-columns: 1fr !important;
        }
        h1 {
            font-size: 1.5rem !important;
        }
        h2 {
            font-size: 1.2rem !important;
        }
        .container > div > div:nth-child(5) > div:first-child {
            padding: 15px !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .container > div > div:nth-child(5) > div:first-child a {
            text-align: center;
        }
        .container > div > div:nth-child(7) > div:first-child {
            padding: 15px !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .container > div > div:nth-child(7) > div:first-child a {
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .container > div > div:nth-child(2) > div:last-child {
            grid-template-columns: 1fr !important;
        }
        table {
            font-size: 0.8rem !important;
        }
        th, td {
            padding: 8px 12px !important;
        }
    }

    /* Animation KPI */
    .container > div > div:nth-child(2) > div:last-child > a,
    .container > div > div:nth-child(2) > div:last-child > div {
        animation: fadeInUp 0.6s ease both;
    }
    .container > div > div:nth-child(2) > div:last-child > a:nth-child(1),
    .container > div > div:nth-child(2) > div:last-child > div:nth-child(1) { animation-delay: 0.1s; }
    .container > div > div:nth-child(2) > div:last-child > a:nth-child(2),
    .container > div > div:nth-child(2) > div:last-child > div:nth-child(2) { animation-delay: 0.2s; }
    .container > div > div:nth-child(2) > div:last-child > div:nth-child(3) { animation-delay: 0.3s; }
    .container > div > div:nth-child(2) > div:last-child > div:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
@if(isset($revenueChart) && $revenueChart->count() > 0)
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($revenueChart->pluck('label')),
                datasets: [{
                    label: 'Chiffre d\'affaires (FCFA)',
                    data: @json($revenueChart->pluck('value')),
                    borderColor: '#2d5a27',
                    borderWidth: 2.5,
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) {
                            return 'rgba(45, 90, 39, 0.1)';
                        }
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(45, 90, 39, 0.25)');
                        gradient.addColorStop(1, 'rgba(45, 90, 39, 0)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#2d5a27',
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
                maintainAspectRatio: true,
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
                        backgroundColor: 'rgba(45, 90, 39, 0.95)',
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
                            }
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
@endpush