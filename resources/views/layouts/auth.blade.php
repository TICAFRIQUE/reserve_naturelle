<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'La Réserve Naturelle')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ============================================
           VARIABLES GLOBALES
        ============================================ */
        :root {
            --green: #0B7A48;
            --green-dark: #055936;
            --gold: #D79A05;
            --gold-light: #F2B832;
            --cream: #FAF6EB;
            --brown: #5C3D1E;
            --text: #2F2A22;
            --muted: #777064;
            --white: #fff;
            --danger: #B83232;
            --success: #16804A;
            --line: rgba(11, 122, 72, 0.14);
        }

        /* ============================================
           RESET & BASE
        ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', 'Segoe UI', Arial, sans-serif;
            color: var(--text);
            background: var(--cream);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ============================================
           HEADER / NAVBAR
        ============================================ */
        .header {
            background: rgba(250, 246, 235, 0.96);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--line);
        }

        .nav {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            width: min(1180px, 92%);
            margin: auto;
        }

        /* ============================================
           LOGO UNIQUEMENT
        ============================================ */
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid var(--gold);
            padding: 3px;
            background: var(--white);
            box-shadow: 0 2px 12px rgba(11, 122, 72, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .brand:hover .brand-icon {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(11, 122, 72, 0.25);
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-text strong {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(16px, 2.2vw, 20px);
            color: var(--brown);
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .brand-text em {
            font-family: 'Dancing Script', cursive;
            font-size: clamp(16px, 2.2vw, 21px);
            color: var(--green);
            font-style: normal;
            font-weight: 700;
            margin-top: -2px;
        }

        /* ============================================
           HAMBURGER MENU
        ============================================ */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .hamburger .bar {
            display: block;
            width: 28px;
            height: 3px;
            background: var(--brown);
            border-radius: 3px;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .hamburger.active .bar:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .hamburger.active .bar:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger.active .bar:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        .hamburger:hover .bar {
            background: var(--green);
        }

        /* ============================================
           NAVBAR LINKS
        ============================================ */
        .navbar {
            display: flex;
            align-items: center;
        }

        .nav-links {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 1.8rem;
            flex-wrap: wrap;
            padding: 0.8rem 0;
            margin: 0;
        }

        .nav-links li {
            position: relative;
            list-style: none;
        }

        .nav-links a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            font-size: clamp(14px, 1.2vw, 1.05rem);
            padding: 0.5rem 0.2rem;
            transition: color 0.25s ease;
            white-space: nowrap;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--green);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--green);
        }
        /* ============================================
           DROPDOWN MENU
        ============================================ */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--white);
            min-width: 200px;
            list-style: none;
            padding: 0.6rem 0;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dropdown-menu li {
            width: 100%;
        }

        .dropdown-menu a {
            display: block;
            padding: 0.6rem 1.5rem;
            color: var(--text);
            font-weight: 400;
            transition: background 0.2s, color 0.2s;
            white-space: nowrap;
        }

        .dropdown-menu a::after {
            display: none !important;
        }

        .dropdown-menu a:hover {
            background: rgba(11, 122, 72, 0.06);
            color: var(--green);
        }

        .nav-links li.dropdown:hover .dropdown-menu,
        .nav-links li.dropdown:focus-within .dropdown-menu {
            display: flex;
        }

        .dropdown > a::after {
            content: " ▾";
            font-size: 0.75rem;
            color: var(--muted);
            transition: transform 0.2s;
            display: inline-block;
            margin-left: 4px;
        }

        .nav-links li.dropdown:hover > a::after {
            transform: rotate(180deg);
            color: var(--green);
        }

        /* ============================================
           DROPDOWN PROFIL
        ============================================ */
        .user-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--green);
            color: var(--white);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .badge-admin {
            background: var(--danger);
            color: var(--white);
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dropdown-profile .dropdown-menu {
            min-width: 220px;
            right: 0;
            left: auto;
            border-radius: 12px;
            padding: 8px 0;
        }

        .dropdown-profile .dropdown-menu li a {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dropdown-profile .dropdown-menu li a i {
            width: 20px;
            color: var(--muted);
            font-size: 15px;
        }

        .dropdown-profile .dropdown-menu li a:hover i {
            color: var(--green);
        }

        .dropdown-divider {
            margin: 6px 0;
            border: none;
            border-top: 1px solid var(--line);
        }

        .btn-logout-dropdown {
            background: none !important;
            border: none !important;
            color: var(--danger) !important;
            cursor: pointer;
            width: 100%;
            text-align: left;
            padding: 10px 20px !important;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .btn-logout-dropdown i {
            width: 20px;
            color: var(--danger);
            font-size: 15px;
        }

        .btn-logout-dropdown:hover {
            background: rgba(184, 50, 50, 0.06) !important;
            color: var(--danger) !important;
        }

        /* ============================================
           CONTENU PRINCIPAL
        ============================================ */
        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 20px 20px 40px;
            width: 92%;
        }

        /* ============================================
           FLASH MESSAGES
        ============================================ */
        .flash-messages {
            margin: 20px 0;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-left: 4px solid var(--danger);
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            border-left: 4px solid #17a2b8;
        }

        /* ============================================
           FOOTER
        ============================================ */
        .footer {
            background: var(--green-dark);
            color: rgba(255, 255, 255, 0.8);
            padding: 40px 0 0;
            margin-top: 40px;
        }

        .footer-container {
            max-width: 1180px;
            margin: 0 auto;
            width: 92%;
            padding: 0 20px;
        }

        .footer-links {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            padding: 30px 0 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-links h4 {
            color: var(--gold-light);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-links ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links ul li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .footer-links ul li a {
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .footer-links ul li a:hover {
            color: var(--gold-light);
            padding-left: 4px;
        }

        .footer-links ul li svg {
            color: var(--gold);
            flex-shrink: 0;
            opacity: 0.6;
        }

        .footer-bottom {
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
        }

        .footer-bottom strong {
            color: var(--gold-light);
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 992px) {
            .hamburger {
                display: flex;
                order: 1;
            }

            .navbar {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--cream);
                padding: 20px 24px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                border-top: 1px solid var(--line);
                z-index: 99;
                width: 100%;
                justify-content: center;
            }

            .navbar.active {
                display: block;
                animation: slideDown 0.3s ease;
            }

            .nav {
                height: auto;
                padding: 12px 0;
                flex-wrap: wrap;
                gap: 10px;
                position: relative;
            }

            .brand {
                flex: 1;
            }

            .brand-icon {
                width: 40px;
                height: 40px;
            }

            .brand-text strong {
                font-size: 16px;
            }

            .brand-text em {
                font-size: 16px;
            }

            .nav-links {
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 0;
            }

            .nav-links li {
                border-bottom: 1px solid var(--line);
            }

            .nav-links li:last-child {
                border-bottom: none;
            }

            .nav-links a {
                display: block;
                padding: 14px 0;
                font-size: 16px;
                color: var(--text);
                text-align: center;
                white-space: normal;
            }

            .nav-links a::after {
                display: none;
            }

            .nav-links a:hover {
                color: var(--green);
                background: rgba(11, 122, 72, 0.04);
                border-radius: 6px;
            }

            .btn-connexion,
            .btn-inscription {
                display: block;
                text-align: center;
                padding: 12px 20px;
                border-radius: 8px;
                margin: 4px 0;
                width: 100%;
            }

            .btn-connexion {
                background: transparent;
                border: 2px solid var(--green);
                color: var(--green);
            }

            .btn-connexion:hover {
                background: var(--green);
                color: var(--white);
            }

            .btn-inscription {
                background: var(--green);
                color: var(--white);
                border: 2px solid var(--green);
            }

            .btn-inscription:hover {
                background: var(--green-dark);
                border-color: var(--green-dark);
            }

            .dropdown-menu {
                position: relative;
                top: 0;
                left: 0;
                background: rgba(0, 0, 0, 0.03);
                border-radius: 8px;
                margin: 4px 12px 8px;
                padding: 0.4rem 0;
                min-width: unset;
                width: auto;
                box-shadow: none;
                border: none;
            }

            .dropdown-menu a {
                padding: 10px 16px;
                font-size: 14px;
                color: var(--text);
                text-align: center;
            }

            .dropdown-menu a:hover {
                background: rgba(11, 122, 72, 0.08);
                color: var(--green);
            }

            .dropdown > a::after {
                float: right;
                margin-top: 4px;
            }

            .nav-links li.dropdown:hover .dropdown-menu {
                display: none;
            }

            .nav-links li.dropdown.active .dropdown-menu {
                display: block;
            }

            .dropdown-profile .dropdown-menu {
                position: relative;
                top: 0;
                right: auto;
                left: 0;
                box-shadow: none;
                border: none;
                background: rgba(0, 0, 0, 0.03);
                border-radius: 8px;
                margin: 4px 12px 8px;
                padding: 4px 0;
                min-width: unset;
                width: auto;
            }

            .dropdown-profile .dropdown-menu li a {
                padding: 10px 16px;
                font-size: 14px;
                justify-content: center;
            }

            .dropdown-profile .dropdown-menu li a:hover {
                background: rgba(11, 122, 72, 0.08);
            }

            .btn-logout-dropdown {
                justify-content: center;
                padding: 10px 16px !important;
            }

            .btn-logout-dropdown:hover {
                background: rgba(184, 50, 50, 0.08) !important;
            }

            .footer-links {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
        }

        @media (max-width: 600px) {
            .brand-icon {
                width: 36px;
                height: 36px;
                padding: 2px;
            }

            .brand-text strong {
                font-size: 14px;
            }

            .brand-text em {
                font-size: 14px;
            }

            .btn-connexion,
            .btn-inscription {
                padding: 10px 16px;
                font-size: 14px;
            }

            .badge-admin {
                font-size: 9px;
                padding: 1px 8px;
            }

            .footer-links {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 20px 0 30px;
            }

            .container {
                padding: 15px 15px 30px;
                width: 95%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ====== HEADER ====== -->
    <header class="header">
        <div class="nav">
            <!-- LOGO UNIQUEMENT -->
            <a class="brand" href="{{ url('/') }}">
                <div class="brand-icon">
                    <img src="{{ asset('img/logo.png') }}" alt="La Réserve Naturelle">
                </div>
                <div class="brand-text">
                    <strong>La Réserve</strong>
                    <em>Naturelle</em>
                </div>
            </a>

            <!-- HAMBURGER -->
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <!-- NAVIGATION -->
            <nav class="navbar" id="navbar">
                <ul class="nav-links">
                    <!-- ========================================== -->
                    <!-- AUTHENTIFICATION -->
                    <!-- ========================================== -->
                    @auth
                        <!-- UTILISATEUR CONNECTÉ -->
                        <li class="dropdown dropdown-profile">
                            <a href="#" style="display: flex; align-items: center; gap: 8px;">
                                <span>{{ Auth::user()->prenom }}</span>
                                @if(Auth::user()->isAdmin())
                                    <span class="badge-admin">Admin</span>
                                @endif
                                <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ Auth::user()->isAdmin() ? route('client.products.index') : route('client.products.index') }}">
                                        <i class="fas fa-tachometer-alt"></i> Retour au site
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
                    @else
                        <!-- VISITEUR NON CONNECTÉ -->
                        <li><a href="{{ route('register') }}" class="btn-inscription">Inscription</a></li>
                        <li><a href="{{ route('login') }}" class="btn-connexion">Connexion</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <!-- ====== CONTENU PRINCIPAL ====== -->
    <main>
        <div class="container">
            <!-- FLASH MESSAGES -->
            @if(session('success'))
                <div class="flash-messages">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-messages">
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="flash-messages">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ session('info') }}
                    </div>
                </div>
            @endif

            <!-- CONTENU DE LA PAGE -->
            @yield('content')
        </div>
    </main>

    <!-- ====== FOOTER ====== -->
    <footer class="footer" id="contact">
        <div class="footer-container">
            <div class="footer-links">
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

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} <strong>La Réserve Naturelle</strong>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- ====== SCRIPTS ====== -->
    <script>
        // ====== MENU HAMBURGER ======
        const hamburger = document.getElementById('hamburger');
        const navbar = document.getElementById('navbar');
        const dropdowns = document.querySelectorAll('.dropdown');

        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            navbar.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!navbar.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('active');
                navbar.classList.remove('active');
            }
        });

        dropdowns.forEach(dropdown => {
            const link = dropdown.querySelector('a');
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                    dropdowns.forEach(other => {
                        if (other !== dropdown) other.classList.remove('active');
                    });
                }
            });
        });

        document.querySelectorAll('.nav-links > li:not(.dropdown) a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    hamburger.classList.remove('active');
                    navbar.classList.remove('active');
                }
            });
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                hamburger.classList.remove('active');
                navbar.classList.remove('active');
                dropdowns.forEach(dropdown => dropdown.classList.remove('active'));
            }
        });
    </script>

    @stack('scripts')
</body>
</html>