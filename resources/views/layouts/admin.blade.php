<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration - La Réserve Naturelle')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body>

<div class="admin-dashboard">

    <!-- OVERLAY MOBILE -->
    <div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ========================================= -->
    <!-- SIDEBAR -->
    <!-- ========================================= -->
    <aside class="admin-sidebar" id="adminSidebar">

        <!-- Logo -->
        <div class="sidebar-logo">
            <img src="{{ asset('img/logo.png') }}" alt="La Réserve Naturelle">
            <div class="logo-text">
                <strong>La Réserve</strong>
                <em>Naturelle</em>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">

            <div class="nav-section">Navigation</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <div class="nav-section">Catalogue</div>

            <!-- PRODUITS -->
            <a href="{{ route('admin.produits.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i> Produits
                <span class="nav-badge">{{ \App\Models\Product::count() }}</span>
            </a>

            <!-- CATÉGORIES -->
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Catégories
                <span class="nav-badge">{{ \App\Models\Category::count() }}</span>
            </a>

            <!-- SOUS-CATÉGORIES -->
            <a href="{{ route('admin.sous-categories.index') }}" class="nav-item {{ request()->routeIs('admin.sous-categories.*') ? 'active' : '' }}">
                <i class="fas fa-sitemap"></i> Sous-catégories
                <span class="nav-badge">{{ \App\Models\SousCategory::count() }}</span>
            </a>

            <!-- STOCKS -->
            <a href="#" class="nav-item">
                <i class="fas fa-warehouse"></i> Stocks
            </a>

            <div class="nav-section">Ventes</div>

            <!-- COMMANDES -->
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Commandes
                <span class="nav-badge">{{ \App\Models\Order::count() }}</span>
            </a>

            <!-- FOURNISSEURS -->
            <a href="{{ route('admin.fournisseurs.index') }}" class="nav-item {{ request()->routeIs('admin.fournisseurs.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Fournisseurs
                <span class="nav-badge">{{ \App\Models\Fournisseur::count() }}</span>
            </a>

            <div class="nav-section">Utilisateurs</div>

            <!-- UTILISATEURS -->
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Utilisateurs
                <span class="nav-badge">{{ \App\Models\User::count() }}</span>
            </a>

            <div class="nav-section">Finance</div>

            <!-- RAPPORTS -->
            <a href="#" class="nav-item">
                <i class="fas fa-chart-line"></i> Rapports
            </a>

            <!-- DÉPENSES -->
            <a href="#" class="nav-item">
                <i class="fas fa-wallet"></i> Dépenses
            </a>

            <div class="sidebar-divider"></div>

            <!-- Déconnexion -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>

        </nav>

    </aside>

    <!-- ========================================= -->
    <!-- CONTENU PRINCIPAL + FOOTER -->
    <!-- ========================================= -->
    <div class="admin-main-wrapper">

        <main class="admin-main">

            <!-- Toggle mobile -->
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- HEADER -->
            <div class="admin-hero">

                <div class="hero-left">
                    <div class="hero-logo">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo">
                    </div>
                    <div class="hero-text">
                        <p class="hero-subtitle">Administration</p>
                        <h1>La Réserve Naturelle</h1>
                        <p>Gestion e-commerce & stock</p>
                    </div>
                </div>

                <div class="hero-right">
                    <div class="admin-info">
                        <div class="avatar">
                            {{ strtoupper(substr(Auth::user()->prenom ?? 'A', 0, 1)) }}
                        </div>
                        <span>{{ Auth::user()->full_name ?? 'Administrateur' }}</span>
                    </div>
                    <a href="{{ route('home') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Retour boutique
                    </a>
                </div>

            </div>

            <!-- CONTENU DE LA PAGE -->
            @yield('content')

        </main>

        <!-- ====== FOOTER (en dessous du main) ====== -->
        <footer class="admin-footer" id="contact">
            <div class="admin-footer-container">
                <div class="admin-footer-links">
                    <!-- Colonne 1: Catalogue -->
                    <div>
                        <h4>Catalogue</h4>
                        <ul>
                            <li><a href="#">Céréales</a></li>
                            <li><a href="#">Huiles</a></li>
                            <li><a href="#">Légumineuses</a></li>
                            <li><a href="#">Farines & épices</a></li>
                            <li><a href="#">Fruits secs</a></li>
                        </ul>
                    </div>

                    <!-- Colonne 2: Informations -->
                    <div>
                        <h4>Informations</h4>
                        <ul>
                            <li><a href="#apropos">À propos</a></li>
                            <li><a href="#">Livraison</a></li>
                            <li><a href="#">Paiement sécurisé</a></li>
                            <li><a href="#">Conditions générales</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                        </ul>
                    </div>

                    <!-- Colonne 3: Contact -->
                    <div>
                        <h4>Contact</h4>
                        <ul>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                Abidjan, Côte d'Ivoire
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                                +225 01 23 45 67 89
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="M22 7l-8.97 5.7a1.94 1.94 0 01-2.06 0L2 7"/>
                                </svg>
                                contact@lareservenaturelle.ci
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                Lun - Sam: 8h - 19h
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="admin-footer-bottom">
                    <p>&copy; {{ date('Y') }} <strong>La Réserve Naturelle</strong>. Tous droits réservés.</p>
                </div>
            </div>
        </footer>

    </div><!-- /admin-main-wrapper -->

</div><!-- /admin-dashboard -->

<!-- ========================================= -->
<!-- SCRIPTS -->
<!-- ========================================= -->

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }
    });
</script>

@stack('scripts')

</body>
</html>