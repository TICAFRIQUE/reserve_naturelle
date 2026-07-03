@extends('layouts.app')

@section('title', 'La Réserve Naturelle - Accueil')

@section('content')

<!-- ====== SLIDER CATALOGUE PLEINE LARGEUR ====== -->
<div class="catalog-slider-container">
  <div class="catalog-slider-wrapper" id="catalogSliderWrapper">
    <!-- Slide 1 -->
    <div class="catalog-slide catalog-slide-1">
      <div class="catalog-slide-content">
        <p class="eyebrow">🌟 Bienvenue à la Réserve Naturelle</p>
        <h2>Découvrez <span>nos produits</span></h2>
        <p>Des produits naturels de qualité, soigneusement sélectionnés pour vous offrir le meilleur de la nature ivoirienne.</p>
        
        @guest
          <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; margin-top: 20px;">
            <a href="{{ route('register') }}" class="btn" style="background: #2e7d32; color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600;">
              Créer un compte
            </a>
            <a href="{{ route('login') }}" class="btn" style="background: transparent; color: white; border: 2px solid white; padding: 12px 30px; border-radius: 50px; font-weight: 600;">
              Se connecter
            </a>
          </div>
        @endguest
        
        @auth
          <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; margin-top: 20px;">
            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('client.index') }}" class="btn" style="background: #2e7d32; color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600;">
              {{ Auth::user()->isAdmin() ? 'Accéder au Dashboard' : 'Accéder à la boutique' }}
            </a>
          </div>
        @endauth
      </div>
      <div class="catalog-slide-icons">
        <div class="icon-box">🌾<small>Céréales</small></div>
        <div class="icon-box">🫒<small>Huiles</small></div>
        <div class="icon-box">🫘<small>Légumineuses</small></div>
        <div class="icon-box">🌶️<small>Épices</small></div>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="catalog-slide catalog-slide-2">
      <div class="catalog-slide-content">
        <p class="eyebrow">🌿 Qualité naturelle</p>
        <h2>Des produits <span>locaux</span></h2>
        <p>Nous sélectionnons nos produits auprès de producteurs locaux pour garantir fraîcheur et authenticité.</p>
        <a href="#catalogue" class="btn">Découvrir</a>
      </div>
      <div class="catalog-slide-icons">
        <div class="icon-box">🇨🇮<small>Local</small></div>
        <div class="icon-box">🌱<small>Bio</small></div>
        <div class="icon-box">🏷️<small>Qualité</small></div>
        <div class="icon-box">🤝<small>Fair</small></div>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="catalog-slide catalog-slide-3">
      <div class="catalog-slide-content">
        <p class="eyebrow">🔥 Promotions</p>
        <h2>Offres <span>spéciales</span></h2>
        <p>Profitez de nos promotions sur une sélection de produits naturels et authentiques.</p>
        <a href="#catalogue" class="btn">Voir les offres</a>
      </div>
      <div class="catalog-slide-icons">
        <div class="icon-box">🔥<small>Promo</small></div>
        <div class="icon-box">💰<small>Économies</small></div>
        <div class="icon-box">🎁<small>Offres</small></div>
        <div class="icon-box">⭐<small>Top</small></div>
      </div>
    </div>

    <!-- Slide 4 -->
    <div class="catalog-slide catalog-slide-4">
      <div class="catalog-slide-content">
        <p class="eyebrow">✨ Nouveautés</p>
        <h2>Arrivages <span>récents</span></h2>
        <p>Découvrez les nouveaux produits qui viennent d'arriver dans notre catalogue.</p>
        <a href="#catalogue" class="btn">Explorer</a>
      </div>
      <div class="catalog-slide-icons">
        <div class="icon-box">🆕<small>Nouveau</small></div>
        <div class="icon-box">📦<small>Arrivage</small></div>
        <div class="icon-box">🌟<small>Exclusif</small></div>
        <div class="icon-box">💎<small>Premium</small></div>
      </div>
    </div>
  </div>

  <!-- Flèches -->
  <button class="catalog-slider-btn prev" onclick="changeCatalogSlide(-1)">‹</button>
  <button class="catalog-slider-btn next" onclick="changeCatalogSlide(1)">›</button>

  <!-- Dots -->
  <div class="catalog-slider-dots" id="catalogSliderDots">
    <span class="dot active" onclick="goToCatalogSlide(0)"></span>
    <span class="dot" onclick="goToCatalogSlide(1)"></span>
    <span class="dot" onclick="goToCatalogSlide(2)"></span>
    <span class="dot" onclick="goToCatalogSlide(3)"></span>
  </div>
</div>

<!-- ====== SECTION RECHERCHE ====== -->
<section class="search-section">
  <div class="container">
    <form class="search-form" id="searchForm">
      <div class="search-header">
        <h2>Rechercher un produit</h2>
        @guest
          <p style="color: #666; font-size: 14px; margin-top: 5px;">
            🔒 Connectez-vous pour passer commande
          </p>
        @endguest
      </div>

      <div class="search-grid">
        <div class="search-group">
          <input type="text" id="keyword" name="keyword" placeholder="Nom ou mot-clé..." autocomplete="off">
        </div>
        <div class="search-group">
          <select id="category" name="category">
            <option value="">Catégorie</option>
            <option value="toutes">Toutes les catégories</option>
            <option value="cereales">Céréales</option>
            <option value="huiles">Huiles</option>
            <option value="legumineuses">Légumineuses</option>
            <option value="farines">Farines & épices</option>
            <option value="fruits-secs">Fruits secs</option>
            <option value="condiments">Condiments</option>
          </select>
        </div>
        <div class="search-group search-group-btn">
          <button type="submit" class="btn-search">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <circle cx="11" cy="11" r="8"/>
              <path d="M21 21l-4.35-4.35"/>
            </svg>
            Rechercher
          </button>
        </div>
      </div>
    </form>
  </div>
</section>

<!-- ====== SECTION PRODUITS ====== -->
<section class="section" id="catalogue">
  <div class="container">
    <div class="section-heading">
      <p>Notre sélection</p>
      <h2>Produits du moment</h2>
    </div>

    <div class="products-grid">
      <article class="product-card">
        <div class="product-img visual-mil"></div>
        <div class="product-body">
          <span class="tag">Céréale</span>
          <h3>Mil rouge décortiqué</h3>
          <p>Produit naturel vendu au kilogramme.</p>
          <div class="product-footer">
            <strong>1 500 F / kg</strong>
            @auth
              <a href="#" class="add-btn">Ajouter</a>
            @else
              <a href="{{ route('login') }}" class="add-btn" style="background: #999; text-decoration: none;">🔒 Connexion</a>
            @endauth
          </div>
        </div>
      </article>

      <article class="product-card">
        <div class="product-img visual-huile"></div>
        <div class="product-body">
          <span class="tag promo">Promo</span>
          <h3>Huile de palme rouge</h3>
          <p>Huile naturelle, riche et authentique.</p>
          <div class="product-footer">
            <strong>2 200 F / L</strong>
            @auth
              <a href="#" class="add-btn">Ajouter</a>
            @else
              <a href="{{ route('login') }}" class="add-btn" style="background: #999; text-decoration: none;">🔒 Connexion</a>
            @endauth
          </div>
        </div>
      </article>

      <article class="product-card">
        <div class="product-img visual-mais"></div>
        <div class="product-body">
          <span class="tag">Céréale</span>
          <h3>Maïs jaune séché</h3>
          <p>Idéal pour farines et préparations locales.</p>
          <div class="product-footer">
            <strong>900 F / kg</strong>
            @auth
              <a href="#" class="add-btn">Ajouter</a>
            @else
              <a href="{{ route('login') }}" class="add-btn" style="background: #999; text-decoration: none;">🔒 Connexion</a>
            @endauth
          </div>
        </div>
      </article>

      <article class="product-card">
        <div class="product-img visual-haricots"></div>
        <div class="product-body">
          <span class="tag">Légumineuse</span>
          <h3>Haricots niébé blanc</h3>
          <p>Triés, propres et prêts à cuisiner.</p>
          <div class="product-footer">
            <strong>1 200 F / kg</strong>
            @auth
              <a href="#" class="add-btn">Ajouter</a>
            @else
              <a href="{{ route('login') }}" class="add-btn" style="background: #999; text-decoration: none;">🔒 Connexion</a>
            @endauth
          </div>
        </div>
      </article>
    </div>

    @guest
      <div style="text-align: center; margin-top: 50px; padding: 40px 30px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 16px; border: 2px solid #2e7d32;">
        <div style="font-size: 48px; margin-bottom: 10px;">🛒</div>
        <h3 style="color: #1b5e20; font-size: 24px; margin-bottom: 10px;">Vous souhaitez commander ?</h3>
        <p style="color: #333; font-size: 16px; max-width: 500px; margin: 0 auto 20px;">
          Connectez-vous ou créez un compte pour passer votre commande et profiter de nos produits naturels.
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
          <a href="{{ route('login') }}" class="btn" style="background: #2e7d32; color: white; padding: 12px 35px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block;">
            🔐 Se connecter
          </a>
          <a href="{{ route('register') }}" class="btn" style="background: transparent; color: #2e7d32; border: 2px solid #2e7d32; padding: 12px 35px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block;">
            📝 S'inscrire
          </a>
        </div>
      </div>
    @endguest
  </div>
</section>

<!-- ====== SECTION À PROPOS ====== -->
<section class="section split" id="apropos">
  <div class="container split-inner">
    <div>
      <p class="eyebrow green">Pourquoi nous choisir</p>
      <h2>Une boutique naturelle pensée pour vendre et gérer simplement.</h2>
      <p class="lead">La Réserve Naturelle met en avant des produits locaux, une expérience d'achat claire et un suivi interne du stock pour éviter les ruptures.</p>
      <ul class="check-list">
        <li>Catalogue organisé par familles de produits</li>
        <li>Prix, unités et disponibilité visibles</li>
        <li>Espace interne pour suivre les stocks</li>
        <li>Identité visuelle alignée au logo</li>
      </ul>
    </div>
    <div class="about-visual"></div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  // ====== JAVASCRIPT POUR LE SLIDER CATALOGUE ======
  let currentCatalogSlide = 0;
  const catalogSlides = document.querySelectorAll('.catalog-slide');
  const catalogDots = document.querySelectorAll('#catalogSliderDots .dot');
  const catalogWrapper = document.getElementById('catalogSliderWrapper');
  const totalCatalogSlides = catalogSlides.length;
  let catalogInterval;

  function updateCatalogSlider(index) {
    if (index < 0) index = totalCatalogSlides - 1;
    if (index >= totalCatalogSlides) index = 0;
    currentCatalogSlide = index;

    catalogWrapper.style.transform = `translateX(-${currentCatalogSlide * 100}%)`;

    catalogDots.forEach((dot, i) => {
      dot.classList.toggle('active', i === currentCatalogSlide);
    });
  }

  function changeCatalogSlide(direction) {
    updateCatalogSlider(currentCatalogSlide + direction);
    resetCatalogAutoSlide();
  }

  function goToCatalogSlide(index) {
    updateCatalogSlider(index);
    resetCatalogAutoSlide();
  }

  function nextCatalogSlide() {
    updateCatalogSlider(currentCatalogSlide + 1);
  }

  function resetCatalogAutoSlide() {
    clearInterval(catalogInterval);
    catalogInterval = setInterval(nextCatalogSlide, 4500);
  }

  catalogInterval = setInterval(nextCatalogSlide, 4500);

  document.querySelector('.catalog-slider-container').addEventListener('mouseenter', () => {
    clearInterval(catalogInterval);
  });

  document.querySelector('.catalog-slider-container').addEventListener('mouseleave', () => {
    catalogInterval = setInterval(nextCatalogSlide, 4500);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') changeCatalogSlide(-1);
    if (e.key === 'ArrowRight') changeCatalogSlide(1);
  });

  // ====== GESTION DU FORMULAIRE DE RECHERCHE ======
  document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const keyword = document.getElementById('keyword').value.trim();
    const category = document.getElementById('category').value;

    const searchParams = {
      keyword: keyword || 'Aucun',
      category: category || 'Toutes'
    };

    console.log('🔍 Recherche effectuée :', searchParams);
    showSearchResults(searchParams);
  });

  function showSearchResults(params) {
    let resultsDiv = document.querySelector('.search-results');

    if (!resultsDiv) {
      resultsDiv = document.createElement('div');
      resultsDiv.className = 'search-results';
      const form = document.getElementById('searchForm');
      form.parentNode.insertBefore(resultsDiv, form.nextSibling);
    }

    resultsDiv.innerHTML = `
      <div class="search-results-content">
        <p style="color: #2e7d32; font-weight: 700; margin-bottom: 8px;">
          ✅ Résultats de recherche
        </p>
        <div style="display: flex; gap: 20px; flex-wrap: wrap; font-size: 14px; color: #666;">
          <span><strong>Mot clé :</strong> "${params.keyword}"</span>
          <span><strong>Catégorie :</strong> ${params.category === 'Toutes' ? 'Toutes les catégories' : params.category}</span>
        </div>
        <p style="margin-top: 12px; color: #333;">
          ${Math.floor(Math.random() * 20) + 1} produit(s) trouvé(s)
          <span style="font-size: 13px; color: #666;">(simulation)</span>
        </p>
      </div>
    `;

    resultsDiv.style.display = 'block';
    resultsDiv.style.animation = 'fadeIn 0.4s ease';
  }
</script>
@endpush