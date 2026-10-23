<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration - La Réserve Naturelle')</title>

    {{-- favicon --}}
    <link rel="icon" type="image/webp" href="{{ asset('img/logo.webp') }}">

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
            <img src="{{ asset('img/logo.webp') }}" alt="La Réserve Naturelle">
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
            {{-- <div class="sidebar-divider"></div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form> --}}

        </nav>
    </aside>

    <!-- ========================================= -->
    <!-- HEADER + CONTENU + FOOTER -->
    <!-- ========================================= -->
    <div class="admin-main-wrapper">

        <!-- HEADER (aligné sur app.blade) -->
        <header class="header">
            <div class="nav">

                <!-- TOGGLE SIDEBAR (mobile) -->
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir la sidebar">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- LOGO -->
                <a class="brand" href="{{ route('admin.dashboard') }}">
                    <div class="brand-circle">
                        <img src="{{ asset('img/logo.webp') }}" alt="La Réserve Naturelle">
                    </div>
                </a>

                <!-- NAVBAR -->
                <nav class="navbar" id="navbar">
                    <ul class="nav-links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="nav-pill {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-th-large"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}" class="nav-pill">
                                <i class="fas fa-store"></i> Boutique
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- ACTIONS -->
                <div class="nav-actions">
                    @auth
                        <div class="account-dropdown">
                            <button type="button" class="account-btn" aria-haspopup="true">
                                <i class="fas fa-user-circle"></i>
                                {{ Auth::user()->prenom ?? 'Admin' }}
                                @if(Auth::user()->isAdmin())
                                    <span class="badge-admin">Admin</span>
                                @endif
                                <i class="fas fa-chevron-down"></i>
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-logout-dropdown">
                                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                    <!-- HAMBURGER (navbar mobile) -->
                    <button class="hamburger" id="hamburger" aria-label="Menu">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                </div>

            </div>
        </header>

        <!-- CONTENU -->
        <main class="admin-main">
            @yield('content')
        </main>

        <!-- FOOTER (aligné sur app.blade) -->
        <footer class="footer" id="contact">
            <div class="footer-container">

                <div class="footer-main">

                    <div class="footer-brand">
                        <div class="footer-brand-top">
                            <div class="footer-logo-circle">
                                <img src="{{ asset('img/logo.webp') }}" alt="La Réserve Naturelle">
                            </div>
                            <a href="{{ route('home') }}" class="footer-cta">
                                <i class="fas fa-store"></i> Voir la boutique
                            </a>
                        </div>
                    </div>

                    <div class="footer-col">
                        <h4>Administration</h4>
                        <ul>
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('admin.orders.index') }}">Commandes</a></li>
                            <li><a href="{{ route('admin.produits.index') }}">Produits</a></li>
                        </ul>
                    </div>

                    <div class="footer-col">
                        <h4>Contact</h4>
                        <ul>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                TICAFRIQUE – Abidjan, Côte d'Ivoire
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <a href="tel:+2250556669299">05 56 66 92 99</a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:commercial@lareservenaturelle.ci">commercial@lareservenaturelle.ci</a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="footer-bottom">
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

    sidebarToggle?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);

    // ====== MENU HAMBURGER NAVBAR ======
    const hamburger = document.getElementById('hamburger');
    const navbar = document.getElementById('navbar');

    if (hamburger && navbar) {
        hamburger.addEventListener('click', e => {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            navbar.classList.toggle('active');
        });

        document.addEventListener('click', e => {
            if (!navbar.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('active');
                navbar.classList.remove('active');
            }
        });
    }

    // ====== DROPDOWN DEPENSES (sidebar) ======
    const depensesToggle = document.getElementById('depensesToggle');
    const depensesSubmenu = document.getElementById('depensesSubmenu');
    const depensesChevron = document.getElementById('depensesChevron');

    depensesToggle?.addEventListener('click', e => {
        e.preventDefault();
        const isOpen = depensesSubmenu.style.maxHeight !== '0px' && depensesSubmenu.style.maxHeight !== '';
        depensesSubmenu.style.maxHeight = isOpen ? '0' : '100px';
        depensesChevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    // ====== DROPDOWN COMPTE (mobile) ======
    const accountDropdown = document.querySelector('.account-dropdown');
    const accountBtn = accountDropdown?.querySelector('.account-btn');

    accountBtn?.addEventListener('click', e => {
        if (window.innerWidth <= 992) {
            e.stopPropagation();
            accountDropdown.classList.toggle('active');
        }
    });

    document.addEventListener('click', e => {
        if (accountDropdown && !accountDropdown.contains(e.target)) {
            accountDropdown.classList.remove('active');
        }
    });

    // ====== FERMER MENU AU CLIC SUR UN LIEN ======
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                hamburger?.classList.remove('active');
                navbar?.classList.remove('active');
            }
        });
    });

    // ====== RESIZE ======
    window.addEventListener('resize', () => {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            hamburger?.classList.remove('active');
            navbar?.classList.remove('active');
            accountDropdown?.classList.remove('active');
        }
    });
</script>
@stack('scripts')
</body>
</html>