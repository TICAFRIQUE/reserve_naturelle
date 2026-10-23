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
     HEADER
============================================ -->
<header class="header">
  <div class="nav">

    <a class="brand" href="{{ route('home') }}">
      <div class="brand-circle">
        <img src="{{ asset('img/logo.webp') }}" alt="La Réserve Naturelle">
      </div>
    </a>

    <nav class="navbar" id="navbar">
      <ul class="nav-links">

        <li>
          <a href="{{ route('home') }}"
             class="nav-pill {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Accueil
          </a>
        </li>

        <li>
          <a href="{{ route('client.products.catalogue') }}"
             class="nav-pill {{ request()->routeIs('client.products.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> Catalogue
          </a>
        </li>

        <li>
          <a href="{{ route('contact') }}"
             class="nav-pill {{ request()->routeIs('contact') ? 'active' : '' }}">
            <i class="fas fa-phone"></i> Contact
          </a>
        </li>

      </ul>
    </nav>

    <div class="nav-actions">

      <a class="cart-icon-btn" href="{{ route('client.cart.index') }}" aria-label="Panier">
        <i class="fas fa-shopping-cart"></i>
        <span id="cart-badge" class="cart-badge">{{ $cartCount ?? 0 }}</span>
      </a>

      @auth
        <div class="account-dropdown">
          <button class="account-btn" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle"></i>
            {{ Auth::user()->prenom }}
            @if(Auth::user()->isAdmin())
              <span class="badge-admin">Admin</span>
            @endif
            <i class="fas fa-chevron-down"></i>
          </button>

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
        </div>
      @else
        <a href="{{ route('login') }}" class="account-btn">
          <i class="fas fa-user-circle"></i>
          Connexion
        </a>
      @endauth

      <button class="hamburger" id="hamburger" aria-label="Menu"
              aria-controls="navbar" aria-expanded="false">
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

  {{-- SLIDER GLOBAL --}}
  @if(isset($sliderImages) && count($sliderImages) > 0 && !View::hasSection('hide_slider'))
    <section class="global-slider" id="globalSlider">
      @foreach($sliderImages as $index => $imageUrl)
        <div class="slide {{ $index === 0 ? 'active' : '' }}"
             style="background-image: url('{{ $imageUrl }}');"
             data-index="{{ $index }}">

          {{-- Overlay + contenu : UNIQUEMENT sur la 1ère image --}}
          @if($index === 0)
            <div class="slider-overlay">
              <div class="slider-content">
                <span class="eyebrow">Expérience Nature &amp; Terroir</span>
                <h1>
                  {{ $bannerGlobal->titre ?? 'Le meilleur de la' }}<br>
                  <span>{{ $bannerGlobal->titre_span ?? 'terre ivoirienne' }}</span>
                </h1>
                <p>{{ $bannerGlobal->sous_titre ?? 'Des produits naturels sélectionnés...' }}</p>
                <a href="{{ $bannerGlobal->lien_bouton ?? '#catalogue' }}" class="btn-slider">
                  {{ $bannerGlobal->texte_bouton ?? 'Découvrir le catalogue' }}
                </a>
              </div>
            </div>
          @endif

        </div>
      @endforeach

      @if(count($sliderImages) > 1)
        {{-- Flèche précédente --}}
        <button class="slider-arrow slider-arrow-prev" id="sliderPrev" aria-label="Slide précédent">
          <i class="fas fa-chevron-left"></i>
        </button>

        {{-- Flèche suivante --}}
        <button class="slider-arrow slider-arrow-next" id="sliderNext" aria-label="Slide suivant">
          <i class="fas fa-chevron-right"></i>
        </button>

        {{-- Dots --}}
        <div class="slider-dots">
          @foreach($sliderImages as $index => $imageUrl)
            <button class="dot {{ $index === 0 ? 'active' : '' }}"
                    data-index="{{ $index }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
          @endforeach
        </div>
      @endif
    </section>
  @endif

  @yield('content')
</main>

<!-- ============================================
     FOOTER
============================================ -->
<footer class="footer" id="contact">
  <div class="footer-container">

    <div class="footer-main">

      <div class="footer-brand">
        <div class="footer-brand-top">
          <div class="footer-logo-circle">
            <img src="{{ asset('img/logo.webp') }}" alt="La Réserve Naturelle">
          </div>

          <a href="{{ route('client.products.catalogue') }}" class="footer-cta">
            <i class="fas fa-shopping-bag"></i>
            Voir notre boutique
          </a>
        </div>
        <div class="footer-socials">
          <a href="https://facebook.com/lareservenaturelle" target="_blank" rel="noopener" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://instagram.com/lareservenaturelle" target="_blank" rel="noopener" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://wa.me/2250556669299" target="_blank" rel="noopener" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
          </a>
          <a href="https://tiktok.com/@lareservenaturelle" target="_blank" rel="noopener" aria-label="TikTok">
            <i class="fab fa-tiktok"></i>
          </a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Navigation</h4>
        <ul>
          <li><a href="{{ route('home') }}">Accueil</a></li>
          <li><a href="{{ route('client.products.catalogue') }}">Catalogue</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
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
      <p>
        &copy; {{ date('Y') }} <strong>La Réserve Naturelle</strong>. Tous droits réservés.
        <span class="footer-dev">
          Développé par <a href="https://ticafrique.com" target="_blank" rel="noopener">TICAFRIQUE</a>
        </span>
      </p>
    </div>

  </div>

  <a href="https://wa.me/2250556669299?text=Bonjour%20La%20R%C3%A9serve%20Naturelle%2C%20je%20souhaite%20avoir%20des%20informations."
     class="whatsapp-float"
     target="_blank"
     rel="noopener"
     aria-label="Contacter sur WhatsApp">
    <i class="fab fa-whatsapp"></i>
  </a>

  <button class="back-to-top" id="backToTop" aria-label="Retour en haut">
    <i class="fas fa-arrow-up"></i>
  </button>
</footer>

<!-- ============================================
     SCRIPTS
============================================ -->
<script>
  // Bouton retour en haut
  (function () {
    const btn = document.getElementById('backToTop');
    if (!btn) return;

    window.addEventListener('scroll', () => {
      btn.classList.toggle('visible', window.scrollY > 300);
    });

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  })();

  // Menu mobile + dropdowns
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

    document.querySelectorAll('.nav-links a:not(.dropdown > a)').forEach(link => {
      link.addEventListener('click', () => {
        if (window.innerWidth <= MOBILE_BP) closeAll();
      });
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > MOBILE_BP) closeAll();
    });
  })();

  // ⚡ SLIDER GLOBAL
  (function() {
    const slider = document.getElementById('globalSlider');
    if (!slider) return;

    const slides = slider.querySelectorAll('.slide');
    const dots = slider.querySelectorAll('.dot');
    const btnPrev = document.getElementById('sliderPrev');
    const btnNext = document.getElementById('sliderNext');

    if (slides.length <= 1) return;

    let current = 0;
    const duration = 5000;
    let timer;

    function goTo(index) {
      slides[current].classList.remove('active');
      if (dots[current]) dots[current].classList.remove('active');

      current = (index + slides.length) % slides.length;

      slides[current].classList.add('active');
      if (dots[current]) dots[current].classList.add('active');
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    function start() { timer = setInterval(next, duration); }
    function stop() { clearInterval(timer); }

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        stop();
        goTo(i);
        start();
      });
    });

    if (btnNext) {
      btnNext.addEventListener('click', () => {
        stop();
        next();
        start();
      });
    }

    if (btnPrev) {
      btnPrev.addEventListener('click', () => {
        stop();
        prev();
        start();
      });
    }

    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', start);

    start();
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