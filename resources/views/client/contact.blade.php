@extends('layouts.app')

@section('title', 'Contact - La Réserve Naturelle')

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
        <p>Nous sommes à votre écoute pour toute question ou demande</p>
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
                    <div class="info-card border-green">
                        <div class="info-icon text-green">📍</div>
                        <div>
                            <h4>Adresse</h4>
                            <p>Abidjan, Côte d'Ivoire<br>Zone 4, Rue des Jardins</p>
                        </div>
                    </div>

                    <div class="info-card border-gold">
                        <div class="info-icon text-gold">📞</div>
                        <div>
                            <h4>Téléphone</h4>
                            <p>+225 01 23 45 67 89<br>+225 01 23 45 67 90</p>
                        </div>
                    </div>

                    <div class="info-card border-green">
                        <div class="info-icon text-green">✉️</div>
                        <div>
                            <h4>Email</h4>
                            <p>contact@lareservenaturelle.ci<br>support@lareservenaturelle.ci</p>
                        </div>
                    </div>

                    <div class="info-card border-gold">
                        <div class="info-icon text-gold">🕐</div>
                        <div>
                            <h4>Horaires</h4>
                            <p>Lundi - Samedi : 8h - 19h<br>Dimanche : Fermé</p>
                        </div>
                    </div>
                </div>

                <!-- RÉSEAUX SOCIAUX -->
                <div class="social-card">
                    <h4>Suivez-nous</h4>
                    <div class="social-links">
                        <a href="#" class="social-btn whatsapp" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="social-btn facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-btn instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-btn twitter" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
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
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.74890170723!2d-4.06931645!3d5.35995115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1d2be6f440d8b%3A0xde4fa0755586a55c!2sAbidjan%2C%20C%C3%B4te%20d%27Ivoire!5e0!3m2!1sfr!2sfr!4v1700000000000!5m2!1sfr!2sfr"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <a href="https://www.google.com/maps/dir//Abidjan,+C%C3%B4te+d%27Ivoire/@5.35995115,-4.06931645,12z"
               target="_blank"
               class="map-directions-btn">
                <i class="fas fa-directions"></i> Obtenir l'itinéraire
            </a>
        </div>

        <div class="map-address">
            <i class="fas fa-map-marker-alt text-green"></i>
            <strong>Zone 4, Rue des Jardins</strong> - Abidjan, Côte d'Ivoire
        </div>
    </div>
</section>

@endsection