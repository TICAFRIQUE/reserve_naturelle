{{-- resources/views/client/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'La Réserve Naturelle - Catalogue')

@php
    $isPaginated = $products instanceof \Illuminate\Contracts\Pagination\Paginator;
@endphp

@section('content')

<!-- ====== HERO ====== -->
<section class="hero" style="
    position: relative;
    background:
        linear-gradient(90deg, rgba(5,89,54,0.95) 0%, rgba(5,89,54,0.75) 35%, rgba(5,89,54,0.15) 70%, rgba(5,89,54,0) 100%),
        url('{{ asset('img/B_hero.jpeg') }}') center/cover no-repeat;
    border-radius: 0 0 32px 32px;
    overflow: hidden;
">
    <div class="container" style="
        max-width: 1180px;
        margin: 0 auto;
        padding: 70px 24px 60px;
    ">
        <div style="max-width: 640px;">
            <span style="
                display: inline-block;
                background: rgba(215,154,5,0.15);
                border: 1px solid var(--gold);
                color: var(--gold-light);
                font-size: 13px;
                font-weight: 600;
                padding: 6px 18px;
                border-radius: 30px;
                margin-bottom: 18px;
            ">Expérience Nature & Terroir</span>

            <h1 style="
                font-family: 'Playfair Display', Georgia, serif;
                color: var(--white);
                font-size: clamp(2rem, 4vw, 3.2rem);
                font-weight: 800;
                line-height: 1.15;
                margin: 0 0 16px;
            ">
                Le meilleur de la<br>
                <span style="color: var(--gold-light);">terre ivoirienne</span>
            </h1>

            <p style="
                color: rgba(255,255,255,0.8);
                font-size: 1.05rem;
                line-height: 1.7;
                max-width: 460px;
                margin: 0 0 28px;
            ">
                Des produits naturels sélectionnés directement auprès des producteurs locaux, pour une qualité authentique jusqu'à votre table.
            </p>

            <a href="#catalogue" style="
                display: inline-block;
                background: var(--gold);
                color: var(--white);
                padding: 14px 32px;
                border-radius: 30px;
                font-weight: 600;
                text-decoration: none;
            ">Voir le catalogue</a>

            <div style="display: flex; gap: 28px; margin-top: 40px; flex-wrap: wrap;">
                <div>
                    <div style="color: var(--white); font-weight: 700; font-size: 18px;">100%</div>
                    <div style="color: rgba(255,255,255,0.65); font-size: 13px;">Produits locaux</div>
                </div>
                <div>
                    <div style="color: var(--white); font-weight: 700; font-size: 18px;">4.8/5</div>
                    <div style="color: rgba(255,255,255,0.65); font-size: 13px;">Avis clients</div>
                </div>
                <div>
                    <div style="color: var(--white); font-weight: 700; font-size: 18px;">Bio</div>
                    <div style="color: rgba(255,255,255,0.65); font-size: 13px;">Agriculture durable</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== SECTION PRODUITS ====== -->
<section class="section" id="catalogue">
    <div class="container">
        <div class="section-heading">
            <p>Notre sélection</p>
            <h2>Produits du moment</h2>
            @if(($isPaginated ? $products->total() : $products->count()) > 0)
                <p style="color: var(--muted); font-size: 14px; margin-top: 5px;">
                    {{ $isPaginated ? $products->total() : $products->count() }} produit(s) trouvé(s)
                </p>
            @endif
        </div>

        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card" style="
                        background: var(--white);
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
                        transition: all 0.3s ease;
                        border: 1px solid rgba(0,0,0,0.04);
                        display: flex;
                        flex-direction: column;
                        height: 100%;
                    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 8px 35px rgba(11,122,72,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.06)'">

                        <div style="
                            height: 220px;
                            background: #f8f5f0;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            overflow: hidden;
                            position: relative;
                        ">
                            @if($product->image_path)
                                <a href="{{ route('client.products.show', $product) }}">
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                         alt="{{ $product->designation }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                            @else
                                <div style="text-align: center; color: #b8860b;">
                                    <i class="fas fa-box" style="font-size: 48px; display: block; margin-bottom: 8px;"></i>
                                    <span style="font-size: 14px;">Pas d'image</span>
                                </div>
                            @endif

                            @if(($product->qte_dispo ?? 0) > 0)
                                <span style="
                                    position: absolute;
                                    top: 12px;
                                    right: 12px;
                                    background: var(--green);
                                    color: white;
                                    padding: 4px 14px;
                                    border-radius: 30px;
                                    font-size: 11px;
                                    font-weight: 600;
                                ">
                                    <i class="fas fa-check-circle" style="margin-right: 4px;"></i> Disponible
                                </span>
                            @else
                                <span style="
                                    position: absolute;
                                    top: 12px;
                                    right: 12px;
                                    background: #dc3545;
                                    color: white;
                                    padding: 4px 14px;
                                    border-radius: 30px;
                                    font-size: 11px;
                                    font-weight: 600;
                                ">
                                    <i class="fas fa-times-circle" style="margin-right: 4px;"></i> Rupture
                                </span>
                            @endif
                        </div>

                        <div style="padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; gap: 8px;">
                            <h4 style="
                                font-size: 17px;
                                font-weight: 700;
                                margin: 0;
                                line-height: 1.3;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                            ">
                                <a href="{{ route('client.products.show', $product) }}" style="color: var(--text); text-decoration: none;">
                                    {{ $product->designation }}
                                </a>
                            </h4>

                            <div style="font-size: 13px; color: var(--muted);">
                                <i class="fas fa-folder" style="color: var(--gold);"></i>
                                {{ $product->category->nom ?? 'Non catégorisé' }}
                            </div>

                            @if($product->description)
                                <p style="
                                    font-size: 13px;
                                    color: var(--muted);
                                    margin: 4px 0 0;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;
                                    overflow: hidden;
                                    flex: 1;
                                ">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                            @endif

                            <div style="
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                margin-top: 10px;
                                padding-top: 12px;
                                border-top: 1px solid rgba(0,0,0,0.05);
                            ">
                                <div style="font-size: 20px; font-weight: 800; color: var(--green-dark);">
                                    {{ number_format($product->prix_vente ?? 0, 0, ',', ' ') }} FCFA
                                </div>
                                <div style="font-size: 12px; color: var(--muted);">
                                    <i class="fas fa-cube" style="color: var(--gold);"></i>
                                    {{ $product->qte_dispo ?? 0 }} unités
                                </div>
                            </div>

                           @if($product->qte_dispo > 0)
                                <x-add-to-cart-button :product-id="$product->id" />
                            @else
                                <span style="color: var(--muted); font-size: 13px;">Indisponible</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($isPaginated && $products->hasPages())
                <div style="margin-top: 40px; display: flex; justify-content: center;">
                    {{ $products->links() }}
                </div>
            @elseif(!$isPaginated)
                <div style="margin-top: 40px; display: flex; justify-content: center;">
                    <a href="{{ route('client.products.catalogue') }}"
                       style="background: var(--green); color: white; padding: 12px 35px; border-radius: 50px; font-weight: 600; text-decoration: none;">
                        Voir tout le catalogue →
                    </a>
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 60px 20px; background: #f5f5f5; border-radius: 12px;">
                <div style="font-size: 64px; margin-bottom: 20px;">🔍</div>
                <h3 style="color: var(--text); font-size: 24px; margin-bottom: 10px;">Aucun produit trouvé</h3>
                <p style="color: var(--muted); max-width: 400px; margin: 0 auto 20px;">Aucun produit ne correspond à vos critères de recherche. Essayez de modifier vos filtres.</p>
                <a href="{{ route('client.products.index') }}" class="btn" style="background: var(--green); color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; display: inline-block;">Voir tous les produits</a>
            </div>
        @endif

        @guest
            <div style="text-align: center; margin-top: 50px; padding: 40px 30px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 16px; border: 2px solid var(--green);">
                <div style="font-size: 48px; margin-bottom: 10px;">🛒</div>
                <h3 style="color: var(--green-dark); font-size: 24px; margin-bottom: 10px;">Vous souhaitez commander ?</h3>
                <p style="color: var(--text); font-size: 16px; max-width: 500px; margin: 0 auto 20px;">Connectez-vous ou créez un compte pour passer votre commande et profiter de nos produits naturels.</p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('login') }}" class="btn" style="background: var(--green); color: var(--white); padding: 12px 35px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block;">🔐 Se connecter</a>
                    <a href="{{ route('register') }}" class="btn" style="background: transparent; color: var(--green); border: 2px solid var(--green); padding: 12px 35px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block;">📝 S'inscrire</a>
                </div>
            </div>
        @endguest
    </div>
</section>

<!-- ====== SECTION À PROPOS ====== -->
<section class="section split" id="apropos">
    <div class="container split-inner">
        <div>
            <p class="eyebrow green">🌿 La Réserve Naturelle</p>
            <h2>Des produits naturels, <span style="color: var(--green);">authentiques</span> et locaux</h2>
            <p class="lead">Nous sélectionnons avec soin des produits issus de l'agriculture ivoirienne pour vous offrir le meilleur de la nature, tout en soutenant les producteurs locaux.</p>
            <ul class="check-list">
                <li>🌾 <strong>Produits 100% naturels</strong> — Sans additifs ni conservateurs artificiels</li>
                <li>🇨🇮 <strong>Filière locale</strong> — Soutien aux producteurs ivoiriens</li>
                <li>📦 <strong>Qualité garantie</strong> — Sélection rigoureuse de nos produits</li>
                <li>🌱 <strong>Engagement durable</strong> — Respect de l'environnement et des cycles naturels</li>
            </ul>

            <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 25px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="background: var(--green); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">⭐</span>
                    <div>
                        <div style="font-weight: 700; color: var(--text);">4.8/5</div>
                        <div style="font-size: 13px; color: var(--muted);">Avis clients</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="background: var(--green); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🏆</span>
                    <div>
                        <div style="font-weight: 700; color: var(--text);">100%</div>
                        <div style="font-size: 13px; color: var(--muted);">Produits locaux</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="background: var(--green); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🌿</span>
                    <div>
                        <div style="font-weight: 700; color: var(--text);">Bio</div>
                        <div style="font-size: 13px; color: var(--muted);">Agriculture durable</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="about-visual" style="
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: 16px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            border: 2px solid var(--green);
            position: relative;
            overflow: hidden;
        ">
            <div style="font-size: 80px; margin-bottom: 15px;">🌍</div>
            <h3 style="color: var(--green-dark); font-size: 22px; margin: 0; text-align: center;">La Réserve Naturelle</h3>
            <p style="color: var(--text); text-align: center; max-width: 280px; margin: 8px 0 0;">Des produits naturels pour une vie saine et durable</p>

            <div style="display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap; justify-content: center;">
                <span style="background: white; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">🌾 Céréales</span>
                <span style="background: white; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">🌿 Huiles</span>
                <span style="background: white; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">🥜 Légumineuses</span>
                <span style="background: white; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">🌶️ Épices</span>
                <span style="background: white; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">🍯 Fruits secs</span>
            </div>

            <div style="position: absolute; bottom: -30px; right: -30px; font-size: 120px; opacity: 0.08; pointer-events: none;">🌿</div>
            <div style="position: absolute; top: -20px; left: -20px; font-size: 80px; opacity: 0.08; pointer-events: none;">🌾</div>
        </div>
    </div>
</section>

@endsection

@if(request()->hasAny(['search', 'category', 'page']))
@push('scripts')
<script>
    document.getElementById('catalogue')?.scrollIntoView({ behavior: 'smooth' });
</script>
@endpush
@endif