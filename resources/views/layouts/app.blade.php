<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'La Réserve Naturelle - Produits naturels et locaux en Côte d’Ivoire')</title>

  <meta name="google-site-verification" content="ToZMgZcRknfbpUnu-KpdgTAyOvz6rBKQB6mZ3SzYfCA" />
  <meta name="description" content="@yield('meta_description', 'Découvrez La Réserve Naturelle, votre boutique de produits naturels et locaux en Côte d’Ivoire.')">
  <meta name="robots" content="@yield('meta_robots', 'index, follow')">

  {{-- ⚡ PERF: Preconnect (AVANT tout) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://www.googletagmanager.com">

  {{-- ⚡ PERF: Preload image hero (LCP) --}}
  <link rel="preload" as="image" href="{{ asset('img/b-hero.webp') }}" fetchpriority="high">

  {{-- ⚡ PERF: Preload Google Fonts --}}
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap">

  {{-- Canonical --}}
  <link rel="canonical" href="@yield('canonical', url()->current())">

  {{-- Open Graph --}}
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title') ?: 'La Réserve Naturelle - Produits naturels et locaux en Côte d’Ivoire'))">
  <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description') ?: 'Découvrez La Réserve Naturelle, votre boutique de produits naturels et locaux en Côte d’Ivoire.'))">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:site_name" content="La Réserve Naturelle">
  <meta property="og:locale" content="fr_CI">
  <meta property="og:image" content="@yield('og_image', asset('img/logo.webp'))">
  <meta property="og:image:alt" content="@yield('og_image_alt', 'La Réserve Naturelle')">

  {{-- Twitter / X --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title') ?: 'La Réserve Naturelle - Produits naturels et locaux en Côte d’Ivoire'))">
  <meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('meta_description') ?: 'Découvrez La Réserve Naturelle, votre boutique de produits naturels et locaux en Côte d’Ivoire.'))">
  <meta name="twitter:image" content="@yield('og_image', asset('img/logo.webp'))">

  <link rel="icon" type="image/webp" href="{{ asset('img/logo.webp') }}">

  {{-- ⚡ PERF: Google Fonts en async --}}
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap"
        rel="stylesheet"
        media="print"
        onload="this.media='all'">
  <noscript>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap" rel="stylesheet">
  </noscript>

  {{-- ⚡ PERF: Font Awesome en async --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        media="print"
        onload="this.media='all'">
  <noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  </noscript>

  <!-- CSS principal (fusionné + minifié) -->
  <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">

  <!-- ⚡ PERF: Google Analytics en async -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-00YHWD67YQ"></script>
  <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-00YHWD67YQ');
  </script>

  <!-- ⚡ PERF: SweetAlert2 différé -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

  @stack('structured_data')
  @stack('styles')
</head>
<body class="has-fixed-header">

<!-- ============================================
     HEADER  →  [LOGO]  liens centrés  [Panier]
============================================ -->
<header class="header">
  {{-- "container" retiré : la largeur est gérée par .nav (évite le conflit de largeur) --}}
  <div class="nav">

    <!-- LOGO (colonne gauche) -->
    <a class="brand" href="{{ route('home') }}">
      <img src="{{ asset('img/logo.webp') }}" alt="La Réserve Naturelle" class="brand-logo" width="160" height="60">
    </a>

    <!-- NAVIGATION (colonne centrale) -->
    <nav class="navbar" id="navbar">
      <ul class="nav-links">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li><a href="{{ route('client.products.catalogue') }}">Catalogue</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>

        @auth
          <li class="dropdown dropdown-profile">
            {{-- La flèche ▾ vient du CSS (.dropdown > a::after) --}}
            <a href="#">
              <span>{{ Auth::user()->prenom }}</span>
              @if(Auth::user()->isAdmin())
                <span class="badge-admin">Admin</span>
              @endif
            </a>
            <ul class="dropdown-menu">
              <li>
                <a href="{{ route('client.orders.index') }}">
                  <i class="fas fa-shopping-bag"></i> Mes commandes
                </a>
              </li>
              <li>
                <a href="{{ route('password.edit') }}">
                  <i class="fas fa-key"></i> Changer mot de passe
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                  @csrf
                  <button type="submit" class="btn-logout-dropdown">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                  </button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li><a href="{{ route('login') }}" class="btn-connexion">Connexion</a></li>
        @endauth
      </ul>
    </nav>

    <!-- PANIER + HAMBURGER (colonne droite) -->
    <div class="nav-actions">
      <a class="cart-btn" href="{{ route('client.cart.index') }}">
        <i class="fas fa-shopping-cart"></i>
        Panier <span id="cart-badge" class="cart-badge">{{ $cartCount ?? 0 }}</span>
      </a>

      <button class="hamburger" id="hamburger" aria-label="Menu" aria-controls="navbar" aria-expanded="false">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </button>
    </div>

  </div>
</header>

<!-- ============================================
     MAIN
============================================ -->
<main>
  @yield('content')
</main>

<!-- ============================================
     FOOTER
============================================ -->
<footer class="footer" id="contact">
  <div class="footer-container">
    <div class="footer-links">

      <div>
        <h4>Catalogue</h4>
        <ul>
          <li><a href="#">Condiments et épices</a></li>
          <li><a href="#">Produits alimentaires</a></li>
          <li><a href="#">Produits cosmétiques naturels</a></li>
        </ul>
      </div>

      <div>
        <h4>Informations</h4>
        <ul>
          <li><a href="#">À propos</a></li>
          <li><a href="#">Livraison</a></li>
          <li><a href="#">Conditions générales</a></li>
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
            TICAFRIQUE – Abidjan, Côte d'Ivoire
          </li>
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
            </svg>
            <a href="tel:+2250556669299" style="color: inherit; text-decoration: none;">
              05 56 66 92 99
            </a>
          </li>
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="4" width="20" height="16" rx="2"/>
              <path d="M22 7l-8.97 5.7a1.94 1.94 0 01-2.06 0L2 7"/>
            </svg>
              <a href="mailto:commercial@lareservenaturelle.ci" style="color: inherit; text-decoration: none;">
                commercial@lareservenaturelle.ci
              </a>
          </li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      <p>&copy; {{ date('Y') }} <strong>La Réserve Naturelle</strong>. Tous droits réservés.</p>
    </div>
  </div>
</footer>

<!-- ============================================
     SCRIPTS
============================================ -->
<script>
  (() => {
    const MOBILE_BP = 992;
    const hamburger = document.getElementById('hamburger');
    const navbar = document.getElementById('navbar');
    const dropdowns = document.querySelectorAll('.dropdown');

    const closeAll = () => {
      hamburger.classList.remove('active');
      hamburger.setAttribute('aria-expanded', 'false');
      navbar.classList.remove('active');
      dropdowns.forEach(d => d.classList.remove('active'));
    };

    hamburger.addEventListener('click', e => {
      e.stopPropagation();
      const open = hamburger.classList.toggle('active');
      navbar.classList.toggle('active', open);
      hamburger.setAttribute('aria-expanded', String(open));
    });

    document.addEventListener('click', e => {
      if (!navbar.contains(e.target) && !hamburger.contains(e.target)) closeAll();
    });

    dropdowns.forEach(dropdown => {
      const trigger = dropdown.querySelector(':scope > a');
      if (!trigger) return;

      trigger.addEventListener('click', e => {
        if (window.innerWidth > MOBILE_BP) return;
        e.preventDefault();
        e.stopPropagation();
        const wasOpen = dropdown.classList.contains('active');
        dropdowns.forEach(d => d.classList.remove('active'));
        dropdown.classList.toggle('active', !wasOpen);
      });
    });

    // Fermer le menu mobile après clic sur un lien (hors déclencheur de dropdown)
    document.querySelectorAll('.nav-links a:not(.dropdown > a)').forEach(link => {
      link.addEventListener('click', () => {
        if (window.innerWidth <= MOBILE_BP) closeAll();
      });
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > MOBILE_BP) closeAll();
    });
  })();
</script>

<script src="{{ asset('js/client/client-cart.js') }}" defer></script>

@if(session('success') || session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '{{ session('success') ? 'success' : 'error' }}',
            title: @json(session('success') ?? session('error')),
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    });
</script>
@endif
@stack('scripts')
</body>
</html>