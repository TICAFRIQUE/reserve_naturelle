<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'La Réserve Naturelle - Boutique')</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- Font Awesome pour les icônes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @stack('styles')
  <style>
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
  </style>
</head>
<body>
 
<header class="header">
  <div class="container nav">
    <!-- ====== LOGO ====== -->
    <a class="brand" href="{{ route('home') }}">
      <div class="brand-icon">
        <img src="{{ asset('img/logo.png') }}" alt="La Réserve Naturelle">
      </div>
      <div class="brand-text">
        <strong>La Réserve</strong>
        <em>Naturelle</em>
      </div>
    </a>
 
    <!-- ====== BOUTON HAMBURGER (Mobile) ====== -->
    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
 
    <!-- ====== NAVIGATION ====== -->
    <nav class="navbar" id="navbar">
      <ul class="nav-links">
        <!-- Accueil -->
        <li><a href="{{ route('home') }}">Accueil</a></li>
        
        <!-- À propos -->
        <li><a href="{{ route('client.about') }}">À propos</a></li>
        
        <!-- Catalogue - Lien direct (sans dropdown) -->
        <li><a href="{{ route('client.products.catalogue') }}">Catalogue</a></li>
        
        <!-- Contact -->
        <li><a href="{{ route('contact') }}">Contact</a></li>
        
        {{-- ============================================= --}}
        {{-- AUTHENTIFICATION --}}
        {{-- ============================================= --}}
        @auth
          {{-- UTILISATEUR CONNECTÉ --}}
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
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('admin.dashboard') }}">
                  <i class="fas fa-tachometer-alt"></i> Tableau de bord
                </a>
              </li>
              <li><a href="{{ route('password.edit') }}"><i class="fas fa-key"></i> Changer mot de passe</a></li>
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
          {{-- VISITEUR NON CONNECTÉ --}}
          <li><a href="{{ route('register') }}" class="btn-inscription">Inscription</a></li>
          <li><a href="{{ route('login') }}" class="btn-connexion">Connexion</a></li>
        @endauth
      </ul>
    </nav>
    
    <!-- ====== PANIER ====== -->
      <a class="cart-btn" href="{{ route('client.cart.index') }}">
        <i class="fas fa-shopping-cart"></i>
        Panier <span id="cart-badge" class="cart-badge">{{ auth()->user()?->cart?->items->sum('qte') ?? 0 }}</span>
    </a>
  </div>
</header>
 
<main>
  @yield('content')
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
        //Pour le composant add-to-cart-btn(ajout au panier)
      document.addEventListener('click', async function(e) {
          const btn = e.target.closest('.add-to-cart-btn');
          if (!btn) return;

          e.preventDefault();

          const productId = btn.dataset.productId;
          const qteInputId = btn.dataset.qteInput;
          const qte = qteInputId ? (parseInt(document.getElementById(qteInputId)?.value) || 1) : 1;

          const originalText = btn.innerHTML;
          btn.disabled = true;
          btn.innerHTML = '...';

          try {
              const response = await fetch('{{ route("client.cart.store") }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                      'Accept': 'application/json',
                  },
                  body: JSON.stringify({ product_id: productId, qte }),
              });

              const data = await response.json();

              if (!response.ok) {
                  alert(data.message || 'Erreur lors de l\'ajout au panier.');
                  btn.disabled = false;
                  btn.innerHTML = originalText;
                  return;
              }

              document.querySelectorAll('.cart-badge').forEach(badge => {
                  badge.textContent = data.cart_count;
              });

              btn.innerHTML = '✓ Ajouté';
              setTimeout(() => {
                  btn.innerHTML = originalText;
                  btn.disabled = false;
              }, 1200);

          } catch (err) {
              alert('Erreur réseau.');
              btn.innerHTML = originalText;
              btn.disabled = false;
          }
      });
</script>
 
@stack('scripts')
</body>
</html>