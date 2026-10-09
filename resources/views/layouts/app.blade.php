<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'La Réserve Naturelle - Produits naturels et locaux en Côte d’Ivoire')</title>

  <meta name="description" content="@yield('meta_description', 'Découvrez La Réserve Naturelle, votre boutique de produits naturels et locaux en Côte d’Ivoire.')">
  <meta name="robots" content="@yield('meta_robots', 'index, follow')">

  {{-- Canonical --}}
  <link rel="canonical" href="@yield('canonical', url()->current())">

  {{-- Open Graph --}}
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title') ?: 'La Réserve Naturelle - Produits naturels et locaux en Côte d’Ivoire'))">
  <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description') ?: 'Découvrez La Réserve Naturelle, votre boutique de produits naturels et locaux en Côte d’Ivoire.'))">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:site_name" content="La Réserve Naturelle">
  <meta property="og:locale" content="fr_CI">
  <meta property="og:image" content="@yield('og_image', asset('img/logo.png'))">
  <meta property="og:image:alt" content="@yield('og_image_alt', 'La Réserve Naturelle')">

  {{-- Twitter / X --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title') ?: 'La Réserve Naturelle - Produits naturels et locaux en Côte d’Ivoire'))">
  <meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('meta_description') ?: 'Découvrez La Réserve Naturelle, votre boutique de produits naturels et locaux en Côte d’Ivoire.'))">
  <meta name="twitter:image" content="@yield('og_image', asset('img/logo.png'))">

  <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- CSS principal -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @stack('structured_data')
  @stack('styles')
</head>
<body class="has-fixed-header">

<!-- ============================================
     HEADER
============================================ -->
<header class="header">
  <div class="container nav">

    <!-- LOGO -->
    <a class="brand" href="{{ route('home') }}">
      <div class="brand-icon">
        <img src="{{ asset('img/logo.png') }}" alt="La Réserve Naturelle">
      </div>
      <div class="brand-text">
        <strong>La Réserve</strong>
        <em>Naturelle</em>
      </div>
    </a>

    <!-- NAVIGATION -->
    <nav class="navbar" id="navbar">
      <ul class="nav-links">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li><a href="{{ route('client.products.catalogue') }}">Catalogue</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>

        @auth
          <li class="dropdown dropdown-profile">
            <a href="#" style="display:flex;align-items:center;gap:8px;">
              <span>{{ Auth::user()->prenom }}</span>
              @if(Auth::user()->isAdmin())
                <span class="badge-admin">Admin</span>
              @endif
              <i class="fas fa-chevron-down" style="font-size:10px;"></i>
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

    <!-- PANIER (AVANT le hamburger) -->
    <a class="cart-btn" href="{{ route('client.cart.index') }}">
      <i class="fas fa-shopping-cart"></i>
      Panier <span id="cart-badge" class="cart-badge">{{ $cartCount ?? 0 }}</span>
    </a>

    <!-- HAMBURGER (APRÈS le panier) -->
    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>

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
          <li><a href="#apropos">À propos</a></li>
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
            commercial@lareservenaturelle.ci
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
  // ====== MENU HAMBURGER ======
  const hamburger = document.getElementById('hamburger');
  const navbar = document.getElementById('navbar');
  const dropdowns = document.querySelectorAll('.dropdown, .dropdown-profile');

  hamburger.addEventListener('click', function (e) {
    e.stopPropagation();
    this.classList.toggle('active');
    navbar.classList.toggle('active');
  });

  document.addEventListener('click', function (e) {
    if (!navbar.contains(e.target) && !hamburger.contains(e.target)) {
      hamburger.classList.remove('active');
      navbar.classList.remove('active');
      dropdowns.forEach(d => d.classList.remove('active'));
    }
  });

  // ====== DROPDOWNS ======
  dropdowns.forEach(dropdown => {
    const trigger = dropdown.querySelector(':scope > a');
    if (!trigger) return;

    trigger.addEventListener('click', function (e) {
      if (window.innerWidth <= 992) {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = dropdown.classList.contains('active');
        dropdowns.forEach(d => {
          if (d !== dropdown) d.classList.remove('active');
        });
        dropdown.classList.toggle('active', !isOpen);
      }
    });
  });

  // ====== FERMETURE MENU AU CLIC LIEN ======
  document.querySelectorAll('.nav-links > li:not(.dropdown):not(.dropdown-profile) a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 992) {
        hamburger.classList.remove('active');
        navbar.classList.remove('active');
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