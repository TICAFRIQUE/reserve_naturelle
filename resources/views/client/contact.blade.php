@extends('layouts.app')

@section('title', 'Contact - La Réserve Naturelle')
@section('meta_description', 'Contactez La Réserve Naturelle pour toute question concernant nos produits naturels et locaux, vos commandes ou nos services en Côte d’Ivoire.')

{{-- ⚠️ CACHE LE SLIDER GLOBAL SUR CETTE PAGE --}}
@section('hide_slider', true)
@section('content')

<!-- ============================================
     FIL D'ARIANE
============================================ -->
<div class="contact-breadcrumb-bar">
    <div class="container">
        <nav class="contact-breadcrumb" aria-label="Fil d'Ariane">
            {{-- Retour : page précédente si elle existe, sinon l'accueil --}}
            <a href="{{ url('/') }}" class="contact-back" aria-label="Retour"
               onclick="if (history.length > 1) { history.back(); return false; }">
                <i class="fas fa-arrow-left" aria-hidden="true"></i><span>Retour</span>
            </a>
            <span class="contact-crumb-sep" aria-hidden="true">›</span>
            <span class="contact-crumb-current" aria-current="page">Contact</span>
            <div class="contact-meta">
                <span class="contact-pill"><i class="far fa-clock" aria-hidden="true"></i><time id="cpClock">--:--</time></span>
            </div>
        </nav>
        </nav>
    </div>
</div>

<!-- ============================================
     BANNIÈRE CONTACT (Style FOANI)
============================================ -->
<section class="contact-hero">
    <div class="contact-hero-pattern"></div>

    <div class="contact-hero-content">
        <div class="contact-hero-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h1>Contactez-nous</h1>
        <p>Nous sommes à votre écoute pour répondre à toutes vos questions et vous accompagner dans vos projets</p>
    </div>

    <div class="contact-hero-fade"></div>
</section>

<!-- ============================================
     INFOS + FORMULAIRE
============================================ -->
<section class="contact-main">
    <div class="container">
        <div class="contact-grid">

            <!-- COLONNE GAUCHE : INFOS (Style FOANI) -->
            <div class="contact-info">

                <div class="info-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Nos informations</h3>
                </div>

                <div class="info-list">

                    <!-- ADRESSE -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Adresse</h4>
                            <p>TICAFRIQUE<br>Abidjan, Côte d'Ivoire</p>
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h4>Email</h4>
                            <p>
                                <a href="mailto:commercial@lareservenaturelle.ci">
                                    commercial@lareservenaturelle.ci
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- TÉLÉPHONE -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-content">
                            <h4>Téléphone / WhatsApp</h4>
                            <p>
                                <a href="tel:+2250556669299">05 56 66 92 99</a>
                            </p>
                        </div>
                    </div>

                    <!-- HORAIRES -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h4>Horaires</h4>
                            <p>
                                Lundi - Samedi : 8h à 19h<br>
                                Dimanche : Fermé
                            </p>
                        </div>
                    </div>

                </div>

                <!-- BLOC DIRECTION (comme FOANI) -->
                <div class="info-direction">
                    <div class="direction-header">
                        <i class="fas fa-building"></i>
                        <h5>Direction Commerciale (Abidjan)</h5>
                    </div>
                    <p>
                        <i class="fas fa-phone"></i>
                        <a href="tel:+2250556669299">+225 05 56 66 92 99</a>
                    </p>
                </div>

            </div>

            <!-- COLONNE DROITE : FORMULAIRE (Style FOANI) -->
            <div class="contact-form-card">

                <div class="form-header">
                    <i class="fas fa-comments"></i>
                    <h3>Formulaire de contact</h3>
                </div>

                <form action="#" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Nom &amp; Prénoms <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name" placeholder="Votre nom complet" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">
                            <i class="fas fa-tag"></i> Objet <span class="required">*</span>
                        </label>
                        <input type="text" id="subject" name="subject" placeholder="Sujet de votre message" required>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Téléphone
                        </label>
                        <input type="tel" id="phone" name="phone" placeholder="+225 XX XX XX XX XX">
                    </div>

                    <div class="form-group">
                        <label for="message">
                            <i class="fas fa-comment-alt"></i> Message <span class="required">*</span>
                        </label>
                        <textarea id="message" name="message" rows="5"
                                  placeholder="Écrivez votre message ici..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- ============================================
     MAP — Localisation (inchangée)
============================================ -->
<section class="map-section">
    <div class="container">
        <div class="map-heading">
            <p class="eyebrow">Où nous trouver</p>
            <h2>Notre <span class="text-gold">localisation</span></h2>
        </div>

        <div class="map-wrapper">
            <div class="map-frame">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.958565124782!2d-3.9934646!3d5.3960923!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc194a6d23e34df%3A0xb57679beb78c7b98!2sTICAFRIQUE!5e0!3m2!1sfr!2sfr!4v1700000000000!5m2!1sfr!2sfr"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <a href="https://www.google.com/maps/place/TICAFRIQUE/@5.3960923,-3.9934646,17z"
               target="_blank"
               class="map-directions-btn">
                <i class="fas fa-directions"></i> Obtenir l'itinéraire
            </a>
        </div>

        <div class="map-address">
            <i class="fas fa-map-marker-alt text-green"></i>
            <strong>TICAFRIQUE</strong> - Abidjan, Côte d'Ivoire
        </div>
    </div>
</section>

@endsection
@push('scripts')
<script src="{{ asset('js/client/catalogue.js') }}" defer></script>
@endpush