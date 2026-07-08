@extends('layouts.app')

@section('title', 'Contact - La Réserve Naturelle')

@section('content')

<!-- ====== BANNIÈRE CONTACT ====== -->
<section style="background: linear-gradient(135deg, #055936 0%, #0B7A48 60%, #D79A05 100%); 
                padding: 80px 40px; 
                border-radius: 16px; 
                color: white;
                margin-bottom: 50px;
                text-align: center;
                position: relative;
                overflow: hidden;">
    <div style="position: absolute; top: -30%; right: -5%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -20%; left: 10%; width: 300px; height: 300px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="font-size: 64px; margin-bottom: 15px;">📞</div>
        <h1 style="font-family: 'Playfair Display', Georgia, serif; font-size: clamp(32px, 4vw, 48px); margin-bottom: 15px;">
            Contactez-nous
        </h1>
        <p style="font-size: clamp(16px, 1.3vw, 20px); max-width: 600px; margin: 0 auto; opacity: 0.9;">
            Nous sommes à votre écoute pour toute question ou demande
        </p>
    </div>
</section>

<!-- ====== CONTACT FORMULAIRE ET INFOS ====== -->
<section class="section" style="padding: 0 0 40px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">
            <!-- FORMULAIRE -->
            <div style="background: var(--white); padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid var(--line);">
                <h3 style="font-family: 'Playfair Display', Georgia, serif; color: var(--brown); font-size: 24px; margin-bottom: 25px;">
                    Envoyez-nous un <span style="color: var(--green);">message</span>
                </h3>

                <form action="#" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 20px;">
                        <label for="name" style="display: block; font-weight: 600; color: var(--brown); margin-bottom: 8px;">Nom complet</label>
                        <input type="text" id="name" name="name" placeholder="Votre nom" 
                               style="width: 100%; padding: 14px 18px; border: 2px solid var(--line); border-radius: 8px; font-size: 15px; transition: border-color 0.3s; font-family: inherit;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-weight: 600; color: var(--brown); margin-bottom: 8px;">Email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" 
                               style="width: 100%; padding: 14px 18px; border: 2px solid var(--line); border-radius: 8px; font-size: 15px; transition: border-color 0.3s; font-family: inherit;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="subject" style="display: block; font-weight: 600; color: var(--brown); margin-bottom: 8px;">Sujet</label>
                        <select id="subject" name="subject" 
                                style="width: 100%; padding: 14px 18px; border: 2px solid var(--line); border-radius: 8px; font-size: 15px; transition: border-color 0.3s; font-family: inherit; background: white; appearance: none;">
                            <option value="">Choisissez un sujet</option>
                            <option value="commande">Question sur une commande</option>
                            <option value="produit">Information sur un produit</option>
                            <option value="livraison">Livraison</option>
                            <option value="partenariat">Partenariat</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label for="message" style="display: block; font-weight: 600; color: var(--brown); margin-bottom: 8px;">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Votre message..." 
                                  style="width: 100%; padding: 14px 18px; border: 2px solid var(--line); border-radius: 8px; font-size: 15px; transition: border-color 0.3s; font-family: inherit; resize: vertical;"></textarea>
                    </div>

                    <button type="submit" style="width: 100%; padding: 16px; background: var(--green); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                </form>
            </div>

            <!-- INFORMATIONS DE CONTACT -->
            <div>
                <h3 style="font-family: 'Playfair Display', Georgia, serif; color: var(--brown); font-size: 24px; margin-bottom: 25px;">
                    Nos <span style="color: var(--gold);">coordonnées</span>
                </h3>

                <div style="display: flex; flex-direction: column; gap: 25px;">
                    <div style="display: flex; align-items: flex-start; gap: 20px; background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border-left: 4px solid var(--green);">
                        <div style="font-size: 28px; color: var(--green);">📍</div>
                        <div>
                            <h4 style="color: var(--brown); font-size: 16px; margin-bottom: 5px;">Adresse</h4>
                            <p style="color: var(--muted); font-size: 15px; line-height: 1.6;">
                                Abidjan, Côte d'Ivoire<br>
                                Zone 4, Rue des Jardins
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 20px; background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border-left: 4px solid var(--gold);">
                        <div style="font-size: 28px; color: var(--gold);">📞</div>
                        <div>
                            <h4 style="color: var(--brown); font-size: 16px; margin-bottom: 5px;">Téléphone</h4>
                            <p style="color: var(--muted); font-size: 15px; line-height: 1.6;">
                                +225 01 23 45 67 89<br>
                                +225 01 23 45 67 90
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 20px; background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border-left: 4px solid var(--green);">
                        <div style="font-size: 28px; color: var(--green);">✉️</div>
                        <div>
                            <h4 style="color: var(--brown); font-size: 16px; margin-bottom: 5px;">Email</h4>
                            <p style="color: var(--muted); font-size: 15px; line-height: 1.6;">
                                contact@lareservenaturelle.ci<br>
                                support@lareservenaturelle.ci
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 20px; background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border-left: 4px solid var(--gold);">
                        <div style="font-size: 28px; color: var(--gold);">🕐</div>
                        <div>
                            <h4 style="color: var(--brown); font-size: 16px; margin-bottom: 5px;">Horaires</h4>
                            <p style="color: var(--muted); font-size: 15px; line-height: 1.6;">
                                Lundi - Samedi : 8h - 19h<br>
                                Dimanche : Fermé
                            </p>
                        </div>
                    </div>
                </div>

                <!-- RÉSEAUX SOCIAUX -->
                <div style="margin-top: 30px; background: var(--white); padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); text-align: center;">
                    <h4 style="color: var(--brown); font-size: 16px; margin-bottom: 15px;">Suivez-nous</h4>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="#" style="width: 48px; height: 48px; border-radius: 50%; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; transition: transform 0.3s;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" style="width: 48px; height: 48px; border-radius: 50%; background: #4267B2; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; transition: transform 0.3s;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" style="width: 48px; height: 48px; border-radius: 50%; background: #E1306C; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; transition: transform 0.3s;">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" style="width: 48px; height: 48px; border-radius: 50%; background: #1DA1F2; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; transition: transform 0.3s;">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== MAP - VERSION CORRIGÉE ====== -->
<section class="section" style="background: var(--white); padding: 60px 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 30px;">
            <p class="eyebrow" style="color: var(--green); text-transform: uppercase; font-weight: 800; letter-spacing: 0.14em; font-size: 13px;">Où nous trouver</p>
            <h2 style="font-family: 'Playfair Display', Georgia, serif; color: var(--brown); font-size: clamp(28px, 3vw, 36px);">
                Notre <span style="color: var(--gold);">localisation</span>
            </h2>
        </div>

        <!-- CARTE GOOGLE MAPS INTÉGRÉE -->
        <div style="border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid var(--line); position: relative;">
            <!-- L'iframe de la carte -->
            <div style="width: 100%; height: 450px; background: #e8ecf1;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.74890170723!2d-4.06931645!3d5.35995115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1d2be6f440d8b%3A0xde4fa0755586a55c!2sAbidjan%2C%20C%C3%B4te%20d%27Ivoire!5e0!3m2!1sfr!2sfr!4v1700000000000!5m2!1sfr!2sfr" 
                    width="100%" 
                    height="450" 
                    style="border:0; display: block;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            
            <!-- Bouton pour ouvrir dans Google Maps -->
            <a href="https://www.google.com/maps/dir//Abidjan,+C%C3%B4te+d%27Ivoire/@5.35995115,-4.06931645,12z" 
               target="_blank" 
               style="position: absolute; bottom: 20px; right: 20px; background: #055936; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: all 0.3s; z-index: 10;"
               onmouseover="this.style.transform='scale(1.05)'" 
               onmouseout="this.style.transform='scale(1)'">
                <i class="fas fa-directions"></i> Obtenir l'itinéraire
            </a>
        </div>
        
        <!-- Adresse sous la carte -->
        <div style="text-align: center; margin-top: 15px; color: var(--muted); font-size: 14px;">
            <i class="fas fa-map-marker-alt" style="color: var(--green);"></i> 
            <strong style="color: var(--brown);">Zone 4, Rue des Jardins</strong> - Abidjan, Côte d'Ivoire
        </div>
    </div>
</section>

@endsection