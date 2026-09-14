@extends('layouts.app')

@section('title', 'À propos - La Réserve Naturelle')

@section('content')

<!-- ============================================
     BANNIÈRE À PROPOS
============================================ -->
<section class="about-hero">
    <div class="about-hero-bg-1"></div>
    <div class="about-hero-bg-2"></div>

    <div class="about-hero-content">
        <div class="about-hero-icon">🌿</div>
        <h1>À propos de nous</h1>
        <p>Découvrez l'histoire et les valeurs de La Réserve Naturelle</p>
    </div>
</section>

<!-- ============================================
     NOTRE HISTOIRE
============================================ -->
<section class="section about-history">
    <div class="container">
        <div class="about-history-grid">

            <!-- Texte -->
            <div class="about-history-text">
                <p class="eyebrow">Notre histoire</p>
                <h2>
                    Une passion pour la <span class="text-green">nature</span>
                    et les <span class="text-gold">produits locaux</span>
                </h2>

                <p class="about-paragraph">
                    La Réserve Naturelle est née d'une passion commune pour les produits authentiques et naturels de Côte d'Ivoire.
                    Fondée en 2020, notre boutique a pour mission de mettre en avant la richesse du terroir ivoirien.
                </p>

                <p class="about-paragraph">
                    Nous sélectionnons avec soin chaque produit auprès de producteurs locaux, garantissant ainsi qualité,
                    fraîcheur et traçabilité. Notre engagement est de promouvoir une agriculture durable et responsable.
                </p>

                <div class="about-stats">
                    <div class="stat-item">
                        <div class="stat-value">2020</div>
                        <p class="stat-label">Année de création</p>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">100+</div>
                        <p class="stat-label">Produits disponibles</p>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">50+</div>
                        <p class="stat-label">Producteurs locaux</p>
                    </div>
                </div>
            </div>

            <!-- Visuel -->
            <div class="about-history-visual">
                🌾
            </div>

        </div>
    </div>
</section>

<!-- ============================================
     NOS VALEURS
============================================ -->
<section class="section about-values">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Nos valeurs</p>
            <h2>Ce qui nous <span class="text-gold">anime</span></h2>
        </div>

        <div class="values-grid">

            <div class="value-card">
                <div class="value-icon">🌱</div>
                <h3>Authenticité</h3>
                <p>Des produits naturels, sans additifs, respectant les savoir-faire traditionnels.</p>
            </div>

            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3>Équité</h3>
                <p>Des relations justes et transparentes avec nos producteurs locaux.</p>
            </div>

            <div class="value-card">
                <div class="value-icon">♻️</div>
                <h3>Durabilité</h3>
                <p>Un engagement pour une agriculture respectueuse de l'environnement.</p>
            </div>

            <div class="value-card">
                <div class="value-icon">❤️</div>
                <h3>Passion</h3>
                <p>La passion du bon produit et du partage de notre terroir.</p>
            </div>

        </div>
    </div>
</section>

<!-- ============================================
     ÉQUIPE
============================================ -->
<section class="section about-team">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Notre équipe</p>
            <h2>Ceux qui font <span class="text-gold">la différence</span></h2>
        </div>

        <div class="team-grid">

            <div class="team-member">
                <div class="team-avatar">👩‍💼</div>
                <h4>Mme Kouadio</h4>
                <p>Fondatrice & Directrice</p>
            </div>

            <div class="team-member">
                <div class="team-avatar">👨‍🌾</div>
                <h4>M. Koné</h4>
                <p>Responsable approvisionnement</p>
            </div>

            <div class="team-member">
                <div class="team-avatar">👩‍🌾</div>
                <h4>Mlle Yao</h4>
                <p>Responsable qualité</p>
            </div>

            <div class="team-member">
                <div class="team-avatar">👨‍💻</div>
                <h4>M. N'Guessan</h4>
                <p>Responsable digital</p>
            </div>

        </div>
    </div>
</section>

@endsection