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
                <div style="display: flex; gap: 10px;">
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

                    <!-- Commandes du jour -->
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
                            <h3 style="font-size: 1.8rem; font-weight: 700; color: #1565c0; margin: 0;">{{ $stats['orders_today'] ?? 0 }}</h3>
                            <p style="color: #6c757d; margin: 0; font-size: 0.9rem;">Commandes du jour</p>
                        </div>
                    </a>

                    <!-- Chiffre d'affaires -->
                    <a href="#" style="
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
                                Chiffre d'affaires
                                <span style="color: #28a745; font-weight: 600; margin-left: 5px;">
                                    <i class="fas fa-arrow-up"></i> +12%
                                </span>
                            </p>
                        </div>
                    </a>

                    <!-- Dépenses -->
                    <a href="#" style="
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
                                Dépenses
                                <span style="color: #dc3545; font-weight: 600; margin-left: 5px;">
                                    <i class="fas fa-arrow-down"></i> -5%
                                </span>
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- ================================= -->
            <!-- ACTIONS RAPIDES -->
            <!-- ================================= -->
            <div style="margin-bottom: 40px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.5rem; margin: 0;">
                        <i class="fas fa-bolt" style="color: #2d5a27; margin-right: 10px;"></i>
                        Actions rapides
                    </h2>
                    <div style="flex: 1; height: 2px; background: linear-gradient(to right, #e8e0d5, transparent); margin-left: 20px;"></div>
                </div>

                <div style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                    gap: 15px;
                ">
                    <!-- Ajouter produit -->
                    <a href="{{ route('admin.produits.create') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#2d5a27'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-plus-circle" style="font-size: 28px; color: #2d5a27; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Ajouter produit</span>
                    </a>

                    <!-- Liste produits -->
                    <a href="{{ route('admin.produits.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#2d5a27'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-boxes" style="font-size: 28px; color: #2d5a27; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Tous les produits</span>
                    </a>

                    <!-- Catégories -->
                    <a href="{{ route('admin.categories.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#2d5a27'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-folder" style="font-size: 28px; color: #b8860b; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Catégories</span>
                    </a>

                    <!-- Sous-catégories -->
                    <a href="{{ route('admin.sous-categories.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#b8860b'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-sitemap" style="font-size: 28px; color: #b8860b; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Sous-catégories</span>
                    </a>

                    <!-- Commandes -->
                    <a href="{{ route('admin.orders.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#1565c0'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-shopping-cart" style="font-size: 28px; color: #1565c0; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Commandes</span>
                    </a>

                    <!-- Tournées -->
                    <a href="{{ route('admin.tournees.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#b8860b'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-route" style="font-size: 28px; color: #b8860b; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Tournées</span>
                    </a>

                    <!-- Fournisseurs -->
                    <a href="{{ route('admin.fournisseurs.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#2d5a27'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-truck" style="font-size: 28px; color: #2d5a27; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Fournisseurs</span>
                    </a>

                    <!-- Livreurs -->
                    <a href="{{ route('admin.livreurs.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#2d5a27'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-motorcycle" style="font-size: 28px; color: #2d5a27; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Livreurs</span>
                    </a>

                    <!-- Zones -->
                    <a href="{{ route('admin.zones.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#b8860b'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-map-marked-alt" style="font-size: 28px; color: #b8860b; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Zones de livraison</span>
                    </a>

                    <!-- Utilisateurs -->
                    <a href="{{ route('admin.users.index') }}" style="
                        background: white;
                        padding: 20px 15px;
                        border-radius: 12px;
                        border: 1px solid #e8e0d5;
                        text-decoration: none;
                        color: #2d5a27;
                        text-align: center;
                        transition: all 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(45,90,39,0.1)'; this.style.borderColor='#2d5a27'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.borderColor='#e8e0d5'">
                        <i class="fas fa-users" style="font-size: 28px; color: #2d5a27; display: block; margin-bottom: 8px;"></i>
                        <span style="font-weight: 500;">Utilisateurs</span>
                    </a>
                </div>
            </div>

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
                    <div style="
                        background: white;
                        border-radius: 12px;
                        padding: 25px 25px 20px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                    ">
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
                    <div style="
                        background: white;
                        border-radius: 12px;
                        padding: 25px 25px 20px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        border: 1px solid #e8e0d5;
                    ">
                        <h4 style="color: #2d5a27; font-weight: 600; margin-bottom: 20px; font-size: 1.1rem;">
                            <i class="fas fa-book" style="color: #2d5a27; margin-right: 8px;"></i>
                            État du catalogue
                        </h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0ebe5;">
                                <span style="color: #6c757d;">
                                    <i class="fas fa-circle" style="color: #28a745; font-size: 10px;"></i> Disponible
                                </span>
                                <strong style="color: #28a745;">{{ $stats['available'] ?? 0 }}</strong>
                            </li>
                            <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0ebe5;">
                                <span style="color: #6c757d;">
                                    <i class="fas fa-circle" style="color: #ffc107; font-size: 10px;"></i> Stock faible
                                </span>
                                <strong style="color: #ffc107;">{{ $stats['low_stock'] ?? 0 }}</strong>
                            </li>
                            <li style="display: flex; justify-content: space-between; padding: 10px 0;">
                                <span style="color: #6c757d;">
                                    <i class="fas fa-circle" style="color: #dc3545; font-size: 10px;"></i> Rupture
                                </span>
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
                <div style="
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    overflow: hidden;
                ">
                    <div style="
                        padding: 20px 25px;
                        border-bottom: 1px solid #e8e0d5;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 15px;
                        background: #faf8f5;
                    ">
                        <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.3rem; margin: 0;">
                            <i class="fas fa-boxes" style="color: #2d5a27; margin-right: 10px;"></i>
                            Inventaire Produits
                        </h2>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('admin.sous-categories.index') }}" style="
                                background: #b8860b;
                                color: white;
                                padding: 8px 20px;
                                border-radius: 30px;
                                text-decoration: none;
                                font-weight: 500;
                                transition: all 0.3s;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 0.9rem;
                            " onmouseover="this.style.background='#9a7209'" onmouseout="this.style.background='#b8860b'">
                                <i class="fas fa-sitemap"></i> Sous-catégories
                            </a>
                            <a href="{{ route('admin.categories.index') }}" style="
                                background: #2d5a27;
                                color: white;
                                padding: 8px 20px;
                                border-radius: 30px;
                                text-decoration: none;
                                font-weight: 500;
                                transition: all 0.3s;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 0.9rem;
                            " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                                <i class="fas fa-folder"></i> Catégories
                            </a>
                            <a href="{{ route('admin.tournees.index') }}" style="
                                background: #b8860b;
                                color: white;
                                padding: 8px 20px;
                                border-radius: 30px;
                                text-decoration: none;
                                font-weight: 500;
                                transition: all 0.3s;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 0.9rem;
                            " onmouseover="this.style.background='#9a7209'" onmouseout="this.style.background='#b8860b'">
                                <i class="fas fa-route"></i> Tournées
                            </a>
                            <a href="{{ route('admin.produits.create') }}" style="
                                background: #2d5a27;
                                color: white;
                                padding: 8px 20px;
                                border-radius: 30px;
                                text-decoration: none;
                                font-weight: 500;
                                transition: all 0.3s;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 0.9rem;
                            " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                                <i class="fas fa-plus"></i> Ajouter
                            </a>
                            <a href="{{ route('admin.orders.index') }}" style="
                                background: #1565c0;
                                color: white;
                                padding: 8px 20px;
                                border-radius: 30px;
                                text-decoration: none;
                                font-weight: 500;
                                transition: all 0.3s;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 0.9rem;
                            " onmouseover="this.style.background='#0d47a1'" onmouseout="this.style.background='#1565c0'">
                                <i class="fas fa-shopping-cart"></i> Commandes
                            </a>
                            <a href="{{ route('admin.zones.index') }}" style="
                                background: #b8860b;
                                color: white;
                                padding: 8px 20px;
                                border-radius: 30px;
                                text-decoration: none;
                                font-weight: 500;
                                transition: all 0.3s;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 0.9rem;
                            " onmouseover="this.style.background='#9a7209'" onmouseout="this.style.background='#b8860b'">
                                <i class="fas fa-map-marked-alt"></i> Zones
                            </a>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 0.95rem;
                        ">
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
                                            {{ $product->designation ?? $product->nom }}
                                        </td>
                                        <td style="padding: 12px 20px; color: #6c757d;">
                                            {{ $product->category->nom ?? $product->categorie->nom ?? 'Non catégorisé' }}
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center; font-weight: 500;">
                                            {{ $product->qte_dispo ?? $product->stock }} unités
                                        </td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">
                                            {{ number_format($product->prix_vente ?? $product->prix, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center;">
                                            @php
                                                $stock = $product->qte_dispo ?? $product->stock ?? 0;
                                            @endphp
                                           @if($stock > 0 && $stock > $product->stock_minimum)
                                                {{-- badge "Disponible" inchangé --}}
                                                <span style="
                                                    background: #d4edda;
                                                    color: #155724;
                                                    padding: 4px 16px;
                                                    border-radius: 20px;
                                                    font-size: 0.8rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #28a745;"></i>
                                                    Disponible
                                                </span>
                                            @elseif($stock > 0 && $stock <= $product->stock_minimum)
                                                {{-- badge "Faible" inchangé --}}
                                                <span style="
                                                    background: #fff3cd;
                                                    color: #856404;
                                                    padding: 4px 16px;
                                                    border-radius: 20px;
                                                    font-size: 0.8rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #ffc107;"></i>
                                                    Faible
                                                </span>
                                            @else
                                                {{-- badge "Rupture" inchangé --}}
                                                <span style="
                                                    background: #f8d7da;
                                                    color: #721c24;
                                                    padding: 4px 16px;
                                                    border-radius: 20px;
                                                    font-size: 0.8rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px; color: #dc3545;"></i>
                                                    Rupture
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
                @if(($alertesStock ?? collect())->isNotEmpty())
                    <div style="margin-bottom: 40px;">
                        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #f5c6cb; overflow: hidden;">
                            <div style="padding: 20px 25px; border-bottom: 1px solid #f5c6cb; background: #fff8f8; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                                <h2 style="font-family: 'Playfair Display', serif; color: #c62828; font-size: 1.3rem; margin: 0;">
                                    <i class="fas fa-triangle-exclamation" style="margin-right: 10px;"></i>
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
                                            <th style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;"></th>
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
                                                <td style="padding: 12px 20px; text-align: center; color: #6c757d;">
                                                    {{ $produit->stock_minimum }}
                                                </td>
                                                <td style="padding: 12px 20px; text-align: right;">
                                                    <a href="{{ route('admin.achats.create') }}" style="color: #2d5a27; font-weight: 600; text-decoration: none; font-size: 0.85rem;">
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
                <div style="
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    overflow: hidden;
                ">
                    <div style="
                        padding: 20px 25px;
                        border-bottom: 1px solid #e8e0d5;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 15px;
                        background: #faf8f5;
                    ">
                        <h2 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.3rem; margin: 0;">
                            <i class="fas fa-clock" style="color: #2d5a27; margin-right: 10px;"></i>
                            Commandes récentes
                        </h2>
                        <a href="{{ route('admin.orders.index') }}" style="
                            color: #2d5a27;
                            text-decoration: none;
                            font-weight: 500;
                            transition: all 0.3s;
                            display: inline-flex;
                            align-items: center;
                            gap: 5px;
                            font-size: 0.9rem;
                            padding: 6px 16px;
                            border-radius: 20px;
                            background: #f0ebe5;
                        " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                            Voir tout <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 0.95rem;
                        ">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27;">Référence</th>
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
                                            <i class="fas fa-hashtag" style="color: #b8860b; margin-right: 5px;"></i>
                                            #{{ $order->num_order }}
                                        </td>
                                        <td style="padding: 12px 20px; color: #2d5a27;">
                                            <i class="fas fa-user" style="color: #b8860b; margin-right: 8px;"></i>
                                            {{ $order->user->full_name ?? 'N/A' }}
                                        </td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">
                                            {{ number_format($order->montant_ttc, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center; color: #6c757d;">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center;">
                                            @if($order->statut == 'livre')
                                                <span style="
                                                    background: #d4edda;
                                                    color: #155724;
                                                    padding: 4px 16px;
                                                    border-radius: 20px;
                                                    font-size: 0.8rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-check-circle"></i> Livré
                                                </span>
                                            @elseif($order->statut == 'en_cours')
                                                <span style="
                                                    background: #fff3cd;
                                                    color: #856404;
                                                    padding: 4px 16px;
                                                    border-radius: 20px;
                                                    font-size: 0.8rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-clock"></i> En cours
                                                </span>
                                            @else
                                                <span style="
                                                    background: #e3f2fd;
                                                    color: #0d47a1;
                                                    padding: 4px 16px;
                                                    border-radius: 20px;
                                                    font-size: 0.8rem;
                                                    font-weight: 500;
                                                    display: inline-block;
                                                ">
                                                    <i class="fas fa-hourglass-half"></i> En attente
                                                </span>
                                            @endif
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
        .container > div > div:nth-child(2) > div:last-child {
            grid-template-columns: 1fr 1fr !important;
        }
        .container > div > div:nth-child(3) > div:last-child {
            grid-template-columns: repeat(3, 1fr) !important;
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
        .container > div > div > div:last-child > div:first-child,
        .container > div > div > div:last-child > div:last-child {
            padding: 15px !important;
        }
        .container > div > div > div:last-child > div:first-child > div:last-child {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .container > div > div > div:last-child > div:first-child > div:last-child a {
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .container > div > div:nth-child(2) > div:last-child {
            grid-template-columns: 1fr !important;
        }
        .container > div > div:nth-child(3) > div:last-child {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        table {
            font-size: 0.8rem !important;
        }
        th, td {
            padding: 8px 12px !important;
        }
    }

    /* Animation douce pour les KPI */
    .container > div > div:nth-child(2) > div:last-child > a {
        animation: fadeInUp 0.6s ease both;
    }
    .container > div > div:nth-child(2) > div:last-child > a:nth-child(1) { animation-delay: 0.1s; }
    .container > div > div:nth-child(2) > div:last-child > a:nth-child(2) { animation-delay: 0.2s; }
    .container > div > div:nth-child(2) > div:last-child > a:nth-child(3) { animation-delay: 0.3s; }
    .container > div > div:nth-child(2) > div:last-child > a:nth-child(4) { animation-delay: 0.4s; }

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