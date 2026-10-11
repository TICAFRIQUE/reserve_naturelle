<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Connexion - La Réserve Naturelle')</title>

  <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Dancing+Script:wght@700&family=Lato:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* ============================================
       LAYOUT AUTH — LA RÉSERVE NATURELLE
    ============================================ */
    * { box-sizing: border-box; }

    body.auth-body {
      margin: 0;
      font-family: 'Lato', sans-serif;
      background: #faf8f5;
      min-height: 100vh;
      color: #2d5a27;
    }

    .auth-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 100vh;
    }

    /* ====== COLONNE GAUCHE — BRANDING ====== */
    .auth-left {
      position: relative;
      background: linear-gradient(135deg, #1e3d1a 0%, #2d5a27 60%, #3d7a35 100%);
      color: #fff;
      overflow: hidden;
      display: flex;
      align-items: center;
      padding: 60px 70px;
    }

    .auth-left-content {
      position: relative;
      z-index: 2;
      max-width: 520px;
    }

    /* Logo */
    .auth-brand {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 40px;
    }

    .auth-brand img {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      object-fit: cover;
      background: #fff;
      padding: 4px;
      border: 2px solid rgba(255,255,255,0.3);
    }

    .auth-brand-text {
      display: flex;
      flex-direction: column;
      line-height: 1.1;
    }

    .auth-brand-text strong {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      color: #fff;
      font-weight: 900;
    }

    .auth-brand-text em {
      font-family: 'Dancing Script', cursive;
      font-size: 22px;
      color: #f5c878;
      font-style: normal;
      font-weight: 700;
      margin-top: -2px;
    }

    /* Titre */
    .auth-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(28px, 3.4vw, 42px);
      line-height: 1.15;
      color: #fff;
      margin: 0 0 12px;
      font-weight: 900;
    }

    .auth-title-bar {
      width: 55px;
      height: 4px;
      background: #b8860b;
      border-radius: 4px;
      margin-bottom: 26px;
    }

    /* Description */
    .auth-desc {
      font-size: 15px;
      line-height: 1.7;
      color: rgba(255,255,255,0.75);
      max-width: 460px;
      margin-bottom: 40px;
    }

    /* Points forts */
    .auth-features {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 22px;
    }

    .auth-features li {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .auth-feature-icon {
      width: 46px;
      height: 46px;
      flex-shrink: 0;
      border-radius: 12px;
      background: rgba(255,255,255,0.08);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #f5c878;
    }

    .auth-features strong {
      display: block;
      font-size: 15px;
      color: #fff;
      font-weight: 700;
      margin-bottom: 2px;
    }

    .auth-features span {
      font-size: 13.5px;
      color: rgba(255,255,255,0.65);
    }

    .auth-left-deco {
      position: absolute;
      right: -120px;
      bottom: -120px;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(184,134,11,0.25), transparent 70%);
      pointer-events: none;
    }

    /* ====== COLONNE DROITE — FORMULAIRE ====== */
    .auth-right {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 40px;
      background: #faf8f5;
    }

    .auth-card {
      width: 100%;
      max-width: 420px;
      background: #fff;
      border-radius: 16px;
      padding: 40px 36px;
      box-shadow: 0 20px 60px rgba(45,90,39,0.08);
      border: 1px solid #e8e0d5;
    }

    .auth-card h2 {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      font-weight: 700;
      color: #2d5a27;
      margin: 0 0 6px;
    }

    .auth-card p.sub {
      font-size: 14px;
      color: #6c757d;
      margin: 0 0 26px;
    }

    .auth-card label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: #2d5a27;
      margin-bottom: 6px;
    }

    .auth-card input[type="text"],
    .auth-card input[type="email"],
    .auth-card input[type="password"] {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid #e8e0d5;
      border-radius: 8px;
      background: #faf8f5;
      font-size: 15px;
      transition: border-color 0.2s, background 0.2s;
    }

    .auth-card input:focus {
      outline: none;
      border-color: #2d5a27;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(45,90,39,0.08);
    }

    .auth-card .form-group {
      margin-bottom: 18px;
    }

    .auth-card button[type="submit"] {
      width: 100%;
      padding: 13px;
      background: #2d5a27;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
      margin-top: 8px;
    }

    .auth-card button[type="submit"]:hover {
      background: #1e3d1a;
      transform: translateY(-1px);
    }

    .auth-card .auth-link {
      display: block;
      text-align: center;
      margin-top: 18px;
      font-size: 14px;
      color: #6c757d;
    }

    .auth-card .auth-link a {
      color: #b8860b;
      font-weight: 700;
      text-decoration: none;
    }

    .auth-card .auth-link a:hover {
      text-decoration: underline;
    }

    .auth-card .error-box {
      background: #fce4ec;
      border-left: 4px solid #c62828;
      padding: 12px 14px;
      border-radius: 8px;
      margin-bottom: 18px;
      color: #b71c1c;
      font-size: 13.5px;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 992px) {
      .auth-wrapper {
        grid-template-columns: 1fr;
      }

      .auth-left {
        padding: 40px 30px;
        min-height: auto;
      }

      .auth-right {
        padding: 40px 20px;
      }

      .auth-title {
        font-size: 28px;
      }
    }

    @media (max-width: 600px) {
      .auth-left {
        padding: 30px 20px;
      }

      .auth-features li {
        gap: 12px;
      }

      .auth-feature-icon {
        width: 40px;
        height: 40px;
        font-size: 17px;
      }

      .auth-card {
        padding: 30px 22px;
        border-radius: 14px;
      }

      .auth-card h2 {
        font-size: 22px;
      }
    }
  </style>

  @stack('styles')
</head>
<body class="auth-body">

<div class="auth-wrapper">

  <!-- ============================================
       COLONNE GAUCHE — BRANDING
  ============================================ -->
  <div class="auth-left">
    <div class="auth-left-content">

      <!-- Logo -->
      <div class="auth-brand">
        <img src="{{ asset('img/logo.png') }}" alt="La Réserve Naturelle">
        <div class="auth-brand-text">
          <strong>La Réserve</strong>
          <em>Naturelle</em>
        </div>
      </div>

      <!-- Titre -->
      <h1 class="auth-title">
        Plaisir d'Offrir,<br>
        Joie de Recevoir
      </h1>
      <div class="auth-title-bar"></div>

      <!-- Description -->
      <p class="auth-desc">
        Bienvenue dans votre boutique de produits naturels et locaux en Côte d’Ivoire —
        découvrez, commandez et faites-vous livrer en toute simplicité.
      </p>

      <!-- Points forts -->
      <ul class="auth-features">
        <li>
          <div class="auth-feature-icon"><i class="fas fa-leaf"></i></div>
          <div>
            <strong>Produits 100% naturels</strong>
            <span>Sélectionnés chez des producteurs locaux</span>
          </div>
        </li>
        <li>
          <div class="auth-feature-icon"><i class="fas fa-truck"></i></div>
          <div>
            <strong>Livraison rapide</strong>
            <span>À Abidjan et dans toute la Côte d’Ivoire</span>
          </div>
        </li>
        <li>
          <div class="auth-feature-icon"><i class="fas fa-shield-alt"></i></div>
          <div>
            <strong>Commande sécurisée</strong>
            <span>Vos données sont protégées</span>
          </div>
        </li>
      </ul>

    </div>

    <div class="auth-left-deco"></div>
  </div>

  <!-- ============================================
       COLONNE DROITE — FORMULAIRE
  ============================================ -->
  <div class="auth-right">
    <div class="auth-card">
      @yield('content')
    </div>
  </div>

</div>

@stack('scripts')
</body>
</html>