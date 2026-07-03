@extends('layouts.app')

@section('title', 'À propos - La Réserve Naturelle')

@section('content')

<!-- ====== BANNIÈRE À PROPOS ====== -->
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
        <div style="font-size: 64px; margin-bottom: 15px;">🌿</div>
        <h1 style="font-family: 'Playfair Display', Georgia, serif; font-size: clamp(32px, 4vw, 48px); margin-bottom: 15px;">
            À propos de nous
        </h1>
        <p style="font-size: clamp(16px, 1.3vw, 20px); max-width: 600px; margin: 0 auto; opacity: 0.9;">
            Découvrez l'histoire et les valeurs de La Réserve Naturelle
        </p>
    </div>
</section>

<!-- ====== HISTOIRE ====== -->
<section class="section" style="padding: 0 0 40px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center;">
            <div>
                <p class="eyebrow" style="color: var(--green); text-transform: uppercase; font-weight: 800; letter-spacing: 0.14em; font-size: 13px;">Notre histoire</p>
                <h2 style="font-family: 'Playfair Display', Georgia, serif; color: var(--brown); font-size: clamp(28px, 3vw, 36px); margin: 15px 0 20px;">
                    Une passion pour la <span style="color: var(--green);">nature</span> et les <span style="color: var(--gold);">produits locaux</span>
                </h2>
                <p style="color: var(--muted); line-height: 1.8; font-size: 16px; margin-bottom: 15px;">
                    La Réserve Naturelle est née d'une passion commune pour les produits authentiques et naturels de Côte d'Ivoire. 
                    Fondée en 2020, notre boutique a pour mission de mettre en avant la richesse du terroir ivoirien.
                </p>
                <p style="color: var(--muted); line-height: 1.8; font-size: 16px;">
                    Nous sélectionnons avec soin chaque produit auprès de producteurs locaux, garantissant ainsi qualité, 
                    fraîcheur et traçabilité. Notre engagement est de promouvoir une agriculture durable et responsable.
                </p>
                <div style="display: flex; gap: 30px; margin-top: 30px;">
                    <div>
                        <div style="font-size: 32px; color: var(--green); font-weight: 900;">2020</div>
                        <p style="color: var(--muted); font-size: 14px;">Année de création</p>
                    </div>
                    <div>
                        <div style="font-size: 32px; color: var(--green); font-weight: 900;">100+</div>
                        <p style="color: var(--muted); font-size: 14px;">Produits disponibles</p>
                    </div>
                    <div>
                        <div style="font-size: 32px; color: var(--green); font-weight: 900;">50+</div>
                        <p style="color: var(--muted); font-size: 14px;">Producteurs locaux</p>
                    </div>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, var(--green-dark), var(--green)); 
                        border-radius: 16px; 
                        min-height: 350px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 80px;
                        box-shadow: 0 8px 30px rgba(11, 122, 72, 0.2);">
                🌾
            </div>
        </div>
    </div>
</section>

<!-- ====== NOS VALEURS ====== -->
<section class="section" style="background: var(--white); padding: 60px 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <p class="eyebrow" style="color: var(--green); text-transform: uppercase; font-weight: 800; letter-spacing: 0.14em; font-size: 13px;">Nos valeurs</p>
            <h2 style="font-family: 'Playfair Display', Georgia, serif; color: var(--brown); font-size: clamp(28px, 3vw, 36px);">
                Ce qui nous <span style="color: var(--gold);">anime</span>
            </h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <div style="background: var(--cream); padding: 35px 25px; border-radius: 12px; text-align: center; transition: transform 0.3s; border: 1px solid var(--line);">
                <div style="font-size: 48px; margin-bottom: 15px;">🌱</div>
                <h3 style="color: var(--brown); font-size: 20px; margin-bottom: 10px;">Authenticité</h3>
                <p style="color: var(--muted); font-size: 14px; line-height: 1.6;">
                    Des produits naturels, sans additifs, respectant les savoir-faire traditionnels.
                </p>
            </div>

            <div style="background: var(--cream); padding: 35px 25px; border-radius: 12px; text-align: center; transition: transform 0.3s; border: 1px solid var(--line);">
                <div style="font-size: 48px; margin-bottom: 15px;">🤝</div>
                <h3 style="color: var(--brown); font-size: 20px; margin-bottom: 10px;">Équité</h3>
                <p style="color: var(--muted); font-size: 14px; line-height: 1.6;">
                    Des relations justes et transparentes avec nos producteurs locaux.
                </p>
            </div>

            <div style="background: var(--cream); padding: 35px 25px; border-radius: 12px; text-align: center; transition: transform 0.3s; border: 1px solid var(--line);">
                <div style="font-size: 48px; margin-bottom: 15px;">♻️</div>
                <h3 style="color: var(--brown); font-size: 20px; margin-bottom: 10px;">Durabilité</h3>
                <p style="color: var(--muted); font-size: 14px; line-height: 1.6;">
                    Un engagement pour une agriculture respectueuse de l'environnement.
                </p>
            </div>

            <div style="background: var(--cream); padding: 35px 25px; border-radius: 12px; text-align: center; transition: transform 0.3s; border: 1px solid var(--line);">
                <div style="font-size: 48px; margin-bottom: 15px;">❤️</div>
                <h3 style="color: var(--brown); font-size: 20px; margin-bottom: 10px;">Passion</h3>
                <p style="color: var(--muted); font-size: 14px; line-height: 1.6;">
                    La passion du bon produit et du partage de notre terroir.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ====== ÉQUIPE ====== -->
<section class="section" style="padding: 60px 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <p class="eyebrow" style="color: var(--green); text-transform: uppercase; font-weight: 800; letter-spacing: 0.14em; font-size: 13px;">Notre équipe</p>
            <h2 style="font-family: 'Playfair Display', Georgia, serif; color: var(--brown); font-size: clamp(28px, 3vw, 36px);">
                Ceux qui font <span style="color: var(--gold);">la différence</span>
            </h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px;">
            <div style="text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--green), var(--gold)); margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: white;">
                    👩‍💼
                </div>
                <h4 style="color: var(--brown); font-size: 18px;">Mme Kouadio</h4>
                <p style="color: var(--muted); font-size: 14px;">Fondatrice & Directrice</p>
            </div>

            <div style="text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--green), var(--gold)); margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: white;">
                    👨‍🌾
                </div>
                <h4 style="color: var(--brown); font-size: 18px;">M. Koné</h4>
                <p style="color: var(--muted); font-size: 14px;">Responsable approvisionnement</p>
            </div>

            <div style="text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--green), var(--gold)); margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: white;">
                    👩‍🌾
                </div>
                <h4 style="color: var(--brown); font-size: 18px;">Mlle Yao</h4>
                <p style="color: var(--muted); font-size: 14px;">Responsable qualité</p>
            </div>

            <div style="text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--green), var(--gold)); margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: white;">
                    👨‍💻
                </div>
                <h4 style="color: var(--brown); font-size: 18px;">M. N'Guessan</h4>
                <p style="color: var(--muted); font-size: 14px;">Responsable digital</p>
            </div>
        </div>
    </div>
</section>

@endsection