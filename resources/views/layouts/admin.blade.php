<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration - La Réserve Naturelle')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Admin (autonome) -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            <div class="nav-section">Stock</div>

            <a href="{{ route('admin.stock.index') }}" class="nav-item {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i> Stock
            </a>
            <a href="{{ route('admin.inventaires.index') }}" class="nav-item {{ request()->routeIs('admin.inventaires.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Inventaire
            </a>
            <a href="{{ route('admin.fournisseurs.index') }}" class="nav-item {{ request()->routeIs('admin.fournisseurs.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Fournisseurs
                <span class="nav-badge">{{ $sidebarCounts['fournisseurs'] }}</span>
            </a>
            <a href="{{ route('admin.achats.index') }}" class="nav-item {{ request()->routeIs('admin.achats.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-basket"></i> Achats
                <span class="nav-badge">{{ $sidebarCounts['achats'] }}</span>
            </a>
            <a href="{{ route('admin.stock-mouvements.index') }}" class="nav-item {{ request()->routeIs('admin.stock-mouvements.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Mouvements de stock
            </a>

            <div class="nav-section">Catalogue</div>

            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Catégories
                <span class="nav-badge">{{ $sidebarCounts['categories'] }}</span>
            </a>
            <a href="{{ route('admin.sous-categories.index') }}" class="nav-item {{ request()->routeIs('admin.sous-categories.*') ? 'active' : '' }}">
                <i class="fas fa-sitemap"></i> Sous-catégories
                <span class="nav-badge">{{ $sidebarCounts['sousCategories'] }}</span>
            </a>
            <a href="{{ route('admin.produits.index') }}" class="nav-item {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i> Produits
                <span class="nav-badge">{{ $sidebarCounts['produits'] }}</span>
            </a>
            <a href="{{ route('admin.banner.edit') }}" class="nav-item {{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
                <i class="fas fa-image"></i> Bannière
            </a>

            <div class="nav-section">Ventes</div>

            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Commandes
                <span class="nav-badge">{{ $sidebarCounts['orders'] }}</span>
            </a>

            <div class="nav-section">Livraison</div>

            <a href="{{ route('admin.tournees.index') }}" class="nav-item {{ request()->routeIs('admin.tournees.*') ? 'active' : '' }}">
                <i class="fas fa-route"></i> Livraison 
            </a>
            <a href="{{ route('admin.zones.index') }}" class="nav-item {{ request()->routeIs('admin.zones.*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i> Zones de livraison
            </a>

            <div class="nav-section">Utilisateurs</div>

            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Utilisateurs
                <span class="nav-badge">{{ $sidebarCounts['users'] }}</span>
            </a>
            <a href="{{ route('admin.livreurs.index') }}" class="nav-item {{ request()->routeIs('admin.livreurs.*') ? 'active' : '' }}">
                <i class="fas fa-motorcycle"></i> Livreurs
                <span class="nav-badge">{{ $sidebarCounts['livreurs'] }}</span>
            </a>

            <div class="nav-section">Rapports</div>

            <a href="{{ route('admin.rapports.index') }}" class="nav-item {{ request()->routeIs('admin.rapports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Rapports
            </a>
            <div class="nav-item-dropdown">
                <a href="#" class="nav-item nav-item-toggle {{ request()->routeIs('admin.depenses.*', 'admin.categorie-depenses.*') ? 'active' : '' }}" id="depensesToggle" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                    <span><i class="fas fa-wallet"></i> Dépenses</span>
                    <i class="fas fa-chevron-down" style="font-size: 11px; transition: transform 0.2s;" id="depensesChevron"></i>
                </a>
                <div id="depensesSubmenu" style="
                    max-height: {{ request()->routeIs('admin.depenses.*', 'admin.categorie-depenses.*') ? '100px' : '0' }};
                    overflow: hidden;
                    transition: max-height 0.25s ease;
                ">
                    <a href="{{ route('admin.depenses.index') }}" class="nav-item nav-subitem {{ request()->routeIs('admin.depenses.*') ? 'active' : '' }}" style="padding-left: 45px; font-size: 0.92rem;">
                        <i class="fas fa-list"></i> Toutes les dépenses
                    </a>
                    <a href="{{ route('admin.categorie-depenses.index') }}" class="nav-item nav-subitem {{ request()->routeIs('admin.categorie-depenses.*') ? 'active' : '' }}" style="padding-left: 45px; font-size: 0.92rem;">
                        <i class="fas fa-tags"></i> Catégories de dépenses
                    </a>
                </div>
            </div>
            <div class="sidebar-divider"></div>

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

            <!-- HEADER -->
            <header class="header">
                <div class="nav">

                    <!-- TOGGLE SIDEBAR (mobile) -->
                    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir la sidebar">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- LOGO -->
                    <a class="brand" href="{{ route('admin.dashboard') }}">
                        <div class="brand-icon">
                            <img src="{{ asset('img/logo.png') }}" alt="La Réserve Naturelle">
                        </div>
                        <div class="brand-text">
                            <strong>La Réserve</strong>
                            <em>Naturelle</em>
                        </div>
                    </a>

                    <!-- HAMBURGER (navbar mobile) -->
                    <button class="hamburger" id="hamburger" aria-label="Menu">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>

                    <!-- NAVBAR -->
                    <nav class="navbar" id="navbar">
                        <ul class="nav-links">
                            @auth
                                <li class="dropdown dropdown-profile">
                                    <a href="#" style="display: flex; align-items: center; gap: 8px;">
                                        <span>{{ Auth::user()->prenom ?? 'Admin' }}</span>
                                        @if(Auth::user()->isAdmin())
                                            <span class="badge-admin">Admin</span>
                                        @endif
                                        <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('home') }}">
                                                <i class="fas fa-store"></i> Retour boutique
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn-logout-dropdown">
                                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endauth
                        </ul>
                    </nav>
                </div>
            </header>

            <!-- CONTENU -->
            @yield('content')

        </main>

        <!-- FOOTER -->
        <footer class="admin-footer" id="contact">
            <div class="admin-footer-container">
                <div class="admin-footer-links">
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
</div>

<!-- ========================================= -->
<!-- SCRIPTS -->
<!-- ========================================= -->

<script>
    // ====== TOGGLE SIDEBAR MOBILE ======
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }
    });

    // ====== MENU HAMBURGER NAVBAR ======
    const hamburger = document.getElementById('hamburger');
    const navbar = document.getElementById('navbar');

    if (hamburger && navbar) {
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            this.classList.toggle('active');
            navbar.classList.toggle('active');
        });

        document.addEventListener('click', function (e) {
            if (!navbar.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('active');
                navbar.classList.remove('active');
            }
        });
    }
    // ====== DROPDOWN DEPENSES ======
    const depensesToggle = document.getElementById('depensesToggle');
    const depensesSubmenu = document.getElementById('depensesSubmenu');
    const depensesChevron = document.getElementById('depensesChevron');

    if (depensesToggle) {
        depensesToggle.addEventListener('click', function (e) {
            e.preventDefault();
            const isOpen = depensesSubmenu.style.maxHeight !== '0px' && depensesSubmenu.style.maxHeight !== '';
            depensesSubmenu.style.maxHeight = isOpen ? '0' : '100px';
            depensesChevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        });
}
    // ====== DROPDOWN MOBILE ======
    const dropdowns = document.querySelectorAll('.dropdown-profile');

    dropdowns.forEach(dropdown => {
        const link = dropdown.querySelector(':scope > a');
        if (!link) return;

        link.addEventListener('click', function (e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                const isOpen = dropdown.classList.contains('active');
                dropdowns.forEach(d => {
                    if (d !== dropdown) d.classList.remove('active');
                });
                dropdown.classList.toggle('active', !isOpen);
            }
        });
    });

    // ====== FERMER MENU AU CLIC SUR UN LIEN ======
    document.querySelectorAll('.nav-links > li:not(.dropdown-profile) a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                hamburger.classList.remove('active');
                navbar.classList.remove('active');
                dropdowns.forEach(d => d.classList.remove('active'));
            }
        });
    });

    // ====== RESIZE ======
    window.addEventListener('resize', function () {
        if (window.innerWidth > 992) {
            hamburger.classList.remove('active');
            navbar.classList.remove('active');
            dropdowns.forEach(d => d.classList.remove('active'));
        }
    });
</script>
@stack('scripts')
</body>
</html>