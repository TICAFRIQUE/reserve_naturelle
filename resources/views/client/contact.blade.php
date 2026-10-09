@extends('layouts.app')

@section('title', 'Contact - La Réserve Naturelle')
@section('meta_description', 'Contactez La Réserve Naturelle pour toute question concernant nos produits naturels et locaux, vos commandes ou nos services en Côte d’Ivoire.')

@section('content')

<!-- ============================================
     BANNIÈRE CONTACT
============================================ -->
<section class="contact-hero">
    <div class="contact-hero-bg-1"></div>
    <div class="contact-hero-bg-2"></div>

    <div class="contact-hero-content">
        <div class="contact-hero-icon">📞</div>
        <h1>Contactez-nous</h1>
        <p>Nous sommes à votre écoute pour toute question ou commande</p>
    </div>
</section>

<!-- ============================================
     FORMULAIRE + INFOS
============================================ -->
<section class="contact-main">
    <div class="container">
        <div class="contact-grid">

            <!-- FORMULAIRE -->
            <div class="contact-form-card">
                <h3>Envoyez-nous un <span class="text-green">message</span></h3>

                <form action="#" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" id="name" name="name" placeholder="Votre nom">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com">
                    </div>

                    <div class="form-group">
                        <label for="subject">Sujet</label>
                        <select id="subject" name="subject">
                            <option value="">Choisissez un sujet</option>
                            <option value="commande">Question sur une commande</option>
                            <option value="produit">Information sur un produit</option>
                            <option value="livraison">Livraison</option>
                            <option value="partenariat">Partenariat</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Votre message..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                </form>
            </div>

            <!-- INFOS DE CONTACT -->
            <div class="contact-info">
                <h3>Nos <span class="text-gold">coordonnées</span></h3>

                <div class="contact-info-list">

                    <!-- ADRESSE -->
                    <div class="info-card border-green">
                        <div class="info-icon text-green">📍</div>
                        <div>
                            <h4>Adresse</h4>
                            <p>TICAFRIQUE<br>Abidjan, Côte d'Ivoire</p>
                        </div>
                    </div>

                    <!-- TÉLÉPHONE -->
                    <div class="info-card border-gold">
                        <div class="info-icon text-gold">📞</div>
                        <div>
                            <h4>Téléphone / WhatsApp</h4>
                            <p>
                                <a href="tel:+2250556669299" style="color: inherit; text-decoration: none;">
                                    05 56 66 92 99
                                </a>
                            </p>
                            <small style="color: #6c757d; font-size: 0.85rem;">Commandes &amp; renseignements</small>
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="info-card border-green">
                        <div class="info-icon text-green">✉️</div>
                        <div>
                            <h4>Email</h4>
                            <p>commercial@lareservenaturelle.ci</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================
     MAP
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

            <a href="https://www.google.com/maps/place/TICAFRIQUE/@5.3960923,-3.9934646,17z/data=!3m1!4b1!4m6!3m5!1s0xfc194a6d23e34df:0xb57679beb78c7b98!8m2!3d5.3960923!4d-3.9908897!16s%2Fg%2F11c75t9qls?entry=ttu&g_ep=EgoyMDI2MTAwNi4wIKXMDSoASAFQAw%3D%3D"
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