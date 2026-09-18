<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMouvement;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Entrée en stock
     * (achat, retour client)
     */
    public function entreeStock(
        Product $product,
        int $quantite,
        string $type,
        object $source,
        ?string $notes = null,
        ?int $userId = null
    ): void {
        $stockAvant = $product->qte_dispo;
        $cmpAvant   = $product->cmp;

        $nouveauCmp = $cmpAvant;

        /*
         * Pour un achat, on recalcule le CMP
         * avec le prix d'achat de la ligne.
         */
        if ($type === 'entree_achat') {

            $ligne = $source->produits
                ->where('product_id', $product->id)
                ->first();

            if (!$ligne) {
                throw new \Exception(
                    "Impossible de trouver la ligne d'achat du produit {$product->designation}."
                );
            }

            $prixUnitaire = $ligne->prix_unitaire;

            $nouveauCmp = $this->recalculerCMP(
                $stockAvant,
                $cmpAvant,
                $quantite,
                $prixUnitaire
            );
        }

        $stockApres = $stockAvant + $quantite;

        /*
         * Mise à jour du produit
         */
        $product->update([
            'qte_dispo' => $stockApres,
            'cmp'       => $nouveauCmp,
        ]);

        /*
         * Utilisateur :
         *
         * - Seeder => $userId est fourni
         * - Application réelle => Auth::id()
         */
        $stockUserId = $userId ?? Auth::id();

        /*
         * Enregistrement du mouvement
         */
        StockMouvement::create([
            'product_id'  => $product->id,
            'type'        => $type,
            'sens'        => 'entree',
            'quantite'    => $quantite,
            'stock_avant' => $stockAvant,
            'stock_apres' => $stockApres,
            'cmp_avant'   => $cmpAvant,
            'cmp_apres'   => $nouveauCmp,
            'source_type' => get_class($source),
            'source_id'   => $source->id,
            'user_id'     => $stockUserId,
            'notes'       => $notes,
        ]);
    }

    /**
     * Sortie de stock
     * (commande, retour fournisseur, perte/casse)
     */
    public function sortieStock(
        Product $product,
        int $quantite,
        string $type,
        object $source,
        ?string $notes = null,
        ?int $userId = null
    ): void {
        $stockAvant = $product->qte_dispo;
        $cmpAvant   = $product->cmp;

        /*
         * Vérification du stock.
         *
         * Une perte/casse peut exceptionnellement
         * dépasser le stock disponible.
         */
        if (
            $type !== 'perte_casse'
            && $quantite > $stockAvant
        ) {
            throw new \Exception(
                "Stock insuffisant pour le produit {$product->designation}."
            );
        }

        $stockApres = max(
            0,
            $stockAvant - $quantite
        );

        /*
         * Mise à jour du stock
         */
        $product->update([
            'qte_dispo' => $stockApres,
        ]);

        /*
         * Utilisateur :
         *
         * - Seeder => $userId fourni
         * - Application réelle => Auth::id()
         */
        $stockUserId = $userId ?? Auth::id();

        /*
         * Enregistrement du mouvement
         */
        StockMouvement::create([
            'product_id'  => $product->id,
            'type'        => $type,
            'sens'        => 'sortie',
            'quantite'    => $quantite,
            'stock_avant' => $stockAvant,
            'stock_apres' => $stockApres,
            'cmp_avant'   => $cmpAvant,
            'cmp_apres'   => $cmpAvant,
            'source_type' => get_class($source),
            'source_id'   => $source->id,
            'user_id'     => $stockUserId,
            'notes'       => $notes,
        ]);
    }

    /**
     * Ajustement manuel
     * (inventaire physique ou correction)
     */
    public function ajustementStock(
        Product $product,
        int $qteReelle,
        string $type,
        object $source,
        ?string $notes = null,
        ?int $userId = null
    ): void {
        $stockAvant = $product->qte_dispo;
        $cmpAvant   = $product->cmp;

        $ecart = $qteReelle - $stockAvant;

        /*
         * Aucun mouvement si aucun écart.
         */
        if ($ecart === 0) {
            return;
        }

        $sens = $ecart > 0
            ? 'entree'
            : 'sortie';

        /*
         * Mise à jour du stock
         */
        $product->update([
            'qte_dispo' => $qteReelle,
        ]);

        /*
         * Utilisateur :
         *
         * - Seeder => $userId fourni
         * - Application réelle => Auth::id()
         */
        $stockUserId = $userId ?? Auth::id();

        /*
         * Enregistrement du mouvement
         */
        StockMouvement::create([
            'product_id'  => $product->id,
            'type'        => $type,
            'sens'        => $sens,
            'quantite'    => abs($ecart),
            'stock_avant' => $stockAvant,
            'stock_apres' => $qteReelle,
            'cmp_avant'   => $cmpAvant,
            'cmp_apres'   => $cmpAvant,
            'source_type' => get_class($source),
            'source_id'   => $source->id,
            'user_id'     => $stockUserId,
            'notes'       => $notes,
        ]);
    }

    /**
     * CMP =
     *
     * (stock_actuel × cmp_actuel
     *  + qte_entree × prix_unitaire)
     * /
     * (stock_actuel + qte_entree)
     */
    private function recalculerCMP(
        int $stockActuel,
        int $cmpActuel,
        int $qteEntree,
        int $prixUnitaire
    ): int {
        $totalUnites =
            $stockActuel + $qteEntree;

        if ($totalUnites === 0) {
            return $prixUnitaire;
        }

        return (int) round(
            (
                ($stockActuel * $cmpActuel)
                +
                ($qteEntree * $prixUnitaire)
            )
            /
            $totalUnites
        );
    }
}

/* ancien <css>
/* ============================================
   VARIABLES GLOBALES
============================================ */
// :root {
//     --green: #0B7A48;
//     --green-dark: #055936;
//     --gold: #D79A05;
//     --gold-light: #F2B832;
//     --cream: #FAF6EB;
//     --brown: #5C3D1E;
//     --text: #2F2A22;
//     --muted: #777064;
//     --white: #fff;
//     --danger: #B83232;
//     --warning: #C98200;
//     --success: #16804A;
//     --line: rgba(11, 122, 72, 0.14);
// }

// /* ============================================
//    RESET & BASE
// ============================================ */
// * {
//     box-sizing: border-box;
//     margin: 0;
//     padding: 0;
// }

// html, body {
//     overflow-x: hidden;
//     max-width: 100%;
// }

// body {
//     font-family: 'Lato', Arial, sans-serif;
//     color: var(--text);
//     background: var(--cream);
//     -webkit-text-size-adjust: 100%;
// }

// a {
//     text-decoration: none;
//     color: inherit;
// }

// img, svg, video {
//     max-width: 100%;
//     height: auto;
// }

// /* ============================================
//    HEADER / NAVBAR
// ============================================ */
// .header {
//     background: rgba(250, 246, 235, 0.96);
//     position: sticky;
//     top: 0;
//     z-index: 100;
// }

// .nav {
//     min-height: 86px;
//     display: flex;
//     align-items: center;
//     justify-content: space-between;
//     gap: 28px;
//     width: min(1180px, 92%);
//     margin: auto;
//     position: relative;
// }

// /* ============================================
//    LOGO / BRAND
// ============================================ */
// .brand {
//     display: flex;
//     align-items: center;
//     gap: 14px;
//     text-decoration: none;
//     min-width: 200px;
// }

// .brand-icon {
//     width: 54px;
//     height: 54px;
//     border-radius: 50%;
//     overflow: hidden;
//     flex-shrink: 0;
//     border: 2px solid var(--gold);
//     padding: 4px;
//     background: var(--white);
//     box-shadow: 0 2px 12px rgba(11, 122, 72, 0.15);
//     transition: transform 0.3s ease, box-shadow 0.3s ease;
// }

// .brand:hover .brand-icon {
//     transform: scale(1.05);
//     box-shadow: 0 4px 20px rgba(11, 122, 72, 0.25);
// }

// .brand-icon img {
//     width: 100%;
//     height: 100%;
//     object-fit: cover;
//     border-radius: 50%;
//     display: block;
// }

// .brand-text {
//     display: flex;
//     flex-direction: column;
//     line-height: 1.1;
// }

// .brand-text strong {
//     font-family: 'Playfair Display', Georgia, serif;
//     font-size: clamp(15px, 2.5vw, 20px);
//     color: var(--brown);
//     font-weight: 900;
//     letter-spacing: -0.5px;
// }

// .brand-text em {
//     font-family: 'Dancing Script', cursive;
//     font-size: clamp(15px, 2.5vw, 21px);
//     color: var(--green);
//     font-style: normal;
//     font-weight: 700;
//     margin-top: -2px;
// }

// /* ============================================
//    HAMBURGER MENU
// ============================================ */
// .hamburger {
//     display: none;
//     flex-direction: column;
//     gap: 5px;
//     background: transparent;
//     border: none;
//     cursor: pointer;
//     padding: 8px;
//     z-index: 110;
//     transition: all 0.3s ease;
// }

// .hamburger .bar {
//     display: block;
//     width: 28px;
//     height: 3px;
//     background: var(--brown);
//     border-radius: 3px;
//     transition: all 0.3s ease;
//     transform-origin: center;
// }

// .hamburger.active .bar:nth-child(1) {
//     transform: translateY(8px) rotate(45deg);
// }

// .hamburger.active .bar:nth-child(2) {
//     opacity: 0;
//     transform: scaleX(0);
// }

// .hamburger.active .bar:nth-child(3) {
//     transform: translateY(-8px) rotate(-45deg);
// }

// .hamburger:hover .bar {
//     background: var(--green);
// }

// /* ============================================
//    NAVBAR LINKS
// ============================================ */
// .navbar {
//     display: flex;
//     align-items: center;
// }

// .nav-links {
//     list-style: none;
//     display: flex;
//     align-items: center;
//     gap: 1.8rem;
//     flex-wrap: wrap;
//     padding: 0.8rem 0;
// }

// .nav-links li {
//     position: relative;
// }

// .nav-links a {
//     color: var(--text);
//     text-decoration: none;
//     font-weight: 500;
//     font-size: clamp(14px, 1.2vw, 1.05rem);
//     padding: 0.5rem 0.2rem;
//     transition: color 0.25s ease;
//     white-space: nowrap;
// }

// .nav-links a:hover {
//     color: var(--success);
// }

// /* Soulignement animé sur liens principaux */
// .nav-links > li > a {
//     position: relative;
//     padding: 8px 4px;
//     font-weight: 500;
//     color: var(--text);
//     transition: color 0.3s ease;
// }

// .nav-links > li > a::after {
//     content: '';
//     position: absolute;
//     bottom: 0;
//     left: 50%;
//     width: 0;
//     height: 2px;
//     background: var(--green);
//     transition: all 0.3s ease;
//     transform: translateX(-50%);
// }

// .nav-links > li > a:hover::after {
//     width: 100%;
// }

// .nav-links > li > a:hover {
//     color: var(--green);
// }

// /* --- MENU DÉROULANT --- */
// .dropdown-menu {
//     display: none;
//     position: absolute;
//     top: 100%;
//     left: 0;
//     background: var(--text);
//     min-width: 200px;
//     list-style: none;
//     padding: 0.6rem 0;
//     border-radius: 0 0 10px 10px;
//     box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
//     flex-direction: column;
//     z-index: 105;
// }

// .dropdown-menu li {
//     width: 100%;
// }

// .dropdown-menu a {
//     display: block;
//     padding: 0.6rem 1.5rem;
//     color: #e2e8f0;
//     font-weight: 400;
//     transition: background 0.2s, color 0.2s;
//     white-space: nowrap;
// }

// .dropdown-menu a:hover {
//     background: #1e293b;
//     color: #facc15;
// }

// .nav-links li.dropdown:hover .dropdown-menu,
// .nav-links li.dropdown:focus-within .dropdown-menu {
//     display: flex;
// }

// .dropdown > a::after {
//     content: " ▾";
//     font-size: 0.75rem;
//     color: #94a3b8;
//     transition: transform 0.2s;
//     display: inline-block;
//     margin-left: 4px;
// }

// .nav-links li.dropdown:hover > a::after {
//     transform: rotate(180deg);
//     color: #facc15;
// }

// /* --- Bouton Panier --- */
// .cart-btn {
//     background: var(--green);
//     color: var(--white);
//     padding: 12px 18px;
//     border-radius: 6px;
//     font-weight: 700;
//     font-size: clamp(13px, 1.1vw, 1rem);
//     transition: background 0.3s ease, transform 0.3s ease;
//     white-space: nowrap;
//     display: inline-flex;
//     align-items: center;
//     gap: 6px;
// }

// .cart-btn:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px);
// }

// .cart-badge,
// .cart-btn span {
//     background: var(--gold);
//     padding: 2px 7px;
//     margin-left: 4px;
//     border-radius: 999px;
//     font-size: 0.85em;
// }

// /* ============================================
//    DROPDOWN PROFIL UTILISATEUR
// ============================================ */
// .user-avatar {
//     display: inline-flex;
//     align-items: center;
//     justify-content: center;
//     width: 32px;
//     height: 32px;
//     border-radius: 50%;
//     background: var(--green);
//     color: var(--white);
//     font-size: 13px;
//     font-weight: 700;
//     text-transform: uppercase;
//     flex-shrink: 0;
// }

// .user-avatar:hover {
//     background: var(--green-dark);
//     transform: scale(1.05);
// }

// .badge-admin {
//     background: var(--danger);
//     color: var(--white);
//     padding: 2px 10px;
//     border-radius: 12px;
//     font-size: 10px;
//     font-weight: 700;
//     text-transform: uppercase;
//     letter-spacing: 0.5px;
// }

// .badge-user {
//     background: #1976d2;
//     color: var(--white);
//     padding: 2px 10px;
//     border-radius: 12px;
//     font-size: 10px;
//     font-weight: 700;
//     text-transform: uppercase;
//     letter-spacing: 0.5px;
// }

// .nav-links .dropdown-profile .dropdown-menu {
//     min-width: 220px;
//     right: 0;
//     left: auto;
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
//     padding: 8px 0;
//     border: 1px solid rgba(0, 0, 0, 0.05);
// }

// .nav-links .dropdown-profile .dropdown-menu li a {
//     padding: 10px 20px;
//     color: var(--text);
//     font-size: 14px;
//     font-weight: 500;
//     display: flex;
//     align-items: center;
//     gap: 12px;
//     transition: all 0.2s ease;
// }

// .nav-links .dropdown-profile .dropdown-menu li a i {
//     width: 20px;
//     color: var(--muted);
//     font-size: 15px;
//     transition: color 0.2s ease;
// }

// .nav-links .dropdown-profile .dropdown-menu li a:hover {
//     background: rgba(11, 122, 72, 0.06);
//     color: var(--green);
//     padding-left: 24px;
// }

// .nav-links .dropdown-profile .dropdown-menu li a:hover i {
//     color: var(--green);
// }

// .dropdown-divider {
//     margin: 6px 0;
//     border: none;
//     border-top: 1px solid var(--line);
// }

// .btn-logout-dropdown {
//     background: none !important;
//     border: none !important;
//     color: var(--danger) !important;
//     cursor: pointer;
//     width: 100%;
//     text-align: left;
//     padding: 10px 20px !important;
//     font-size: 14px;
//     font-weight: 500;
//     font-family: inherit;
//     display: flex;
//     align-items: center;
//     gap: 12px;
//     transition: all 0.2s ease;
// }

// .btn-logout-dropdown i {
//     width: 20px;
//     color: var(--danger);
//     font-size: 15px;
// }

// .btn-logout-dropdown:hover {
//     background: rgba(184, 50, 50, 0.06) !important;
//     color: var(--danger) !important;
//     padding-left: 24px !important;
// }

// /* ============================================
//    BANNIÈRE PENDING ORDER
// ============================================ */
// .pending-order-banner {
//     display: flex;
//     flex-wrap: wrap;
//     justify-content: center;
//     align-items: center;
//     gap: 8px;
//     padding: 12px 20px;
//     text-align: center;
//     font-size: 14px;
//     line-height: 1.5;
// }

// .pending-order-banner a,
// .pending-order-banner button {
//     font-weight: 600;
//     color: #856404;
// }

// .pending-order-banner form {
//     display: inline;
//     margin: 0;
// }

// /* ============================================
//    SLIDER
// ============================================ */
// .catalog-slider-container {
//     position: relative;
//     width: 100%;
//     overflow: hidden;
//     min-height: 380px;
//     border-radius: 0;
//     margin-bottom: 0;
// }

// .catalog-slider-wrapper {
//     display: flex;
//     transition: transform 0.7s ease-in-out;
//     height: 100%;
// }

// .catalog-slide {
//     min-width: 100%;
//     padding: 4rem 5rem;
//     display: flex;
//     align-items: center;
//     justify-content: space-between;
//     position: relative;
//     min-height: 380px;
// }

// .catalog-slide::before {
//     content: '';
//     position: absolute;
//     top: -50%;
//     right: -10%;
//     width: 400px;
//     height: 400px;
//     background: rgba(255, 255, 255, 0.05);
//     border-radius: 50%;
//     pointer-events: none;
// }

// .catalog-slide::after {
//     content: '';
//     position: absolute;
//     bottom: -30%;
//     left: 20%;
//     width: 300px;
//     height: 300px;
//     background: rgba(255, 255, 255, 0.03);
//     border-radius: 50%;
//     pointer-events: none;
// }

// .catalog-slide-1 { background: linear-gradient(135deg, #055936 0%, #0B7A48 60%, #D79A05 100%); }
// .catalog-slide-2 { background: linear-gradient(135deg, #8F6100 0%, #D79A05 50%, #F2B832 100%); }
// .catalog-slide-3 { background: linear-gradient(135deg, #3d1a0a 0%, #5C3D1E 50%, #0B7A48 100%); }
// .catalog-slide-4 { background: linear-gradient(135deg, #055936 0%, #0B7A48 40%, #F2B832 100%); }

// .catalog-slide-content {
//     position: relative;
//     z-index: 2;
//     color: var(--white);
//     max-width: 55%;
// }

// .catalog-slide-content .eyebrow {
//     color: var(--gold-light);
//     font-size: clamp(12px, 1.2vw, 14px);
//     font-weight: 700;
//     letter-spacing: 0.15em;
//     text-transform: uppercase;
//     margin-bottom: 8px;
// }

// .catalog-slide-content h2 {
//     font-family: 'Playfair Display', Georgia, serif;
//     font-size: clamp(28px, 4.5vw, 52px);
//     line-height: 1.1;
//     margin-bottom: 12px;
// }

// .catalog-slide-content h2 span {
//     color: var(--gold-light);
// }

// .catalog-slide-content p {
//     font-size: clamp(14px, 1.3vw, 17px);
//     line-height: 1.7;
//     opacity: 0.9;
//     max-width: 480px;
//     margin-bottom: 20px;
// }

// .catalog-slide-content .btn {
//     display: inline-block;
//     padding: 12px 28px;
//     border-radius: 6px;
//     font-weight: 800;
//     background: var(--white);
//     color: var(--green-dark);
//     transition: all 0.3s ease;
//     font-size: clamp(13px, 1.1vw, 15px);
// }

// .catalog-slide-content .btn:hover {
//     transform: translateY(-2px);
//     box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
// }

// .catalog-slide-icons {
//     position: relative;
//     z-index: 2;
//     display: flex;
//     gap: 14px;
//     align-items: center;
//     flex-wrap: wrap;
// }

// .catalog-slide-icons .icon-box {
//     width: clamp(60px, 8vw, 80px);
//     height: clamp(60px, 8vw, 80px);
//     background: rgba(255, 255, 255, 0.15);
//     backdrop-filter: blur(8px);
//     border-radius: 16px;
//     display: flex;
//     flex-direction: column;
//     align-items: center;
//     justify-content: center;
//     font-size: clamp(24px, 3vw, 32px);
//     border: 1px solid rgba(255, 255, 255, 0.1);
//     transition: transform 0.3s ease;
// }

// .catalog-slide-icons .icon-box:hover {
//     transform: translateY(-6px) scale(1.05);
// }

// .catalog-slide-icons .icon-box small {
//     font-size: clamp(7px, 0.8vw, 10px);
//     color: rgba(255, 255, 255, 0.8);
//     margin-top: 4px;
//     font-weight: 600;
//     text-transform: uppercase;
// }

// .catalog-slider-dots {
//     position: absolute;
//     bottom: 20px;
//     left: 50%;
//     transform: translateX(-50%);
//     display: flex;
//     gap: 10px;
//     z-index: 10;
// }

// .catalog-slider-dots .dot {
//     width: 12px;
//     height: 12px;
//     border-radius: 50%;
//     background: rgba(255, 255, 255, 0.35);
//     cursor: pointer;
//     transition: all 0.3s ease;
//     border: 2px solid transparent;
// }

// .catalog-slider-dots .dot.active {
//     background: var(--white);
//     border-color: var(--gold-light);
//     transform: scale(1.2);
// }

// .catalog-slider-dots .dot:hover {
//     background: rgba(255, 255, 255, 0.7);
// }

// .catalog-slider-btn {
//     position: absolute;
//     top: 50%;
//     transform: translateY(-50%);
//     background: rgba(255, 255, 255, 0.2);
//     backdrop-filter: blur(8px);
//     color: var(--white);
//     border: none;
//     width: clamp(38px, 4vw, 48px);
//     height: clamp(38px, 4vw, 48px);
//     border-radius: 50%;
//     font-size: clamp(18px, 2vw, 24px);
//     cursor: pointer;
//     z-index: 10;
//     transition: all 0.3s ease;
//     display: flex;
//     align-items: center;
//     justify-content: center;
// }

// .catalog-slider-btn:hover {
//     background: rgba(255, 255, 255, 0.4);
//     transform: translateY(-50%) scale(1.1);
// }

// .catalog-slider-btn.prev { left: clamp(10px, 2vw, 20px); }
// .catalog-slider-btn.next { right: clamp(10px, 2vw, 20px); }

// /* ============================================
//    SECTION RECHERCHE
// ============================================ */
// .search-section {
//     padding: 60px 0 50px;
//     background: var(--cream);
// }

// .search-form {
//     background: var(--white);
//     padding: clamp(20px, 3vw, 40px);
//     border-radius: 16px;
//     box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
//     border: 1px solid var(--line);
// }

// .search-header {
//     margin-bottom: 24px;
// }

// .search-header h2 {
//     font-family: 'Playfair Display', Georgia, serif;
//     color: var(--brown);
//     font-size: clamp(22px, 2.5vw, 28px);
//     font-weight: 700;
// }

// .search-grid {
//     display: grid;
//     grid-template-columns: 1fr 1fr auto;
//     gap: 16px;
//     align-items: end;
// }

// .search-group {
//     display: flex;
//     flex-direction: column;
//     gap: 0;
// }

// .search-group input[type="text"],
// .search-group select {
//     width: 100%;
//     padding: 14px 18px;
//     border: 2px solid var(--line);
//     border-radius: 8px;
//     font-size: clamp(14px, 1.1vw, 15px);
//     color: var(--text);
//     background: var(--cream);
//     transition: all 0.3s ease;
//     font-family: inherit;
// }

// .search-group input[type="text"]::placeholder {
//     color: var(--muted);
//     opacity: 0.7;
// }

// .search-group input[type="text"]:focus,
// .search-group select:focus {
//     outline: none;
//     border-color: var(--green);
//     box-shadow: 0 0 0 4px rgba(11, 122, 72, 0.1);
//     background: var(--white);
// }

// .search-group select {
//     appearance: none;
//     -webkit-appearance: none;
//     cursor: pointer;
//     background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23777064' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
//     background-repeat: no-repeat;
//     background-position: right 16px center;
//     padding-right: 44px;
// }

// .search-group-btn {
//     display: flex;
//     align-items: stretch;
// }

// .btn-search {
//     display: inline-flex;
//     align-items: center;
//     justify-content: center;
//     gap: 10px;
//     padding: 14px 32px;
//     background: var(--green);
//     color: var(--white);
//     border: none;
//     border-radius: 8px;
//     font-size: clamp(14px, 1.1vw, 16px);
//     font-weight: 700;
//     cursor: pointer;
//     transition: all 0.3s ease;
//     font-family: inherit;
//     white-space: nowrap;
//     min-height: 54px;
//     width: 100%;
// }

// .btn-search:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px);
//     box-shadow: 0 8px 24px rgba(11, 122, 72, 0.3);
// }

// .search-results {
//     display: none;
//     margin-top: 20px;
//     padding: 20px 24px;
//     background: var(--white);
//     border-radius: 12px;
//     border: 1px solid var(--line);
//     box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
// }

// /* ============================================
//    TRUST BAND
// ============================================ */
// .trust-band {
//     background: var(--green-dark);
//     color: var(--white);
//     display: grid;
//     grid-template-columns: repeat(4, 1fr);
//     text-align: center;
// }

// .trust-band div {
//     padding: clamp(16px, 2vw, 22px);
//     border-right: 1px solid rgba(255, 255, 255, 0.12);
//     font-weight: 800;
//     font-size: clamp(12px, 1.1vw, 16px);
// }

// .trust-band div:last-child {
//     border-right: none;
// }

// /* ============================================
//    SECTIONS
// ============================================ */
// .section {
//     padding: clamp(40px, 6vw, 78px) 0;
// }

// .section-heading {
//     text-align: center;
//     margin-bottom: 38px;
// }

// .section-heading p {
//     color: var(--green);
//     text-transform: uppercase;
//     font-size: clamp(11px, 1vw, 13px);
//     font-weight: 800;
//     letter-spacing: 0.14em;
// }

// .section-heading h2,
// .split h2 {
//     font-family: 'Playfair Display', Georgia, serif;
//     color: var(--brown);
//     font-size: clamp(26px, 4vw, 44px);
// }

// /* ============================================
//    PRODUITS
// ============================================ */
// .products-grid {
//     display: grid;
//     grid-template-columns: repeat(4, 1fr);
//     gap: clamp(16px, 2vw, 24px);
// }

// .product-card {
//     background: var(--white);
//     border: 1px solid var(--line);
//     border-radius: 8px;
//     overflow: hidden;
//     box-shadow: 0 10px 30px rgba(47, 42, 34, 0.06);
//     transition: transform 0.3s ease, box-shadow 0.3s ease;
// }

// .product-card:hover {
//     transform: translateY(-4px);
//     box-shadow: 0 16px 40px rgba(47, 42, 34, 0.1);
// }

// .product-img {
//     height: clamp(160px, 20vw, 230px);
//     background: radial-gradient(circle at 20% 25%, rgba(250, 246, 235, 0.25), transparent 18%),
//                 radial-gradient(circle at 78% 68%, rgba(215, 154, 5, 0.25), transparent 24%),
//                 linear-gradient(135deg, var(--green-dark), var(--green));
// }

// .visual-mil { background: linear-gradient(135deg, #FAF6EB, #D79A05); }
// .visual-huile { background: linear-gradient(135deg, #FAF6EB, #0B7A48); }
// .visual-mais { background: linear-gradient(135deg, #F2B832, #FAF6EB); }
// .visual-haricots { background: linear-gradient(135deg, #FAF6EB, #5C3D1E); }

// .product-body {
//     padding: clamp(14px, 1.8vw, 20px);
// }

// .product-body h3 {
//     color: var(--brown);
//     font-size: clamp(16px, 1.4vw, 19px);
//     margin-bottom: 8px;
// }

// .product-body p {
//     color: var(--muted);
//     font-size: clamp(12px, 1vw, 14px);
//     line-height: 1.6;
// }

// .product-footer {
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     margin-top: 18px;
// }

// .product-footer strong {
//     color: var(--gold);
//     font-size: clamp(14px, 1.2vw, 17px);
// }

// .add-btn {
//     background: var(--green);
//     color: var(--white);
//     padding: 9px 14px;
//     border-radius: 5px;
//     font-size: clamp(11px, 0.9vw, 13px);
//     font-weight: 800;
//     transition: background 0.3s ease, transform 0.3s ease;
// }

// .add-btn:hover {
//     background: var(--green-dark);
//     transform: scale(1.05);
// }

// .tag {
//     display: inline-block;
//     color: var(--green);
//     font-size: clamp(10px, 0.8vw, 12px);
//     font-weight: 900;
//     text-transform: uppercase;
//     margin-bottom: 10px;
// }

// .tag.promo {
//     color: var(--gold);
// }

// /* ============================================
//    SPLIT
// ============================================ */
// .split {
//     background: var(--white);
// }

// .split-inner {
//     display: grid;
//     grid-template-columns: 1fr 1fr;
//     gap: clamp(30px, 5vw, 54px);
//     align-items: center;
// }

// .lead {
//     margin: 18px 0;
//     color: var(--muted);
//     line-height: 1.8;
//     font-size: clamp(14px, 1.1vw, 16px);
// }

// .check-list {
//     display: grid;
//     gap: 12px;
//     padding-left: 18px;
//     color: var(--brown);
//     font-size: clamp(14px, 1vw, 15px);
// }

// .about-visual {
//     border-radius: 8px;
//     min-height: clamp(250px, 30vw, 390px);
//     background: radial-gradient(circle at 20% 25%, rgba(250, 246, 235, 0.25), transparent 18%),
//                 radial-gradient(circle at 78% 68%, rgba(215, 154, 5, 0.25), transparent 24%),
//                 linear-gradient(135deg, var(--green-dark), var(--green));
// }

// /* ============================================
//    CONTAINER & BTN
// ============================================ */
// .container {
//     width: min(1180px, 92%);
//     margin: auto;
// }

// .btn {
//     display: inline-block;
//     padding: 13px 22px;
//     border-radius: 6px;
//     font-weight: 800;
// }

// /* ============================================
//    FOOTER
// ============================================ */
// .footer {
//     background: var(--green-dark);
//     color: rgba(255, 255, 255, 0.8);
//     padding: 50px 0 0;
//     margin-top: 40px;
// }

// .footer-container {
//     max-width: 1180px;
//     margin: 0 auto;
//     width: 92%;
//     padding: 0 20px;
// }

// .footer-links {
//     display: grid;
//     grid-template-columns: repeat(3, 1fr);
//     gap: clamp(24px, 4vw, 40px);
//     padding: 40px 0;
//     border-bottom: 1px solid rgba(255, 255, 255, 0.08);
// }

// .footer-links h4 {
//     color: var(--gold-light);
//     font-size: clamp(14px, 1.2vw, 16px);
//     font-weight: 700;
//     margin-bottom: 16px;
//     text-transform: uppercase;
//     letter-spacing: 0.05em;
// }

// .footer-links ul {
//     list-style: none;
//     display: flex;
//     flex-direction: column;
//     gap: 10px;
// }

// .footer-links ul li {
//     display: flex;
//     align-items: center;
//     gap: 10px;
//     font-size: clamp(13px, 1vw, 14px);
//     transition: all 0.3s ease;
// }

// .footer-links ul li a {
//     color: rgba(255, 255, 255, 0.7);
//     transition: all 0.3s ease;
// }

// .footer-links ul li a:hover {
//     color: var(--gold-light);
//     padding-left: 4px;
// }

// .footer-links ul li svg {
//     color: var(--gold);
//     flex-shrink: 0;
//     opacity: 0.6;
//     width: clamp(16px, 1.3vw, 18px);
//     height: clamp(16px, 1.3vw, 18px);
// }

// .footer-links ul li:hover svg {
//     opacity: 1;
// }

// .footer-bottom {
//     display: flex;
//     justify-content: center;
//     align-items: center;
//     padding: 20px 0;
//     text-align: center;
//     font-size: 14px;
//     color: rgba(255, 255, 255, 0.5);
// }

// .footer-bottom strong {
//     color: var(--gold-light);
// }

// /* ============================================
//    RESPONSIVE — TABLETTE (<= 992px)
// ============================================ */
// @media (max-width: 992px) {
//     /* === NAVIGATION === */
//     .nav {
//         flex-wrap: wrap;
//         justify-content: space-between;
//         align-items: center;
//         padding: 10px 0;
//         min-height: auto;
//         gap: 10px;
//     }

//     .brand {
//         order: 0;
//         flex: 1 1 auto;
//         min-width: 0;
//     }

//     .hamburger {
//         display: flex;
//         order: 1;
//         margin-left: auto;
//     }

//     .cart-btn {
//         order: 2;
//         padding: 10px 16px;
//         font-size: 13px;
//         margin-left: 8px;
//     }

//     .navbar {
//         order: 3;
//         flex-basis: 100%;
//         display: none;
//         width: 100%;
//         padding: 0;
//         background: transparent;
//         box-shadow: none;
//         border-top: none;
//     }

//     .navbar.active {
//         display: block;
//         padding-top: 12px;
//         border-top: 1px solid var(--line);
//         animation: slideDown 0.3s ease;
//     }

//     @keyframes slideDown {
//         from { opacity: 0; transform: translateY(-10px); }
//         to   { opacity: 1; transform: translateY(0); }
//     }

//     .nav-links {
//         flex-direction: column;
//         align-items: stretch;
//         gap: 0;
//         padding: 0;
//         width: 100%;
//     }

//     .nav-links > li {
//         border-bottom: 1px solid var(--line);
//         width: 100%;
//     }

//     .nav-links > li:last-child {
//         border-bottom: none;
//     }

//     .nav-links > li > a {
//         display: block;
//         padding: 14px 12px;
//         font-size: 16px;
//         text-align: center;
//         white-space: normal;
//     }

//     .nav-links > li > a::after {
//         display: none;
//     }

//     /* Dropdowns en accordéon */
//     .dropdown-menu,
//     .nav-links .dropdown-profile .dropdown-menu {
//         position: static;
//         display: none;
//         width: 100%;
//         min-width: 0;
//         background: rgba(0, 0, 0, 0.03);
//         border: none;
//         box-shadow: none;
//         border-radius: 8px;
//         margin: 4px 0 8px;
//         padding: 4px 0;
//         left: auto;
//         right: auto;
//     }

//     .nav-links li.dropdown.active > .dropdown-menu,
//     .nav-links li.dropdown-profile.active > .dropdown-menu {
//         display: block;
//     }

//     .nav-links li.dropdown:hover > .dropdown-menu,
//     .nav-links li.dropdown-profile:hover > .dropdown-menu {
//         display: none;
//     }

//     .dropdown-menu a,
//     .nav-links .dropdown-profile .dropdown-menu li a {
//         padding: 10px 16px;
//         font-size: 14px;
//         text-align: center;
//         justify-content: center;
//     }

//     .nav-links .dropdown-profile .dropdown-menu li a:hover {
//         padding-left: 16px;
//     }

//     .btn-logout-dropdown {
//         justify-content: center;
//         padding: 10px 16px !important;
//     }

//     .btn-logout-dropdown:hover {
//         padding-left: 16px !important;
//     }

//     /* Boutons connexion/inscription */
//     .btn-connexion,
//     .btn-inscription {
//         display: block;
//         text-align: center;
//         padding: 12px 20px;
//         border-radius: 8px;
//         margin: 6px 12px;
//     }

//     .btn-connexion {
//         background: transparent;
//         border: 2px solid var(--green);
//         color: var(--green);
//     }

//     .btn-connexion:hover {
//         background: var(--green);
//         color: var(--white);
//     }

//     .btn-inscription {
//         background: var(--green);
//         color: var(--white);
//         border: 2px solid var(--green);
//     }

//     .btn-inscription:hover {
//         background: var(--green-dark);
//         border-color: var(--green-dark);
//     }

//     /* === SLIDER === */
//     .catalog-slide {
//         flex-direction: column;
//         text-align: center;
//         padding: 2.5rem 2rem;
//         min-height: auto;
//         justify-content: center;
//     }

//     .catalog-slide-content {
//         max-width: 100%;
//         margin-bottom: 20px;
//     }

//     .catalog-slide-content p {
//         max-width: 100%;
//         margin-left: auto;
//         margin-right: auto;
//     }

//     .catalog-slide-icons {
//         justify-content: center;
//     }

//     .catalog-slider-container {
//         min-height: auto;
//     }

//     /* === RECHERCHE === */
//     .search-grid {
//         grid-template-columns: 1fr 1fr;
//     }

//     .search-group-btn {
//         grid-column: 1 / -1;
//     }

//     /* === PRODUITS === */
//     .products-grid {
//         grid-template-columns: repeat(2, 1fr);
//     }

//     /* === TRUST BAND === */
//     .trust-band {
//         grid-template-columns: repeat(2, 1fr);
//     }

//     .trust-band div {
//         border-right: none;
//         border-bottom: 1px solid rgba(255, 255, 255, 0.12);
//     }

//     .trust-band div:nth-last-child(-n+2) {
//         border-bottom: none;
//     }

//     /* === SPLIT === */
//     .split-inner {
//         grid-template-columns: 1fr;
//         gap: 30px;
//     }

//     /* === FOOTER === */
//     .footer-links {
//         grid-template-columns: repeat(2, 1fr);
//         gap: 30px;
//     }
// }

// /* ============================================
//    RESPONSIVE — MOBILE (<= 600px)
// ============================================ */
// @media (max-width: 600px) {
//     /* === NAVIGATION === */
//     .nav {
//         padding: 8px 0;
//     }

//     .brand-icon {
//         width: 44px;
//         height: 44px;
//     }

//     .brand-text strong,
//     .brand-text em {
//         font-size: 15px;
//     }

//     .hamburger {
//         padding: 4px;
//     }

//     .hamburger .bar {
//         width: 24px;
//         height: 2.5px;
//     }

//     .cart-btn {
//         padding: 8px 12px;
//         font-size: 12px;
//     }

//     .cart-badge,
//     .cart-btn span {
//         padding: 1px 5px;
//         margin-left: 4px;
//     }

//     .navbar.active {
//         padding: 12px 0;
//     }

//     .nav-links > li > a {
//         padding: 12px 8px;
//         font-size: 15px;
//     }

//     .dropdown-menu a {
//         padding: 8px 16px;
//         font-size: 13px;
//     }

//     .btn-connexion,
//     .btn-inscription {
//         padding: 10px 16px;
//         font-size: 14px;
//     }

//     .badge-admin,
//     .badge-user {
//         font-size: 9px;
//         padding: 1px 8px;
//     }

//     /* === SLIDER === */
//     .catalog-slide {
//         padding: 1.8rem 1rem;
//     }

//     .catalog-slide-content h2 {
//         font-size: 24px;
//     }

//     .catalog-slide-content p {
//         font-size: 14px;
//     }

//     .catalog-slide-icons .icon-box {
//         width: 56px;
//         height: 56px;
//         font-size: 22px;
//     }

//     .catalog-slide-icons .icon-box small {
//         font-size: 7px;
//     }

//     .catalog-slider-btn {
//         width: 32px;
//         height: 32px;
//         font-size: 16px;
//     }

//     .catalog-slider-btn.prev { left: 6px; }
//     .catalog-slider-btn.next { right: 6px; }

//     .catalog-slider-dots .dot {
//         width: 10px;
//         height: 10px;
//     }

//     /* === RECHERCHE === */
//     .search-section {
//         padding: 40px 0 30px;
//     }

//     .search-grid {
//         grid-template-columns: 1fr;
//         gap: 12px;
//     }

//     .search-group-btn {
//         grid-column: 1;
//     }

//     .search-form {
//         padding: 16px;
//     }

//     .search-header h2 {
//         font-size: 22px;
//     }

//     .btn-search {
//         padding: 14px 20px;
//         font-size: 15px;
//     }

//     .search-group input[type="text"],
//     .search-group select {
//         padding: 12px 16px;
//         font-size: 14px;
//     }

//     /* === PRODUITS === */
//     .products-grid {
//         grid-template-columns: 1fr;
//     }

//     .product-img {
//         height: 180px;
//     }

//     /* === TRUST BAND === */
//     .trust-band {
//         grid-template-columns: 1fr;
//     }

//     .trust-band div {
//         border-bottom: 1px solid rgba(255, 255, 255, 0.12);
//         border-right: none;
//     }

//     .trust-band div:last-child {
//         border-bottom: none;
//     }

//     /* === SECTIONS === */
//     .section {
//         padding: 30px 0;
//     }

//     .section-heading h2,
//     .split h2 {
//         font-size: 26px;
//     }

//     /* === SPLIT === */
//     .split-inner {
//         gap: 24px;
//     }

//     .about-visual {
//         min-height: 200px;
//     }

//     .check-list {
//         padding-left: 10px;
//     }

//     /* === FOOTER === */
//     .footer {
//         padding: 30px 0 0;
//     }

//     .footer-container {
//         padding: 0 16px;
//     }

//     .footer-links {
//         grid-template-columns: 1fr;
//         gap: 24px;
//         padding: 24px 0;
//     }

//     .footer-links ul li {
//         font-size: 14px;
//     }

//     .footer-bottom {
//         flex-direction: column;
//         gap: 12px;
//         padding: 16px 0;
//         font-size: 13px;
//     }

//     /* === PENDING ORDER BANNER === */
//     .pending-order-banner {
//         font-size: 13px;
//         padding: 10px 12px;
//     }

//     .pending-order-banner form,
//     .pending-order-banner button {
//         width: 100%;
//     }
// }

// /* ============================================
//    RESPONSIVE — TRÈS PETIT (<= 380px)
// ============================================ */
// @media (max-width: 380px) {
//     .brand-text strong,
//     .brand-text em {
//         font-size: 13px;
//     }

//     .cart-btn {
//         font-size: 11px;
//         padding: 6px 10px;
//     }

//     .nav-links > li > a {
//         font-size: 14px;
//         padding: 10px 6px;
//     }
// }
// /* ============================================
//    PAGE CONTACT
// ============================================ */

// /* --- Bannière Hero --- */
// .contact-hero {
//     background: linear-gradient(135deg, #055936 0%, #0B7A48 60%, #D79A05 100%);
//     padding: 80px 40px;
//     border-radius: 16px;
//     color: var(--white);
//     margin-bottom: 50px;
//     text-align: center;
//     position: relative;
//     overflow: hidden;
//     width: min(1180px, 92%);
//     margin-left: auto;
//     margin-right: auto;
// }

// .contact-hero-bg-1,
// .contact-hero-bg-2 {
//     position: absolute;
//     border-radius: 50%;
//     pointer-events: none;
// }

// .contact-hero-bg-1 {
//     top: -30%;
//     right: -5%;
//     width: 400px;
//     height: 400px;
//     background: rgba(255, 255, 255, 0.05);
// }

// .contact-hero-bg-2 {
//     bottom: -20%;
//     left: 10%;
//     width: 300px;
//     height: 300px;
//     background: rgba(255, 255, 255, 0.03);
// }

// .contact-hero-content {
//     position: relative;
//     z-index: 2;
// }

// .contact-hero-icon {
//     font-size: 64px;
//     margin-bottom: 15px;
//     line-height: 1;
// }

// .contact-hero h1 {
//     font-family: 'Playfair Display', Georgia, serif;
//     font-size: clamp(28px, 4vw, 48px);
//     margin-bottom: 15px;
//     line-height: 1.2;
// }

// .contact-hero p {
//     font-size: clamp(15px, 1.3vw, 20px);
//     max-width: 600px;
//     margin: 0 auto;
//     opacity: 0.9;
//     line-height: 1.6;
// }

// /* --- Grille principale --- */
// .contact-main {
//     padding: 0 0 40px;
// }

// .contact-grid {
//     display: grid;
//     grid-template-columns: 1fr 1fr;
//     gap: 50px;
//     align-items: start;
// }

// /* --- Carte formulaire --- */
// .contact-form-card {
//     background: var(--white);
//     padding: 40px;
//     border-radius: 16px;
//     box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
//     border: 1px solid var(--line);
// }

// .contact-form-card h3,
// .contact-info h3 {
//     font-family: 'Playfair Display', Georgia, serif;
//     color: var(--brown);
//     font-size: clamp(20px, 2vw, 24px);
//     margin-bottom: 25px;
//     line-height: 1.3;
// }

// .text-green { color: var(--green); }
// .text-gold  { color: var(--gold); }

// /* --- Form groups --- */
// .form-group {
//     margin-bottom: 20px;
// }

// .form-group label {
//     display: block;
//     font-weight: 600;
//     color: var(--brown);
//     margin-bottom: 8px;
//     font-size: 14px;
// }

// .form-group input,
// .form-group select,
// .form-group textarea {
//     width: 100%;
//     padding: 14px 18px;
//     border: 2px solid var(--line);
//     border-radius: 8px;
//     font-size: 15px;
//     font-family: inherit;
//     color: var(--text);
//     background: var(--white);
//     transition: border-color 0.3s, box-shadow 0.3s;
// }

// .form-group input:focus,
// .form-group select:focus,
// .form-group textarea:focus {
//     outline: none;
//     border-color: var(--green);
//     box-shadow: 0 0 0 4px rgba(11, 122, 72, 0.1);
// }

// .form-group select {
//     appearance: none;
//     -webkit-appearance: none;
//     cursor: pointer;
//     background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23777064' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
//     background-repeat: no-repeat;
//     background-position: right 16px center;
//     padding-right: 44px;
// }

// .form-group textarea {
//     resize: vertical;
//     min-height: 120px;
// }

// /* --- Bouton submit --- */
// .btn-submit {
//     width: 100%;
//     padding: 16px;
//     background: var(--green);
//     color: var(--white);
//     border: none;
//     border-radius: 8px;
//     font-size: 16px;
//     font-weight: 700;
//     font-family: inherit;
//     cursor: pointer;
//     transition: background 0.3s, transform 0.3s;
//     display: inline-flex;
//     align-items: center;
//     justify-content: center;
//     gap: 10px;
// }

// .btn-submit:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px);
// }

// /* --- Colonne infos --- */
// .contact-info-list {
//     display: flex;
//     flex-direction: column;
//     gap: 20px;
// }

// .info-card {
//     display: flex;
//     align-items: flex-start;
//     gap: 20px;
//     background: var(--white);
//     padding: 20px;
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
//     border-left: 4px solid var(--green);
// }

// .info-card.border-green { border-left-color: var(--green); }
// .info-card.border-gold  { border-left-color: var(--gold); }

// .info-icon {
//     font-size: 28px;
//     line-height: 1;
//     flex-shrink: 0;
// }

// .info-card h4 {
//     color: var(--brown);
//     font-size: 16px;
//     margin-bottom: 5px;
// }

// .info-card p {
//     color: var(--muted);
//     font-size: 15px;
//     line-height: 1.6;
//     word-break: break-word;
// }

// /* --- Réseaux sociaux --- */
// .social-card {
//     margin-top: 30px;
//     background: var(--white);
//     padding: 25px;
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
//     text-align: center;
// }

// .social-card h4 {
//     color: var(--brown);
//     font-size: 16px;
//     margin-bottom: 15px;
// }

// .social-links {
//     display: flex;
//     gap: 15px;
//     justify-content: center;
//     flex-wrap: wrap;
// }

// .social-btn {
//     width: 48px;
//     height: 48px;
//     border-radius: 50%;
//     color: var(--white);
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     font-size: 22px;
//     transition: transform 0.3s;
//     flex-shrink: 0;
// }

// .social-btn:hover {
//     transform: translateY(-3px) scale(1.05);
// }

// .social-btn.whatsapp  { background: #25D366; }
// .social-btn.facebook  { background: #4267B2; }
// .social-btn.instagram { background: #E1306C; }
// .social-btn.twitter   { background: #1DA1F2; }

// /* ============================================
//    MAP SECTION
// ============================================ */
// .map-section {
//     background: var(--white);
//     padding: 60px 0;
// }

// .map-heading {
//     text-align: center;
//     margin-bottom: 30px;
// }

// .map-heading .eyebrow {
//     color: var(--green);
//     text-transform: uppercase;
//     font-weight: 800;
//     letter-spacing: 0.14em;
//     font-size: 13px;
//     margin-bottom: 8px;
// }

// .map-heading h2 {
//     font-family: 'Playfair Display', Georgia, serif;
//     color: var(--brown);
//     font-size: clamp(24px, 3vw, 36px);
//     line-height: 1.2;
// }

// .map-wrapper {
//     position: relative;
//     border-radius: 16px;
//     overflow: hidden;
//     box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
//     border: 1px solid var(--line);
// }

// .map-frame {
//     width: 100%;
//     height: 450px;
//     background: #e8ecf1;
//     position: relative;
// }

// .map-frame iframe {
//     width: 100%;
//     height: 100%;
//     border: 0;
//     display: block;
// }

// .map-directions-btn {
//     position: absolute;
//     bottom: 20px;
//     right: 20px;
//     background: #055936;
//     color: var(--white);
//     padding: 12px 24px;
//     border-radius: 8px;
//     text-decoration: none;
//     font-weight: 600;
//     font-size: 14px;
//     box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
//     transition: all 0.3s;
//     z-index: 10;
//     display: inline-flex;
//     align-items: center;
//     gap: 8px;
// }

// .map-directions-btn:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px) scale(1.03);
// }

// .map-address {
//     text-align: center;
//     margin-top: 15px;
//     color: var(--muted);
//     font-size: 14px;
//     line-height: 1.6;
// }

// .map-address strong {
//     color: var(--brown);
// }

// /* ============================================
//    RESPONSIVE — PAGE CONTACT
// ============================================ */
// @media (max-width: 992px) {
//     .contact-hero {
//         padding: 50px 24px;
//         margin-bottom: 30px;
//     }

//     .contact-hero-icon {
//         font-size: 48px;
//     }

//     .contact-grid {
//         grid-template-columns: 1fr;
//         gap: 30px;
//     }

//     .contact-form-card {
//         padding: 28px;
//     }

//     .map-frame {
//         height: 380px;
//     }

//     .map-section {
//         padding: 40px 0;
//     }
// }

// @media (max-width: 600px) {
//     .contact-hero {
//         padding: 40px 16px;
//         border-radius: 12px;
//         margin-bottom: 24px;
//     }

//     .contact-hero-icon {
//         font-size: 40px;
//         margin-bottom: 10px;
//     }

//     .contact-hero h1 {
//         font-size: 26px;
//         margin-bottom: 10px;
//     }

//     .contact-hero p {
//         font-size: 14px;
//     }

//     .contact-form-card {
//         padding: 20px;
//         border-radius: 12px;
//     }

//     .contact-form-card h3,
//     .contact-info h3 {
//         font-size: 20px;
//         margin-bottom: 20px;
//     }

//     .form-group {
//         margin-bottom: 16px;
//     }

//     .form-group input,
//     .form-group select,
//     .form-group textarea {
//         padding: 12px 14px;
//         font-size: 14px;
//     }

//     .btn-submit {
//         padding: 14px;
//         font-size: 15px;
//     }

//     .info-card {
//         padding: 16px;
//         gap: 14px;
//     }

//     .info-icon {
//         font-size: 24px;
//     }

//     .info-card h4 {
//         font-size: 15px;
//     }

//     .info-card p {
//         font-size: 14px;
//     }

//     .social-card {
//         padding: 20px;
//         margin-top: 20px;
//     }

//     .social-btn {
//         width: 42px;
//         height: 42px;
//         font-size: 20px;
//     }

//     /* MAP */
//     .map-section {
//         padding: 30px 0;
//     }

//     .map-frame {
//         height: 280px;
//     }

//     .map-directions-btn {
//         bottom: 12px;
//         right: 12px;
//         padding: 10px 16px;
//         font-size: 12px;
//     }

//     .map-address {
//         font-size: 13px;
//     }
// }

// @media (max-width: 380px) {
//     .contact-hero h1 {
//         font-size: 22px;
//     }

//     .contact-hero p {
//         font-size: 13px;
//     }

//     .social-btn {
//         width: 38px;
//         height: 38px;
//         font-size: 18px;
//     }

//     .map-directions-btn {
//         padding: 8px 12px;
//         font-size: 11px;
//     }
// }
// /* ============================================
//    PAGE À PROPOS
// ============================================ */

// /* --- Bannière Hero (réutilise le style Contact) --- */
// .about-hero {
//     background: linear-gradient(135deg, #055936 0%, #0B7A48 60%, #D79A05 100%);
//     padding: 80px 40px;
//     border-radius: 16px;
//     color: var(--white);
//     margin-bottom: 50px;
//     text-align: center;
//     position: relative;
//     overflow: hidden;
//     width: min(1180px, 92%);
//     margin-left: auto;
//     margin-right: auto;
// }

// .about-hero-bg-1,
// .about-hero-bg-2 {
//     position: absolute;
//     border-radius: 50%;
//     pointer-events: none;
// }

// .about-hero-bg-1 {
//     top: -30%;
//     right: -5%;
//     width: 400px;
//     height: 400px;
//     background: rgba(255, 255, 255, 0.05);
// }

// .about-hero-bg-2 {
//     bottom: -20%;
//     left: 10%;
//     width: 300px;
//     height: 300px;
//     background: rgba(255, 255, 255, 0.03);
// }

// .about-hero-content {
//     position: relative;
//     z-index: 2;
// }

// .about-hero-icon {
//     font-size: 64px;
//     margin-bottom: 15px;
//     line-height: 1;
// }

// .about-hero h1 {
//     font-family: 'Playfair Display', Georgia, serif;
//     font-size: clamp(28px, 4vw, 48px);
//     margin-bottom: 15px;
//     line-height: 1.2;
// }

// .about-hero p {
//     font-size: clamp(15px, 1.3vw, 20px);
//     max-width: 600px;
//     margin: 0 auto;
//     opacity: 0.9;
//     line-height: 1.6;
// }

// /* --- Eyebrow (réutilisable) --- */
// .eyebrow {
//     color: var(--green);
//     text-transform: uppercase;
//     font-weight: 800;
//     letter-spacing: 0.14em;
//     font-size: 13px;
//     margin-bottom: 8px;
// }

// /* ============================================
//    HISTOIRE
// ============================================ */
// .about-history {
//     padding: 0 0 40px;
// }

// .about-history-grid {
//     display: grid;
//     grid-template-columns: 1fr 1fr;
//     gap: 50px;
//     align-items: center;
// }

// .about-history-text h2 {
//     font-family: 'Playfair Display', Georgia, serif;
//     color: var(--brown);
//     font-size: clamp(26px, 3vw, 36px);
//     margin: 15px 0 20px;
//     line-height: 1.25;
// }

// .about-paragraph {
//     color: var(--muted);
//     line-height: 1.8;
//     font-size: clamp(14px, 1.1vw, 16px);
//     margin-bottom: 15px;
// }

// /* Statistiques */
// .about-stats {
//     display: flex;
//     gap: 30px;
//     margin-top: 30px;
//     flex-wrap: wrap;
// }

// .stat-item {
//     flex: 1 1 auto;
//     min-width: 90px;
// }

// .stat-value {
//     font-size: clamp(24px, 3vw, 32px);
//     color: var(--green);
//     font-weight: 900;
//     line-height: 1.2;
// }

// .stat-label {
//     color: var(--muted);
//     font-size: 14px;
//     margin-top: 4px;
// }

// /* Visuel histoire */
// .about-history-visual {
//     background: linear-gradient(135deg, var(--green-dark), var(--green));
//     border-radius: 16px;
//     min-height: 350px;
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     color: var(--white);
//     font-size: 80px;
//     box-shadow: 0 8px 30px rgba(11, 122, 72, 0.2);
//     line-height: 1;
// }

// /* ============================================
//    VALEURS
// ============================================ */
// .about-values {
//     background: var(--white);
//     padding: 60px 0;
// }

// .values-grid {
//     display: grid;
//     grid-template-columns: repeat(4, 1fr);
//     gap: 30px;
// }

// .value-card {
//     background: var(--cream);
//     padding: 35px 25px;
//     border-radius: 12px;
//     text-align: center;
//     transition: transform 0.3s, box-shadow 0.3s;
//     border: 1px solid var(--line);
// }

// .value-card:hover {
//     transform: translateY(-6px);
//     box-shadow: 0 12px 30px rgba(11, 122, 72, 0.12);
// }

// .value-icon {
//     font-size: 48px;
//     margin-bottom: 15px;
//     line-height: 1;
// }

// .value-card h3 {
//     color: var(--brown);
//     font-size: 20px;
//     margin-bottom: 10px;
// }

// .value-card p {
//     color: var(--muted);
//     font-size: 14px;
//     line-height: 1.6;
// }

// /* ============================================
//    ÉQUIPE
// ============================================ */
// .about-team {
//     padding: 60px 0;
// }

// .team-grid {
//     display: grid;
//     grid-template-columns: repeat(4, 1fr);
//     gap: 30px;
// }

// .team-member {
//     text-align: center;
// }

// .team-avatar {
//     width: 120px;
//     height: 120px;
//     border-radius: 50%;
//     background: linear-gradient(135deg, var(--green), var(--gold));
//     margin: 0 auto 15px;
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     font-size: 48px;
//     color: var(--white);
//     line-height: 1;
//     box-shadow: 0 8px 20px rgba(11, 122, 72, 0.2);
//     transition: transform 0.3s;
// }

// .team-member:hover .team-avatar {
//     transform: translateY(-4px) scale(1.05);
// }

// .team-member h4 {
//     color: var(--brown);
//     font-size: 18px;
//     margin-bottom: 4px;
// }

// .team-member p {
//     color: var(--muted);
//     font-size: 14px;
// }

// /* ============================================
//    RESPONSIVE — PAGE À PROPOS
// ============================================ */

// /* --- Tablette --- */
// @media (max-width: 992px) {
//     .about-hero {
//         padding: 50px 24px;
//         margin-bottom: 30px;
//     }

//     .about-hero-icon {
//         font-size: 48px;
//     }

//     .about-history-grid {
//         grid-template-columns: 1fr;
//         gap: 30px;
//     }

//     .about-history-visual {
//         min-height: 250px;
//         font-size: 64px;
//     }

//     .about-values {
//         padding: 40px 0;
//     }

//     .values-grid {
//         grid-template-columns: repeat(2, 1fr);
//         gap: 24px;
//     }

//     .about-team {
//         padding: 40px 0;
//     }

//     .team-grid {
//         grid-template-columns: repeat(2, 1fr);
//         gap: 24px;
//     }
// }

// /* --- Mobile --- */
// @media (max-width: 600px) {
//     .about-hero {
//         padding: 40px 16px;
//         border-radius: 12px;
//         margin-bottom: 24px;
//     }

//     .about-hero-icon {
//         font-size: 40px;
//         margin-bottom: 10px;
//     }

//     .about-hero h1 {
//         font-size: 26px;
//         margin-bottom: 10px;
//     }

//     .about-hero p {
//         font-size: 14px;
//     }

//     .about-history {
//         padding: 0 0 30px;
//     }

//     .about-history-text h2 {
//         font-size: 24px;
//         margin: 12px 0 16px;
//     }

//     .about-paragraph {
//         font-size: 15px;
//         line-height: 1.7;
//     }

//     .about-stats {
//         gap: 20px;
//         margin-top: 24px;
//         justify-content: space-between;
//     }

//     .stat-item {
//         flex: 1 1 calc(50% - 10px);
//         min-width: 0;
//     }

//     .stat-value {
//         font-size: 24px;
//     }

//     .stat-label {
//         font-size: 13px;
//     }

//     .about-history-visual {
//         min-height: 200px;
//         font-size: 56px;
//         border-radius: 12px;
//     }

//     /* Valeurs */
//     .about-values {
//         padding: 30px 0;
//     }

//     .values-grid {
//         grid-template-columns: 1fr;
//         gap: 16px;
//     }

//     .value-card {
//         padding: 24px 20px;
//     }

//     .value-icon {
//         font-size: 40px;
//         margin-bottom: 12px;
//     }

//     .value-card h3 {
//         font-size: 18px;
//     }

//     /* Équipe */
//     .about-team {
//         padding: 30px 0;
//     }

//     .team-grid {
//         grid-template-columns: 1fr 1fr;
//         gap: 20px;
//     }

//     .team-avatar {
//         width: 90px;
//         height: 90px;
//         font-size: 38px;
//     }

//     .team-member h4 {
//         font-size: 15px;
//     }

//     .team-member p {
//         font-size: 13px;
//     }
// }

// /* --- Très petit écran --- */
// @media (max-width: 380px) {
//     .about-hero h1 {
//         font-size: 22px;
//     }

//     .about-hero p {
//         font-size: 13px;
//     }

//     .about-history-text h2 {
//         font-size: 22px;
//     }

//     .stat-item {
//         flex: 1 1 100%;
//     }

//     .team-grid {
//         grid-template-columns: 1fr;
//     }

//     .team-avatar {
//         width: 80px;
//         height: 80px;
//         font-size: 32px;
//     }
// }
// /* ============================================
//    PAGE CATALOGUE
// ============================================ */

// /* --- En-tête --- */
// .catalogue-header {
//     background: linear-gradient(135deg, var(--green-dark) 0%, var(--green) 100%);
//     padding: 60px 20px;
//     text-align: center;
//     color: var(--white);
//     margin-bottom: 40px;
// }

// .catalogue-header h1 {
//     font-size: clamp(28px, 4vw, 42px);
//     margin-bottom: 15px;
//     line-height: 1.2;
// }

// .catalogue-subtitle {
//     font-size: clamp(15px, 1.3vw, 18px);
//     opacity: 0.9;
//     max-width: 600px;
//     margin: 0 auto;
//     line-height: 1.6;
// }

// .catalogue-stats {
//     display: flex;
//     justify-content: center;
//     gap: clamp(20px, 5vw, 50px);
//     margin-top: 30px;
//     flex-wrap: wrap;
// }

// .catalogue-stats .stat {
//     text-align: center;
//     min-width: 80px;
// }

// .catalogue-stats .stat-value {
//     font-size: clamp(24px, 3vw, 32px);
//     font-weight: 700;
//     display: block;
//     line-height: 1.2;
// }

// .catalogue-stats .stat-label {
//     font-size: clamp(12px, 1vw, 14px);
//     opacity: 0.85;
// }

// /* --- Wrapper --- */
// .catalogue-wrapper {
//     max-width: 1400px;
//     margin: 0 auto;
//     padding: 0 20px 60px;
// }

// /* ============================================
//    BARRE DE FILTRES
// ============================================ */
// .catalogue-filters {
//     background: var(--white);
//     padding: 25px;
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
//     margin-bottom: 30px;
// }

// .filters-grid {
//     display: grid;
//     grid-template-columns: 1fr 1fr 1fr auto;
//     gap: 15px;
//     align-items: end;
// }

// .filter-field {
//     display: flex;
//     flex-direction: column;
// }

// .filter-field label {
//     display: block;
//     font-size: 14px;
//     font-weight: 600;
//     margin-bottom: 5px;
//     color: var(--text);
// }

// .filter-field input,
// .filter-field select {
//     width: 100%;
//     padding: 10px 15px;
//     border: 2px solid #e0e0e0;
//     border-radius: 8px;
//     font-size: 14px;
//     font-family: inherit;
//     color: var(--text);
//     background: var(--white);
//     transition: border-color 0.3s, box-shadow 0.3s;
// }

// .filter-field input:focus,
// .filter-field select:focus {
//     outline: none;
//     border-color: var(--green);
//     box-shadow: 0 0 0 3px rgba(11, 122, 72, 0.1);
// }

// .filter-field select {
//     appearance: none;
//     -webkit-appearance: none;
//     cursor: pointer;
//     background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23777064' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
//     background-repeat: no-repeat;
//     background-position: right 14px center;
//     padding-right: 38px;
// }

// .btn-apply {
//     background: var(--green);
//     color: var(--white);
//     padding: 11px 30px;
//     border: none;
//     border-radius: 8px;
//     font-weight: 600;
//     font-family: inherit;
//     cursor: pointer;
//     width: 100%;
//     transition: background 0.3s, transform 0.3s;
//     white-space: nowrap;
// }

// .btn-apply:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px);
// }

// /* Filtres supplémentaires */
// .filters-extra {
//     display: flex;
//     gap: 20px;
//     margin-top: 15px;
//     flex-wrap: wrap;
//     align-items: center;
// }

// .filter-inline {
//     display: flex;
//     gap: 10px;
//     align-items: center;
//     flex-wrap: wrap;
// }

// .filter-inline label {
//     font-size: 14px;
//     font-weight: 500;
//     white-space: nowrap;
// }

// .filter-inline select {
//     padding: 8px 30px 8px 12px;
//     border: 2px solid #e0e0e0;
//     border-radius: 6px;
//     font-size: 13px;
//     font-family: inherit;
//     background: var(--white);
//     appearance: none;
//     -webkit-appearance: none;
//     cursor: pointer;
//     background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23777064' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
//     background-repeat: no-repeat;
//     background-position: right 10px center;
// }

// .input-mini {
//     width: 80px;
//     padding: 8px 10px;
//     border: 2px solid #e0e0e0;
//     border-radius: 6px;
//     font-size: 13px;
//     font-family: inherit;
// }

// .separator {
//     color: var(--muted);
// }

// .btn-reset {
//     color: var(--danger);
//     font-size: 14px;
//     text-decoration: none;
//     font-weight: 500;
//     white-space: nowrap;
//     transition: color 0.3s;
// }

// .btn-reset:hover {
//     text-decoration: underline;
// }

// /* ============================================
//    LAYOUT (Sidebar + Main)
// ============================================ */
// .catalogue-layout {
//     display: grid;
//     grid-template-columns: 250px 1fr;
//     gap: 30px;
//     align-items: start;
// }

// /* --- SIDEBAR --- */
// .catalogue-sidebar {
//     display: flex;
//     flex-direction: column;
//     gap: 20px;
//     position: sticky;
//     top: 100px;
// }

// .sidebar-card {
//     background: var(--white);
//     border-radius: 12px;
//     padding: 20px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
// }

// .sidebar-card h3 {
//     font-size: 16px;
//     margin-bottom: 15px;
//     color: var(--text);
// }

// .sidebar-list {
//     list-style: none;
//     padding: 0;
// }

// .sidebar-list li {
//     margin-bottom: 8px;
// }

// .sidebar-list li a {
//     color: var(--text);
//     text-decoration: none;
//     font-size: 14px;
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     padding: 6px 0;
//     transition: color 0.2s, padding-left 0.2s;
//     gap: 8px;
// }

// .sidebar-list li a:hover {
//     color: var(--green);
//     padding-left: 4px;
// }

// .sidebar-list li a.active {
//     font-weight: 700;
//     color: var(--green);
// }

// .sidebar-list .count {
//     color: var(--muted);
//     font-weight: 400;
//     font-size: 13px;
// }

// /* Nouveautés */
// .recent-item {
//     display: flex;
//     gap: 10px;
//     margin-bottom: 12px;
//     padding-bottom: 12px;
//     border-bottom: 1px solid #f0f0f0;
// }

// .recent-item:last-child {
//     border-bottom: none;
//     margin-bottom: 0;
//     padding-bottom: 0;
// }

// .recent-item img {
//     width: 50px;
//     height: 50px;
//     object-fit: cover;
//     border-radius: 6px;
//     flex-shrink: 0;
// }

// .recent-item a {
//     text-decoration: none;
//     color: var(--text);
//     font-size: 13px;
//     font-weight: 500;
//     display: block;
//     line-height: 1.3;
//     transition: color 0.2s;
// }

// .recent-item a:hover {
//     color: var(--green);
// }

// .recent-item p {
//     font-size: 12px;
//     color: var(--muted);
//     margin: 3px 0 0;
// }

// .empty-text {
//     color: var(--muted);
//     font-size: 13px;
//     text-align: center;
// }

// /* --- MAIN --- */
// .catalogue-main {
//     min-width: 0; /* important en grid */
// }

// .results-header {
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     margin-bottom: 20px;
// }

// .results-header p {
//     color: var(--muted);
//     font-size: 14px;
// }

// /* --- Grille produits --- */
// .catalogue-products-grid {
//     display: grid;
//     grid-template-columns: repeat(3, 1fr);
//     gap: 25px;
// }

// .catalogue-product-card {
//     background: var(--white);
//     border-radius: 12px;
//     overflow: hidden;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
//     transition: transform 0.3s, box-shadow 0.3s;
//     display: flex;
//     flex-direction: column;
// }

// .catalogue-product-card:hover {
//     transform: translateY(-4px);
//     box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
// }

// .product-link {
//     text-decoration: none;
//     color: inherit;
//     display: block;
//     flex: 1;
// }

// .product-image {
//     height: 200px;
//     background-size: cover;
//     background-position: center;
//     background-color: #f0f0f0;
//     position: relative;
// }

// .badge {
//     position: absolute;
//     padding: 4px 12px;
//     border-radius: 20px;
//     font-size: 11px;
//     font-weight: 600;
//     color: var(--white);
//     white-space: nowrap;
// }

// .badge-promo {
//     top: 10px;
//     right: 10px;
//     background: #e74c3c;
// }

// .badge-stock,
// .badge-limited,
// .badge-out {
//     bottom: 10px;
//     right: 10px;
// }

// .badge-stock   { background: rgba(0, 0, 0, 0.7); }
// .badge-limited { background: rgba(255, 152, 0, 0.9); }
// .badge-out     { background: rgba(244, 67, 54, 0.9); }

// .product-info {
//     padding: 15px 15px 0;
// }

// .product-category {
//     font-size: 12px;
//     color: var(--muted);
//     margin-bottom: 5px;
// }

// .product-info h3 {
//     font-size: 16px;
//     margin-bottom: 5px;
//     color: var(--text);
//     line-height: 1.3;
// }

// .product-desc {
//     font-size: 14px;
//     color: var(--muted);
//     margin-bottom: 10px;
//     display: -webkit-box;
//     -webkit-line-clamp: 2;
//     -webkit-box-orient: vertical;
//     overflow: hidden;
//     height: 40px;
//     line-height: 1.4;
// }

// .product-footer {
//     padding: 0 15px 15px;
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     gap: 10px;
//     flex-wrap: wrap;
// }

// .product-price {
//     font-size: 18px;
//     font-weight: 700;
//     color: var(--green-dark);
//     white-space: nowrap;
// }

// .unavailable {
//     color: var(--muted);
//     font-size: 13px;
// }

// /* --- Pagination --- */
// .catalogue-pagination {
//     margin-top: 40px;
//     display: flex;
//     justify-content: center;
// }

// /* --- Empty state --- */
// .empty-state {
//     text-align: center;
//     padding: 60px 20px;
//     background: #f5f5f5;
//     border-radius: 12px;
// }

// .empty-icon {
//     font-size: 64px;
//     margin-bottom: 20px;
//     line-height: 1;
// }

// .empty-state h3 {
//     color: var(--text);
//     font-size: clamp(20px, 2.5vw, 24px);
//     margin-bottom: 10px;
// }

// .empty-state p {
//     color: var(--muted);
//     max-width: 400px;
//     margin: 0 auto 20px;
//     line-height: 1.6;
// }

// .btn-primary {
//     background: var(--green);
//     color: var(--white);
//     padding: 12px 30px;
//     border-radius: 50px;
//     text-decoration: none;
//     display: inline-block;
//     font-weight: 600;
//     transition: background 0.3s, transform 0.3s;
// }

// .btn-primary:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px);
// }

// /* ============================================
//    RESPONSIVE — PAGE CATALOGUE
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .catalogue-header {
//         padding: 40px 16px;
//         margin-bottom: 24px;
//     }

//     .catalogue-wrapper {
//         padding: 0 16px 40px;
//     }

//     .catalogue-filters {
//         padding: 20px;
//     }

//     .filters-grid {
//         grid-template-columns: 1fr 1fr;
//         gap: 12px;
//     }

//     .filter-submit {
//         grid-column: 1 / -1;
//     }

//     .filter-submit .btn-apply {
//         padding: 12px;
//     }

//     /* Layout : sidebar en haut, pleine largeur */
//     .catalogue-layout {
//         grid-template-columns: 1fr;
//         gap: 20px;
//     }

//     .catalogue-sidebar {
//         position: static;
//         flex-direction: row;
//         flex-wrap: wrap;
//         gap: 15px;
//     }

//     .sidebar-card {
//         flex: 1 1 calc(50% - 8px);
//         min-width: 240px;
//     }

//     /* Grille : 2 colonnes */
//     .catalogue-products-grid {
//         grid-template-columns: repeat(2, 1fr);
//         gap: 18px;
//     }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .catalogue-header {
//         padding: 30px 14px;
//         margin-bottom: 20px;
//     }

//     .catalogue-header h1 {
//         font-size: 24px;
//     }

//     .catalogue-subtitle {
//         font-size: 14px;
//     }

//     .catalogue-stats {
//         gap: 20px;
//         margin-top: 20px;
//     }

//     .catalogue-stats .stat-value {
//         font-size: 22px;
//     }

//     .catalogue-wrapper {
//         padding: 0 12px 30px;
//     }

//     .catalogue-filters {
//         padding: 16px;
//         margin-bottom: 20px;
//     }

//     /* Filtres : 1 colonne */
//     .filters-grid {
//         grid-template-columns: 1fr;
//         gap: 10px;
//     }

//     .filter-field input,
//     .filter-field select {
//         padding: 10px 12px;
//         font-size: 14px;
//     }

//     .btn-apply {
//         padding: 12px;
//         font-size: 14px;
//     }

//     .filters-extra {
//         flex-direction: column;
//         align-items: stretch;
//         gap: 12px;
//     }

//     .filter-inline {
//         flex-wrap: wrap;
//         justify-content: flex-start;
//     }

//     .filter-inline select,
//     .input-mini {
//         flex: 1 1 auto;
//         min-width: 0;
//     }

//     /* Sidebar : pleine largeur, empilée */
//     .catalogue-sidebar {
//         flex-direction: column;
//         gap: 12px;
//     }

//     .sidebar-card {
//         flex: 1 1 100%;
//         min-width: 0;
//         padding: 16px;
//     }

//     /* Grille produits : 1 colonne */
//     .catalogue-products-grid {
//         grid-template-columns: 1fr;
//         gap: 16px;
//     }

//     .product-image {
//         height: 180px;
//     }

//     .product-info h3 {
//         font-size: 15px;
//     }

//     .product-price {
//         font-size: 16px;
//     }

//     .results-header p {
//         font-size: 13px;
//     }

//     /* Empty state */
//     .empty-state {
//         padding: 40px 16px;
//     }

//     .empty-icon {
//         font-size: 48px;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .catalogue-header h1 {
//         font-size: 20px;
//     }

//     .catalogue-stats {
//         flex-direction: column;
//         gap: 12px;
//     }

//     .filter-inline label {
//         font-size: 13px;
//     }

//     .input-mini {
//         width: 70px;
//     }
// }
// /* ============================================
//    PAGE PRODUIT (SHOW)
// ============================================ */

// .product-show-wrapper {
//     padding: 40px 20px;
// }

// /* --- Breadcrumb --- */
// .breadcrumb {
//     margin-bottom: 30px;
//     font-size: 14px;
//     display: flex;
//     flex-wrap: wrap;
//     align-items: center;
//     gap: 6px;
// }

// .breadcrumb a {
//     color: var(--green);
//     text-decoration: none;
//     transition: color 0.2s;
// }

// .breadcrumb a:hover {
//     color: var(--green-dark);
//     text-decoration: underline;
// }

// .breadcrumb-sep {
//     color: var(--muted);
// }

// .breadcrumb-current {
//     color: var(--text);
//     font-weight: 500;
// }

// /* ============================================
//    GRILLE PRINCIPALE
// ============================================ */
// .product-show-grid {
//     display: grid;
//     grid-template-columns: 1fr 1fr;
//     gap: 40px;
//     max-width: 1200px;
//     margin: 0 auto;
//     align-items: start;
// }

// /* --- Colonne image --- */
// .product-show-image-card {
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
//     padding: 20px;
//     position: sticky;
//     top: 100px;
// }

// .product-show-image {
//     aspect-ratio: 1;
//     overflow: hidden;
//     border-radius: 8px;
//     background: #f8f5f0;
// }

// .product-show-image img {
//     width: 100%;
//     height: 100%;
//     object-fit: cover;
//     display: block;
// }

// .product-badges {
//     display: flex;
//     gap: 10px;
//     margin-top: 15px;
//     flex-wrap: wrap;
// }

// .product-badges .badge {
//     padding: 5px 15px;
//     border-radius: 20px;
//     font-size: 13px;
//     font-weight: 600;
//     color: var(--white);
//     white-space: nowrap;
// }

// .product-badges .badge-promo   { background: #e74c3c; }
// .product-badges .badge-new     { background: #4CAF50; }
// .product-badges .badge-stock   { background: #27ae60; }
// .product-badges .badge-limited { background: #f39c12; }
// .product-badges .badge-out     { background: #e74c3c; }

// /* --- Colonne infos --- */
// .product-show-info h1 {
//     font-size: clamp(22px, 3vw, 28px);
//     color: var(--text);
//     margin-bottom: 10px;
//     line-height: 1.3;
// }

// .product-show-category {
//     color: var(--muted);
//     font-size: 14px;
//     margin-bottom: 15px;
// }

// .product-show-category span {
//     color: var(--green);
//     font-weight: 600;
// }

// /* Prix */
// .product-show-price-box {
//     background: rgba(11, 122, 72, 0.08);
//     padding: 15px 20px;
//     border-radius: 8px;
//     margin-bottom: 20px;
//     display: flex;
//     align-items: center;
//     flex-wrap: wrap;
//     gap: 10px;
// }

// .product-show-price {
//     font-size: clamp(24px, 3vw, 32px);
//     font-weight: 700;
//     color: var(--green-dark);
//     line-height: 1.2;
// }

// .product-show-price-old {
//     font-size: 16px;
//     color: var(--muted);
//     text-decoration: line-through;
// }

// .product-show-discount {
//     background: #e74c3c;
//     color: var(--white);
//     padding: 2px 10px;
//     border-radius: 20px;
//     font-size: 12px;
//     font-weight: 600;
// }

// /* Blocs */
// .product-show-block {
//     margin-bottom: 25px;
// }

// .product-show-block h3 {
//     font-size: 16px;
//     color: var(--text);
//     margin-bottom: 8px;
// }

// .product-show-block p {
//     color: var(--text);
//     line-height: 1.8;
//     font-size: 15px;
// }

// .product-show-ref {
//     color: var(--muted);
//     font-size: 13px;
//     margin-bottom: 20px;
// }

// .product-show-stock {
//     color: var(--text);
//     margin-bottom: 25px;
//     font-size: 15px;
// }

// /* Actions */
// .product-show-actions {
//     display: flex;
//     gap: 15px;
//     align-items: center;
//     flex-wrap: wrap;
// }

// .qty-field {
//     display: flex;
//     align-items: center;
//     gap: 10px;
// }

// .qty-field label {
//     font-weight: 600;
//     white-space: nowrap;
// }

// .qty-field input {
//     width: 70px;
//     padding: 8px;
//     border: 2px solid rgba(11, 122, 72, 0.2);
//     border-radius: 8px;
//     text-align: center;
//     font-size: 15px;
//     font-family: inherit;
//     color: var(--text);
// }

// .qty-field input:focus {
//     outline: none;
//     border-color: var(--green);
//     box-shadow: 0 0 0 3px rgba(11, 122, 72, 0.1);
// }

// /* Bouton "Ajouter" version large */
// .btn-add-cart-large,
// .product-show-actions .add-to-cart-btn {
//     padding: 12px 40px;
//     border-radius: 50px;
//     font-size: 16px;
//     font-weight: 700;
//     background: var(--green);
//     color: var(--white);
//     border: none;
//     cursor: pointer;
//     transition: background 0.3s, transform 0.3s;
//     display: inline-flex;
//     align-items: center;
//     gap: 8px;
//     white-space: nowrap;
// }

// .btn-add-cart-large:hover,
// .product-show-actions .add-to-cart-btn:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px);
// }

// .btn-add-cart-large:disabled,
// .product-show-actions .add-to-cart-btn:disabled {
//     opacity: 0.7;
//     cursor: wait;
//     transform: none;
// }

// /* Rupture de stock */
// .product-show-out {
//     background: #fde8e8;
//     padding: 15px;
//     border-radius: 8px;
//     text-align: center;
//     color: #e74c3c;
//     font-weight: 600;
// }

// /* ============================================
//    PRODUITS SIMILAIRES
// ============================================ */
// .similar-products {
//     max-width: 1200px;
//     margin: 60px auto 0;
// }

// .similar-products h2 {
//     font-size: clamp(20px, 2.5vw, 24px);
//     color: var(--text);
//     margin-bottom: 20px;
// }

// .similar-products-grid {
//     display: grid;
//     grid-template-columns: repeat(4, 1fr);
//     gap: 20px;
// }

// .similar-product-card {
//     background: var(--white);
//     border-radius: 16px;
//     overflow: hidden;
//     box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
//     border: 1px solid rgba(0, 0, 0, 0.04);
//     display: flex;
//     flex-direction: column;
//     transition: transform 0.3s, box-shadow 0.3s;
// }

// .similar-product-card:hover {
//     transform: translateY(-4px);
//     box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
// }

// .similar-product-link {
//     text-decoration: none;
//     color: inherit;
//     display: flex;
//     flex-direction: column;
//     height: 100%;
// }

// .similar-product-image {
//     height: 180px;
//     background: #f8f5f0;
//     overflow: hidden;
//     display: flex;
//     align-items: center;
//     justify-content: center;
// }

// .similar-product-image img {
//     width: 100%;
//     height: 100%;
//     object-fit: cover;
//     transition: transform 0.4s;
// }

// .similar-product-card:hover .similar-product-image img {
//     transform: scale(1.05);
// }

// .similar-product-placeholder {
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     height: 100%;
//     color: #b8860b;
//     font-size: 32px;
// }

// .similar-product-info {
//     padding: 14px;
//     display: flex;
//     flex-direction: column;
//     gap: 6px;
//     flex: 1;
// }

// .similar-product-info h3 {
//     color: var(--text);
//     font-weight: 700;
//     font-size: 15px;
//     line-height: 1.3;
//     display: -webkit-box;
//     -webkit-line-clamp: 2;
//     -webkit-box-orient: vertical;
//     overflow: hidden;
// }

// .similar-product-price {
//     font-size: 16px;
//     font-weight: 800;
//     color: var(--green-dark);
//     margin-top: auto;
// }

// /* ============================================
//    RESPONSIVE — PAGE PRODUIT
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .product-show-wrapper {
//         padding: 30px 16px;
//     }

//     .product-show-grid {
//         grid-template-columns: 1fr;
//         gap: 30px;
//     }

//     .product-show-image-card {
//         position: static;
//         max-width: 500px;
//         margin: 0 auto;
//         width: 100%;
//     }

//     .similar-products-grid {
//         grid-template-columns: repeat(2, 1fr);
//         gap: 16px;
//     }

//     .similar-products {
//         margin-top: 40px;
//     }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .product-show-wrapper {
//         padding: 20px 12px;
//     }

//     .breadcrumb {
//         font-size: 13px;
//         margin-bottom: 20px;
//     }

//     .product-show-grid {
//         gap: 24px;
//     }

//     .product-show-image-card {
//         padding: 14px;
//         border-radius: 10px;
//     }

//     .product-badges .badge {
//         padding: 4px 12px;
//         font-size: 12px;
//     }

//     .product-show-info h1 {
//         font-size: 22px;
//     }

//     .product-show-price-box {
//         padding: 12px 16px;
//         gap: 8px;
//     }

//     .product-show-price {
//         font-size: 24px;
//     }

//     .product-show-price-old {
//         font-size: 14px;
//     }

//     .product-show-block p {
//         font-size: 14px;
//         line-height: 1.7;
//     }

//     /* Actions en colonne */
//     .product-show-actions {
//         flex-direction: column;
//         align-items: stretch;
//         gap: 12px;
//     }

//     .qty-field {
//         justify-content: space-between;
//     }

//     .qty-field input {
//         width: 80px;
//     }

//     .btn-add-cart-large,
//     .product-show-actions .add-to-cart-btn {
//         width: 100%;
//         justify-content: center;
//         padding: 14px 20px;
//         font-size: 15px;
//     }

//     /* Produits similaires : 1 colonne */
//     .similar-products-grid {
//         grid-template-columns: 1fr;
//         gap: 14px;
//     }

//     .similar-products h2 {
//         font-size: 20px;
//         margin-bottom: 16px;
//     }

//     .similar-product-image {
//         height: 200px;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .product-show-info h1 {
//         font-size: 20px;
//     }

//     .product-show-price {
//         font-size: 22px;
//     }

//     .product-show-price-box {
//         flex-direction: column;
//         align-items: flex-start;
//     }

//     .product-show-image-card {
//         padding: 10px;
//     }
// }
// /* ============================================
//    PAGE D'ACCUEIL (INDEX)
// ============================================ */

// /* ============================================
//    HERO
// ============================================ */
// .home-hero {
//     position: relative;
//     background:
//         linear-gradient(90deg,
//             rgba(5, 89, 54, 0.95) 0%,
//             rgba(5, 89, 54, 0.75) 35%,
//             rgba(5, 89, 54, 0.15) 70%,
//             rgba(5, 89, 54, 0) 100%),
//         #055936;
//     background-size: cover;
//     background-position: center;
//     background-repeat: no-repeat;
//     border-radius: 0 0 32px 32px;
//     overflow: hidden;
// }

// .home-hero .container {
//     max-width: 1180px;
//     margin: 0 auto;
//     padding: 70px 24px 60px;
// }

// .home-hero-content {
//     max-width: 640px;
// }

// .hero-badge {
//     display: inline-block;
//     background: rgba(215, 154, 5, 0.15);
//     border: 1px solid var(--gold);
//     color: var(--gold-light);
//     font-size: 13px;
//     font-weight: 600;
//     padding: 6px 18px;
//     border-radius: 30px;
//     margin-bottom: 18px;
// }

// .home-hero h1 {
//     font-family: 'Playfair Display', Georgia, serif;
//     color: var(--white);
//     font-size: clamp(2rem, 4vw, 3.2rem);
//     font-weight: 800;
//     line-height: 1.15;
//     margin: 0 0 16px;
// }

// .home-hero h1 span {
//     color: var(--gold-light);
// }

// .hero-lead {
//     color: rgba(255, 255, 255, 0.85);
//     font-size: clamp(15px, 1.1vw, 17px);
//     line-height: 1.7;
//     max-width: 460px;
//     margin: 0 0 28px;
// }

// .btn-hero {
//     display: inline-block;
//     background: var(--gold);
//     color: var(--white);
//     padding: 14px 32px;
//     border-radius: 30px;
//     font-weight: 600;
//     font-size: 15px;
//     text-decoration: none;
//     transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
// }

// .btn-hero:hover {
//     background: var(--gold-light);
//     transform: translateY(-2px);
//     box-shadow: 0 8px 24px rgba(215, 154, 5, 0.4);
// }

// .hero-stats {
//     display: flex;
//     gap: 28px;
//     margin-top: 40px;
//     flex-wrap: wrap;
// }

// .hero-stat-value {
//     color: var(--white);
//     font-weight: 700;
//     font-size: 18px;
// }

// .hero-stat-label {
//     color: rgba(255, 255, 255, 0.65);
//     font-size: 13px;
// }

// /* ============================================
//    SECTION COUNT
// ============================================ */
// .section-count {
//     color: var(--muted);
//     font-size: 14px;
//     margin-top: 5px;
// }

// /* ============================================
//    CARTES PRODUITS
// ============================================ */
// .home-product-card {
//     background: var(--white);
//     border-radius: 16px;
//     overflow: hidden;
//     box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
//     border: 1px solid rgba(0, 0, 0, 0.04);
//     display: flex;
//     flex-direction: column;
//     height: 100%;
//     transition: transform 0.3s, box-shadow 0.3s;
// }

// .home-product-card:hover {
//     transform: translateY(-6px);
//     box-shadow: 0 8px 35px rgba(11, 122, 72, 0.12);
// }

// .home-product-image {
//     height: 220px;
//     background: #f8f5f0;
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     overflow: hidden;
//     position: relative;
// }

// .home-product-image > a {
//     display: block;
//     width: 100%;
//     height: 100%;
// }

// .home-product-image img {
//     width: 100%;
//     height: 100%;
//     object-fit: cover;
//     display: block;
//     transition: transform 0.4s;
// }

// .home-product-card:hover .home-product-image img {
//     transform: scale(1.05);
// }

// .home-product-noimg {
//     text-align: center;
//     color: #b8860b;
// }

// .home-product-noimg i {
//     font-size: 48px;
//     display: block;
//     margin-bottom: 8px;
// }

// .home-product-noimg span {
//     font-size: 14px;
// }

// .home-badge {
//     position: absolute;
//     top: 12px;
//     right: 12px;
//     padding: 4px 14px;
//     border-radius: 30px;
//     font-size: 11px;
//     font-weight: 600;
//     color: var(--white);
//     display: inline-flex;
//     align-items: center;
//     gap: 4px;
//     white-space: nowrap;
// }

// .home-badge-stock { background: var(--green); }
// .home-badge-out   { background: #dc3545; }

// .home-product-body {
//     padding: 18px 20px 20px;
//     flex: 1;
//     display: flex;
//     flex-direction: column;
//     gap: 8px;
// }

// .home-product-body h4 {
//     font-size: 17px;
//     font-weight: 700;
//     margin: 0;
//     line-height: 1.3;
//     display: -webkit-box;
//     -webkit-line-clamp: 2;
//     -webkit-box-orient: vertical;
//     overflow: hidden;
//     min-height: 2.6em;
// }

// .home-product-body h4 a {
//     color: var(--text);
//     text-decoration: none;
//     transition: color 0.2s;
// }

// .home-product-body h4 a:hover {
//     color: var(--green);
// }

// .home-product-category {
//     font-size: 13px;
//     color: var(--muted);
//     display: flex;
//     align-items: center;
//     gap: 6px;
// }

// .home-product-category i {
//     color: var(--gold);
// }

// .home-product-desc {
//     font-size: 13px;
//     color: var(--muted);
//     margin: 4px 0 0;
//     line-height: 1.5;
//     display: -webkit-box;
//     -webkit-line-clamp: 2;
//     -webkit-box-orient: vertical;
//     overflow: hidden;
//     flex: 1;
// }

// .home-product-footer {
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     margin-top: 10px;
//     padding-top: 12px;
//     border-top: 1px solid rgba(0, 0, 0, 0.05);
//     gap: 8px;
//     flex-wrap: wrap;
// }

// .home-product-price {
//     font-size: 20px;
//     font-weight: 800;
//     color: var(--green-dark);
//     white-space: nowrap;
// }

// .home-product-stock {
//     font-size: 12px;
//     color: var(--muted);
//     display: flex;
//     align-items: center;
//     gap: 4px;
// }

// .home-product-stock i {
//     color: var(--gold);
// }

// .home-product-action {
//     margin-top: 4px;
// }

// /* ============================================
//    PAGINATION
// ============================================ */
// .home-pagination {
//     margin-top: 40px;
//     display: flex;
//     justify-content: center;
// }

// /* ============================================
//    GUEST CTA
// ============================================ */
// .guest-cta {
//     text-align: center;
//     margin-top: 50px;
//     padding: 40px 30px;
//     background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
//     border-radius: 16px;
//     border: 2px solid var(--green);
// }

// .guest-cta-icon {
//     font-size: 48px;
//     margin-bottom: 10px;
//     line-height: 1;
// }

// .guest-cta h3 {
//     color: var(--green-dark);
//     font-size: clamp(20px, 2.5vw, 24px);
//     margin-bottom: 10px;
// }

// .guest-cta p {
//     color: var(--text);
//     font-size: 16px;
//     max-width: 500px;
//     margin: 0 auto 20px;
//     line-height: 1.6;
// }

// .guest-cta-actions {
//     display: flex;
//     gap: 15px;
//     justify-content: center;
//     flex-wrap: wrap;
// }

// /* ============================================
//    TRUST BADGES
// ============================================ */
// .trust-badges {
//     display: flex;
//     gap: 20px;
//     flex-wrap: wrap;
//     margin-top: 25px;
// }

// .trust-badge {
//     display: flex;
//     align-items: center;
//     gap: 10px;
// }

// .trust-icon {
//     background: var(--green);
//     color: var(--white);
//     border-radius: 50%;
//     width: 40px;
//     height: 40px;
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     font-size: 18px;
//     flex-shrink: 0;
// }

// .trust-value {
//     font-weight: 700;
//     color: var(--text);
// }

// .trust-label {
//     font-size: 13px;
//     color: var(--muted);
// }

// /* ============================================
//    ABOUT VISUAL (home)
// ============================================ */
// .about-visual-home {
//     background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
//     border-radius: 16px;
//     padding: 40px;
//     display: flex;
//     flex-direction: column;
//     align-items: center;
//     justify-content: center;
//     min-height: 300px;
//     border: 2px solid var(--green);
//     position: relative;
//     overflow: hidden;
//     text-align: center;
// }

// .about-visual-emoji {
//     font-size: 80px;
//     margin-bottom: 15px;
//     line-height: 1;
// }

// .about-visual-home h3 {
//     color: var(--green-dark);
//     font-size: 22px;
//     margin: 0;
// }

// .about-visual-home > p {
//     color: var(--text);
//     max-width: 280px;
//     margin: 8px 0 0;
//     line-height: 1.5;
// }

// .about-visual-tags {
//     display: flex;
//     gap: 15px;
//     margin-top: 20px;
//     flex-wrap: wrap;
//     justify-content: center;
// }

// .about-visual-tags span {
//     background: var(--white);
//     padding: 6px 16px;
//     border-radius: 30px;
//     font-size: 13px;
//     font-weight: 500;
//     box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
//     white-space: nowrap;
// }

// .about-visual-bg-1,
// .about-visual-bg-2 {
//     position: absolute;
//     opacity: 0.08;
//     pointer-events: none;
//     line-height: 1;
// }

// .about-visual-bg-1 {
//     bottom: -30px;
//     right: -30px;
//     font-size: 120px;
// }

// .about-visual-bg-2 {
//     top: -20px;
//     left: -20px;
//     font-size: 80px;
// }

// /* ============================================
//    RESPONSIVE — PAGE D'ACCUEIL
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .home-hero {
//         border-radius: 0 0 24px 24px;
//         background:
//             linear-gradient(180deg,
//                 rgba(5, 89, 54, 0.9) 0%,
//                 rgba(5, 89, 54, 0.7) 60%,
//                 rgba(5, 89, 54, 0.5) 100%),
//             #055936;
//     }

//     .home-hero .container {
//         padding: 50px 20px 40px;
//     }

//     .home-hero-content {
//         max-width: 100%;
//     }

//     .hero-lead {
//         max-width: 100%;
//     }

//     .products-grid {
//         grid-template-columns: repeat(2, 1fr);
//     }

//     .about-visual-home {
//         min-height: 260px;
//         padding: 30px 20px;
//     }

//     .about-visual-emoji {
//         font-size: 64px;
//     }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .home-hero {
//         border-radius: 0 0 16px 16px;
//         background:
//             linear-gradient(180deg,
//                 rgba(5, 89, 54, 0.95) 0%,
//                 rgba(5, 89, 54, 0.8) 100%),
//             #055936;
//     }

//     .home-hero .container {
//         padding: 36px 16px 32px;
//     }

//     .hero-badge {
//         font-size: 12px;
//         padding: 5px 14px;
//         margin-bottom: 14px;
//     }

//     .home-hero h1 {
//         font-size: 1.75rem;
//         margin-bottom: 12px;
//     }

//     .home-hero h1 br {
//         display: none;
//     }

//     .hero-lead {
//         font-size: 14px;
//         line-height: 1.6;
//         margin-bottom: 22px;
//     }

//     .btn-hero {
//         padding: 12px 26px;
//         font-size: 14px;
//         width: 100%;
//         text-align: center;
//     }

//     .hero-stats {
//         gap: 20px;
//         margin-top: 30px;
//     }

//     .hero-stat-value {
//         font-size: 16px;
//     }

//     .hero-stat-label {
//         font-size: 12px;
//     }

//     /* Produits : 1 colonne */
//     .products-grid {
//         grid-template-columns: 1fr;
//         gap: 16px;
//     }

//     .home-product-image {
//         height: 200px;
//     }

//     .home-product-body {
//         padding: 14px 16px 16px;
//     }

//     .home-product-body h4 {
//         font-size: 15px;
//     }

//     .home-product-price {
//         font-size: 17px;
//     }

//     /* Section count */
//     .section-count {
//         font-size: 13px;
//     }

//     /* Trust badges */
//     .trust-badges {
//         gap: 14px;
//         margin-top: 20px;
//     }

//     .trust-icon {
//         width: 34px;
//         height: 34px;
//         font-size: 16px;
//     }

//     .trust-value {
//         font-size: 14px;
//     }

//     .trust-label {
//         font-size: 12px;
//     }

//     /* About visual */
//     .about-visual-home {
//         min-height: 220px;
//         padding: 24px 16px;
//         border-radius: 12px;
//     }

//     .about-visual-emoji {
//         font-size: 56px;
//         margin-bottom: 10px;
//     }

//     .about-visual-home h3 {
//         font-size: 18px;
//     }

//     .about-visual-home > p {
//         font-size: 13px;
//     }

//     .about-visual-tags {
//         gap: 8px;
//         margin-top: 14px;
//     }

//     .about-visual-tags span {
//         padding: 5px 12px;
//         font-size: 12px;
//     }

//     /* Guest CTA */
//     .guest-cta {
//         padding: 28px 20px;
//         margin-top: 30px;
//         border-radius: 12px;
//     }

//     .guest-cta-icon {
//         font-size: 40px;
//     }

//     .guest-cta h3 {
//         font-size: 20px;
//     }

//     .guest-cta p {
//         font-size: 14px;
//     }

//     .guest-cta-actions {
//         flex-direction: column;
//         gap: 10px;
//     }

//     .guest-cta-actions .btn-primary,
//     .guest-cta-actions .btn-outline {
//         width: 100%;
//         text-align: center;
//         padding: 12px 20px;
//     }

//     /* Check-list */
//     .check-list {
//         font-size: 14px;
//         line-height: 1.7;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .home-hero h1 {
//         font-size: 1.5rem;
//     }

//     .hero-stat-value {
//         font-size: 15px;
//     }

//     .about-visual-tags span {
//         font-size: 11px;
//         padding: 4px 10px;
//     }

//     .trust-badge {
//         gap: 8px;
//     }

//     .trust-icon {
//         width: 30px;
//         height: 30px;
//         font-size: 14px;
//     }
// }
// /* ============================================
//    PAGE PANIER
// ============================================ */

// .cart-wrapper {
//     max-width: 1200px;
//     margin: 0 auto;
//     padding: 40px 20px;
// }

// .cart-title {
//     font-size: clamp(24px, 3vw, 32px);
//     margin-bottom: 30px;
//     color: var(--text);
// }

// /* ============================================
//    NOTICE INVITÉ
// ============================================ */
// .cart-guest-notice {
//     background: #fff3cd;
//     color: #856404;
//     padding: 15px;
//     border-radius: 8px;
//     margin-bottom: 20px;
//     line-height: 1.5;
//     font-size: 14px;
// }

// .cart-guest-notice a {
//     color: #856404;
//     font-weight: 600;
//     text-decoration: underline;
// }

// /* ============================================
//    CARTE PRINCIPALE
// ============================================ */
// .cart-card {
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
//     overflow: hidden;
// }

// /* ============================================
//    TABLEAU (desktop)
// ============================================ */
// .cart-table-wrapper {
//     display: block;
//     overflow-x: auto;
// }

// .cart-table {
//     width: 100%;
//     border-collapse: collapse;
// }

// .cart-table thead {
//     background: #f8f9fa;
// }

// .cart-table th {
//     padding: 15px;
//     text-align: left;
//     font-weight: 600;
//     font-size: 14px;
//     color: var(--text);
//     white-space: nowrap;
// }

// .cart-table th:nth-child(2),
// .cart-table th:nth-child(3),
// .cart-table th:nth-child(4),
// .cart-table th:nth-child(5) {
//     text-align: center;
// }

// .cart-table tbody tr {
//     border-bottom: 1px solid #eee;
// }

// .cart-table tbody tr:last-child {
//     border-bottom: none;
// }

// .cart-table td {
//     padding: 15px;
//     font-size: 14px;
//     color: var(--text);
// }

// .cart-cell-center { text-align: center; }

// .cart-cell-total {
//     font-weight: 700;
//     color: var(--green-dark);
//     white-space: nowrap;
// }

// /* Produit dans le tableau */
// .cart-product {
//     display: flex;
//     align-items: center;
//     gap: 15px;
// }

// .cart-product img {
//     width: 60px;
//     height: 60px;
//     object-fit: cover;
//     border-radius: 8px;
//     flex-shrink: 0;
// }

// .cart-product strong {
//     display: block;
//     font-size: 14px;
//     line-height: 1.3;
//     margin-bottom: 2px;
// }

// .cart-product p {
//     font-size: 12px;
//     color: var(--muted);
//     margin: 0;
// }

// /* Formulaire quantité */
// .cart-qty-form {
//     display: inline-flex;
//     justify-content: center;
//     gap: 5px;
//     align-items: center;
// }

// .cart-qty-form input[type="number"] {
//     width: 60px;
//     padding: 5px;
//     border: 2px solid #e0e0e0;
//     border-radius: 6px;
//     text-align: center;
//     font-size: 14px;
//     font-family: inherit;
//     color: var(--text);
// }

// .cart-qty-form input[type="number"]:focus {
//     outline: none;
//     border-color: var(--green);
//     box-shadow: 0 0 0 3px rgba(11, 122, 72, 0.1);
// }

// .btn-update {
//     background: var(--green);
//     color: var(--white);
//     padding: 6px 12px;
//     border: none;
//     border-radius: 6px;
//     cursor: pointer;
//     font-size: 14px;
//     font-weight: 600;
//     transition: background 0.3s, transform 0.2s;
// }

// .btn-update:hover {
//     background: var(--green-dark);
//     transform: scale(1.05);
// }

// .btn-delete {
//     background: #dc3545;
//     color: var(--white);
//     padding: 6px 12px;
//     border: none;
//     border-radius: 6px;
//     cursor: pointer;
//     font-size: 14px;
//     font-weight: 600;
//     transition: background 0.3s, transform 0.2s;
// }

// .btn-delete:hover {
//     background: #b02a37;
//     transform: scale(1.05);
// }

// .cart-delete-form { display: inline; }

// /* ============================================
//    VUE MOBILE (cartes)
// ============================================ */
// .cart-mobile-list {
//     display: none;
//     padding: 16px;
//     gap: 14px;
//     flex-direction: column;
// }

// .cart-mobile-item {
//     background: var(--white);
//     border: 1px solid var(--line);
//     border-radius: 12px;
//     padding: 14px;
//     display: flex;
//     flex-direction: column;
//     gap: 12px;
//     box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
// }

// .cart-mobile-top {
//     display: flex;
//     gap: 12px;
//     align-items: flex-start;
// }

// .cart-mobile-top img {
//     width: 70px;
//     height: 70px;
//     object-fit: cover;
//     border-radius: 8px;
//     flex-shrink: 0;
// }

// .cart-mobile-info {
//     flex: 1;
//     min-width: 0;
// }

// .cart-mobile-info strong {
//     display: block;
//     font-size: 15px;
//     line-height: 1.3;
//     color: var(--text);
//     margin-bottom: 3px;
// }

// .cart-mobile-info p {
//     font-size: 12px;
//     color: var(--muted);
//     margin: 0 0 4px;
// }

// .cart-mobile-unit-price {
//     font-size: 13px;
//     font-weight: 600;
//     color: var(--green-dark);
// }

// .cart-mobile-bottom {
//     display: flex;
//     align-items: center;
//     justify-content: space-between;
//     gap: 10px;
//     flex-wrap: wrap;
//     padding-top: 12px;
//     border-top: 1px solid var(--line);
// }

// .cart-mobile-total {
//     display: flex;
//     flex-direction: column;
//     align-items: flex-end;
//     line-height: 1.2;
// }

// .cart-mobile-total span {
//     font-size: 11px;
//     color: var(--muted);
//     text-transform: uppercase;
//     letter-spacing: 0.05em;
// }

// .cart-mobile-total strong {
//     font-size: 15px;
//     color: var(--green-dark);
//     white-space: nowrap;
// }

// /* ============================================
//    PIED DU PANIER
// ============================================ */
// .cart-footer {
//     padding: 20px;
//     background: #f8f9fa;
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     flex-wrap: wrap;
//     gap: 15px;
// }

// .cart-footer-total {
//     display: flex;
//     align-items: baseline;
//     gap: 15px;
//     flex-wrap: wrap;
// }

// .cart-footer-total strong {
//     font-size: 18px;
//     color: var(--text);
// }

// .cart-footer-total span {
//     font-size: clamp(20px, 2.5vw, 24px);
//     font-weight: 700;
//     color: var(--green-dark);
// }

// .cart-footer-actions {
//     display: flex;
//     gap: 15px;
//     flex-wrap: wrap;
// }

// .btn-clear,
// .btn-order {
//     padding: 10px 25px;
//     border: none;
//     border-radius: 8px;
//     cursor: pointer;
//     font-weight: 600;
//     font-size: 14px;
//     font-family: inherit;
//     display: inline-flex;
//     align-items: center;
//     justify-content: center;
//     gap: 8px;
//     text-decoration: none;
//     transition: background 0.3s, transform 0.3s;
//     white-space: nowrap;
// }

// .btn-clear {
//     background: #dc3545;
//     color: var(--white);
// }

// .btn-clear:hover {
//     background: #b02a37;
//     transform: translateY(-2px);
// }

// .btn-order {
//     background: var(--green);
//     color: var(--white);
//     padding: 10px 30px;
// }

// .btn-order:hover {
//     background: var(--green-dark);
//     transform: translateY(-2px);
// }

// /* ============================================
//    PANIER VIDE
// ============================================ */
// .cart-empty {
//     text-align: center;
//     padding: 80px 20px;
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
// }

// .cart-empty-icon {
//     font-size: 64px;
//     margin-bottom: 20px;
//     line-height: 1;
// }

// .cart-empty h3 {
//     color: var(--text);
//     font-size: clamp(20px, 2.5vw, 24px);
//     margin-bottom: 10px;
// }

// .cart-empty p {
//     color: var(--muted);
//     margin-bottom: 20px;
//     font-size: 15px;
// }

// /* ============================================
//    RESPONSIVE — PAGE PANIER
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .cart-wrapper {
//         padding: 30px 16px;
//     }

//     .cart-title {
//         font-size: 26px;
//         margin-bottom: 20px;
//     }

//     /* Basculer tableau → cartes */
//     .cart-table-wrapper { display: none; }
//     .cart-mobile-list   { display: flex; }

//     .cart-footer {
//         padding: 16px;
//     }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .cart-wrapper {
//         padding: 20px 12px;
//     }

//     .cart-title {
//         font-size: 22px;
//         margin-bottom: 16px;
//     }

//     .cart-guest-notice {
//         padding: 12px;
//         font-size: 13px;
//         line-height: 1.5;
//     }

//     .cart-mobile-list {
//         padding: 12px;
//         gap: 10px;
//     }

//     .cart-mobile-item {
//         padding: 12px;
//         border-radius: 10px;
//     }

//     .cart-mobile-top img {
//         width: 60px;
//         height: 60px;
//     }

//     .cart-mobile-info strong {
//         font-size: 14px;
//     }

//     .cart-mobile-bottom {
//         gap: 8px;
//     }

//     .cart-qty-form input[type="number"] {
//         width: 55px;
//         padding: 6px;
//     }

//     .btn-update,
//     .btn-delete {
//         padding: 6px 10px;
//         font-size: 13px;
//     }

//     /* Footer en colonne */
//     .cart-footer {
//         flex-direction: column;
//         align-items: stretch;
//         padding: 16px;
//         gap: 12px;
//     }

//     .cart-footer-total {
//         justify-content: space-between;
//         width: 100%;
//     }

//     .cart-footer-total strong {
//         font-size: 16px;
//     }

//     .cart-footer-total span {
//         font-size: 20px;
//     }

//     .cart-footer-actions {
//         flex-direction: column;
//         gap: 10px;
//         width: 100%;
//     }

//     .btn-clear,
//     .btn-order {
//         width: 100%;
//         padding: 12px 20px;
//         font-size: 14px;
//     }

//     /* Panier vide */
//     .cart-empty {
//         padding: 50px 16px;
//     }

//     .cart-empty-icon {
//         font-size: 48px;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .cart-title {
//         font-size: 20px;
//     }

//     .cart-mobile-top img {
//         width: 54px;
//         height: 54px;
//     }

//     .cart-mobile-info strong {
//         font-size: 13px;
//     }

//     .cart-mobile-unit-price {
//         font-size: 12px;
//     }

//     .cart-mobile-total strong {
//         font-size: 14px;
//     }

//     .cart-footer-total span {
//         font-size: 18px;
//     }
// }
// /* ============================================
//    PAGE CHECKOUT
// ============================================ */

// .checkout-wrapper {
//     background: #f8f5f0;
//     min-height: 100vh;
//     padding: 40px 20px;
// }

// .checkout-container {
//     max-width: 1200px;
//     margin: 0 auto;
// }

// /* ============================================
//    STEPPER
// ============================================ */
// .checkout-stepper {
//     display: flex;
//     justify-content: center;
//     align-items: center;
//     gap: 8px;
//     margin-bottom: 35px;
//     flex-wrap: wrap;
// }

// .checkout-stepper .step {
//     display: flex;
//     align-items: center;
//     gap: 8px;
// }

// .step-dot {
//     width: 14px;
//     height: 14px;
//     border-radius: 50%;
//     border: 2px solid var(--green-dark);
//     flex-shrink: 0;
// }

// .step-dot.done    { background: var(--green-dark); }
// .step-dot.pending { background: var(--white); }

// .step-label {
//     font-size: 0.85rem;
//     font-weight: 600;
//     white-space: nowrap;
// }

// .step-label.done    { color: var(--green-dark); }
// .step-label.pending { color: #9ca3af; }

// .step-line {
//     width: 40px;
//     height: 2px;
//     background: var(--green-dark);
//     flex-shrink: 0;
// }

// .step-line.done    { opacity: 1; }
// .step-line.pending { opacity: 0.3; }

// /* ============================================
//    ERREURS
// ============================================ */
// .checkout-errors {
//     background: #f8d7da;
//     color: #721c24;
//     padding: 15px 20px;
//     border-radius: 8px;
//     border-left: 4px solid #dc3545;
//     margin-bottom: 20px;
//     display: flex;
//     align-items: flex-start;
//     gap: 12px;
//     max-width: 800px;
//     margin-left: auto;
//     margin-right: auto;
//     line-height: 1.5;
// }

// .checkout-errors > i {
//     font-size: 20px;
//     margin-top: 2px;
//     flex-shrink: 0;
// }

// .checkout-errors strong {
//     display: block;
//     margin-bottom: 4px;
// }

// .checkout-errors ul {
//     margin: 0;
//     padding-left: 20px;
// }

// /* ============================================
//    LAYOUT (2 colonnes)
// ============================================ */
// .checkout-layout {
//     display: grid;
//     grid-template-columns: 1fr 380px;
//     gap: 30px;
//     align-items: start;
// }

// .checkout-main {
//     min-width: 0;
// }

// /* ============================================
//    CARTES
// ============================================ */
// .checkout-card {
//     background: var(--white);
//     border-radius: 12px;
//     padding: 25px;
//     margin-bottom: 20px;
//     border: 1px solid #e8e0d5;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
// }

// .checkout-card:last-child {
//     margin-bottom: 0;
// }

// .checkout-card-title {
//     font-size: 16px;
//     color: var(--green-dark);
//     font-weight: 700;
//     margin: 0 0 15px 0;
//     display: flex;
//     align-items: center;
//     justify-content: space-between;
//     gap: 10px;
//     flex-wrap: wrap;
// }

// .checkout-card-title > i {
//     color: var(--gold);
//     margin-right: 8px;
// }

// .checkout-card-title > span:first-child {
//     display: inline-flex;
//     align-items: center;
// }

// .checkout-count-badge {
//     background: #e8f5e9;
//     color: var(--green-dark);
//     padding: 2px 12px;
//     border-radius: 20px;
//     font-size: 0.8rem;
//     font-weight: 600;
//     white-space: nowrap;
// }

// /* ============================================
//    ARTICLES
// ============================================ */
// .checkout-item {
//     display: flex;
//     align-items: center;
//     gap: 15px;
//     padding: 12px 0;
//     border-bottom: 1px solid #f0ebe5;
// }

// .checkout-item.last {
//     border-bottom: none;
// }

// .checkout-item img {
//     width: 50px;
//     height: 50px;
//     object-fit: cover;
//     border-radius: 8px;
//     flex-shrink: 0;
// }

// .checkout-item-info {
//     flex: 1;
//     min-width: 0;
// }

// .checkout-item-name {
//     font-weight: 600;
//     color: var(--green-dark);
//     font-size: 14px;
//     line-height: 1.3;
// }

// .checkout-item-price {
//     font-size: 0.85rem;
//     color: var(--muted);
//     margin-top: 2px;
// }

// .checkout-item-totals {
//     display: flex;
//     align-items: center;
//     gap: 12px;
//     flex-shrink: 0;
// }

// .checkout-item-qty {
//     color: var(--muted);
//     font-size: 0.9rem;
// }

// .checkout-item-total {
//     font-weight: 700;
//     color: var(--green-dark);
//     min-width: 100px;
//     text-align: right;
//     white-space: nowrap;
// }

// /* ============================================
//    FORMULAIRES
// ============================================ */
// .form-group-checkout {
//     margin-bottom: 15px;
// }

// .form-group-checkout label {
//     display: block;
//     font-weight: 600;
//     color: var(--green-dark);
//     font-size: 0.9rem;
//     margin-bottom: 6px;
// }

// .form-group-checkout .required {
//     color: #dc3545;
// }

// .form-group-checkout input[type="text"],
// .form-group-checkout select,
// .form-group-checkout textarea {
//     width: 100%;
//     padding: 10px 15px;
//     border: 1px solid #e8e0d5;
//     border-radius: 8px;
//     font-size: 0.95rem;
//     font-family: inherit;
//     color: var(--text);
//     background: var(--white);
//     outline: none;
//     transition: border-color 0.3s, box-shadow 0.3s;
// }

// .form-group-checkout input[type="text"]:focus,
// .form-group-checkout select:focus,
// .form-group-checkout textarea:focus {
//     border-color: var(--green);
//     box-shadow: 0 0 0 3px rgba(11, 122, 72, 0.1);
// }

// .form-group-checkout select {
//     cursor: pointer;
//     appearance: none;
//     -webkit-appearance: none;
//     background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23777064' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
//     background-repeat: no-repeat;
//     background-position: right 14px center;
//     padding-right: 40px;
// }

// .form-group-checkout textarea {
//     resize: vertical;
//     min-height: 60px;
// }

// /* ============================================
//    MODE DE LIVRAISON
// ============================================ */
// .delivery-modes {
//     display: flex;
//     gap: 15px;
//     flex-wrap: wrap;
// }

// .delivery-option {
//     flex: 1 1 200px;
//     display: flex;
//     align-items: center;
//     gap: 10px;
//     padding: 12px 15px;
//     border: 2px solid #e8e0d5;
//     border-radius: 8px;
//     cursor: pointer;
//     transition: border-color 0.3s, background 0.3s;
// }

// .delivery-option:hover {
//     border-color: var(--green);
//     background: rgba(11, 122, 72, 0.02);
// }

// .delivery-option input[type="radio"] {
//     width: 18px;
//     height: 18px;
//     accent-color: var(--green-dark);
//     flex-shrink: 0;
// }

// .delivery-option span {
//     font-size: 0.9rem;
//     color: var(--green-dark);
//     font-weight: 500;
//     line-height: 1.3;
// }

// .delivery-option input[type="radio"]:checked + span {
//     font-weight: 700;
// }

// /* ============================================
//    INFO BOX
// ============================================ */
// .checkout-info-box {
//     display: flex;
//     align-items: flex-start;
//     gap: 12px;
//     background: #e8f5e9;
//     border: 1px solid #c8e6c9;
//     border-radius: 10px;
//     padding: 16px 18px;
// }

// .checkout-info-box > i {
//     color: var(--green-dark);
//     font-size: 18px;
//     margin-top: 2px;
//     flex-shrink: 0;
// }

// .checkout-info-box div {
//     font-size: 0.9rem;
//     color: var(--green-dark);
//     line-height: 1.5;
// }

// .checkout-info-box strong {
//     display: block;
//     margin-bottom: 2px;
// }

// .checkout-info-box span {
//     color: #4a7040;
// }

// /* ============================================
//    SIDEBAR RÉSUMÉ
// ============================================ */
// .checkout-sidebar {
//     min-width: 0;
// }

// .checkout-summary {
//     background: var(--white);
//     border-radius: 12px;
//     padding: 25px;
//     border: 1px solid #e8e0d5;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
//     position: sticky;
//     top: 100px;
// }

// .checkout-summary-box {
//     background: #f8f5f0;
//     border-radius: 10px;
//     padding: 20px;
//     margin-bottom: 20px;
// }

// .summary-line {
//     display: flex;
//     justify-content: space-between;
//     font-size: 0.9rem;
//     color: var(--muted);
//     margin-bottom: 8px;
//     gap: 10px;
// }

// .summary-line:last-of-type {
//     margin-bottom: 0;
// }

// .summary-total {
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     margin-top: 14px;
//     padding-top: 14px;
//     border-top: 2px solid var(--green-dark);
//     gap: 10px;
//     flex-wrap: wrap;
// }

// .summary-total > span:first-child {
//     font-weight: 700;
//     color: var(--green-dark);
//     font-size: 1.1rem;
// }

// .summary-total > span:last-child {
//     font-family: 'Playfair Display', serif;
//     font-weight: 800;
//     color: var(--green-dark);
//     font-size: clamp(1.2rem, 2vw, 1.5rem);
//     white-space: nowrap;
// }

// /* Bouton submit */
// .btn-checkout-submit {
//     width: 100%;
//     padding: 16px;
//     background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
//     color: var(--white);
//     border: none;
//     border-radius: 50px;
//     font-size: 1.05rem;
//     font-weight: 700;
//     font-family: inherit;
//     cursor: pointer;
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     gap: 10px;
//     box-shadow: 0 4px 15px rgba(11, 122, 72, 0.3);
//     transition: transform 0.3s, box-shadow 0.3s;
// }

// .btn-checkout-submit:hover {
//     transform: translateY(-2px);
//     box-shadow: 0 8px 24px rgba(11, 122, 72, 0.4);
// }

// /* Info livraison */
// .checkout-delivery-info {
//     margin-top: 15px;
//     padding: 15px;
//     background: #f8f5f0;
//     border-radius: 8px;
//     font-size: 0.85rem;
//     color: var(--muted);
// }

// .checkout-delivery-info > div {
//     display: flex;
//     align-items: center;
//     gap: 8px;
// }

// .checkout-delivery-info i {
//     color: var(--gold);
// }

// /* ============================================
//    RESPONSIVE — PAGE CHECKOUT
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .checkout-wrapper {
//         padding: 30px 16px;
//     }

//     .checkout-layout {
//         grid-template-columns: 1fr;
//         gap: 20px;
//     }

//     .checkout-summary {
//         position: static;
//     }

//     /* Résumé en premier sur mobile (plus utile) */
//     .checkout-sidebar {
//         order: -1;
//     }

//     .checkout-stepper {
//         gap: 6px;
//         margin-bottom: 25px;
//     }

//     .step-line {
//         width: 30px;
//     }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .checkout-wrapper {
//         padding: 20px 12px;
//     }

//     .checkout-container {
//         max-width: 100%;
//     }

//     .checkout-card {
//         padding: 18px;
//         border-radius: 10px;
//         margin-bottom: 14px;
//     }

//     .checkout-card-title {
//         font-size: 15px;
//         margin-bottom: 12px;
//     }

//     /* Stepper compact */
//     .checkout-stepper {
//         margin-bottom: 20px;
//     }

//     .step-label {
//         font-size: 0.75rem;
//     }

//     .step-dot {
//         width: 12px;
//         height: 12px;
//     }

//     .step-line {
//         width: 20px;
//     }

//     /* Article */
//     .checkout-item {
//         gap: 12px;
//         padding: 10px 0;
//         flex-wrap: wrap;
//     }

//     .checkout-item img {
//         width: 44px;
//         height: 44px;
//     }

//     .checkout-item-totals {
//         width: 100%;
//         justify-content: space-between;
//         padding-left: 56px; /* aligner avec le texte (image + gap) */
//     }

//     .checkout-item-total {
//         min-width: auto;
//         font-size: 14px;
//     }

//     .checkout-item-name {
//         font-size: 13px;
//     }

//     .checkout-item-price {
//         font-size: 12px;
//     }

//     /* Formulaires */
//     .form-group-checkout input[type="text"],
//     .form-group-checkout select,
//     .form-group-checkout textarea {
//         padding: 10px 12px;
//         font-size: 0.9rem;
//     }

//     /* Mode livraison en colonne */
//     .delivery-modes {
//         flex-direction: column;
//         gap: 10px;
//     }

//     .delivery-option {
//         flex: 1 1 auto;
//         padding: 10px 12px;
//     }

//     /* Résumé */
//     .checkout-summary {
//         padding: 18px;
//         border-radius: 10px;
//     }

//     .checkout-summary-box {
//         padding: 16px;
//     }

//     .summary-line {
//         font-size: 0.85rem;
//     }

//     .summary-total > span:first-child {
//         font-size: 1rem;
//     }

//     .summary-total > span:last-child {
//         font-size: 1.2rem;
//     }

//     .btn-checkout-submit {
//         padding: 14px;
//         font-size: 0.95rem;
//     }

//     /* Erreurs */
//     .checkout-errors {
//         padding: 12px 14px;
//         font-size: 0.85rem;
//     }

//     .checkout-info-box {
//         padding: 14px;
//         font-size: 0.85rem;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .checkout-wrapper {
//         padding: 16px 10px;
//     }

//     .checkout-card {
//         padding: 14px;
//     }

//     .checkout-item {
//         gap: 10px;
//     }

//     .checkout-item img {
//         width: 40px;
//         height: 40px;
//     }

//     .checkout-item-totals {
//         padding-left: 50px;
//     }

//     .step-line {
//         width: 14px;
//     }

//     .step-label {
//         font-size: 0.7rem;
//     }

//     .summary-total {
//         flex-direction: column;
//         align-items: flex-start;
//         gap: 4px;
//     }
// }
// /* ============================================
//    PAGE CONFIRMATION DE COMMANDE (payment)
// ============================================ */

// .payment-wrapper {
//     background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 50%, #a5d6a7 100%);
//     min-height: 100vh;
//     padding: 60px 20px;
//     display: flex;
//     justify-content: center;
//     align-items: center;
// }

// .payment-card {
//     background: var(--white);
//     max-width: 600px;
//     width: 100%;
//     border-radius: 20px;
//     box-shadow: 0 20px 60px rgba(11, 122, 72, 0.15);
//     overflow: hidden;
//     border: 1px solid rgba(11, 122, 72, 0.08);
// }

// /* ============================================
//    EN-TÊTE
// ============================================ */
// .payment-header {
//     background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
//     padding: 30px 30px 25px;
//     text-align: center;
//     position: relative;
//     overflow: hidden;
// }

// .payment-header-icon {
//     font-size: 48px;
//     margin-bottom: 10px;
//     line-height: 1;
// }

// .payment-header h1 {
//     font-family: 'Playfair Display', Georgia, serif;
//     color: var(--white);
//     font-size: clamp(22px, 3vw, 28px);
//     margin: 0;
//     line-height: 1.2;
// }

// .payment-header-sub {
//     color: rgba(255, 255, 255, 0.8);
//     font-size: 14px;
//     margin: 5px 0 0;
// }

// .payment-header-bg {
//     position: absolute;
//     top: -30px;
//     right: -30px;
//     font-size: 100px;
//     opacity: 0.08;
//     pointer-events: none;
//     line-height: 1;
// }

// /* ============================================
//    CORPS
// ============================================ */
// .payment-body {
//     padding: 30px 30px 35px;
// }

// /* ============================================
//    ERREURS
// ============================================ */
// .payment-error {
//     background: #fef2f2;
//     border: 1px solid #fecaca;
//     border-left: 4px solid #dc3545;
//     padding: 14px 18px;
//     border-radius: 10px;
//     margin-bottom: 25px;
//     display: flex;
//     align-items: flex-start;
//     gap: 10px;
//     line-height: 1.5;
// }

// .payment-error > i {
//     color: #dc3545;
//     margin-top: 2px;
//     flex-shrink: 0;
// }

// .payment-error strong {
//     color: #991b1b;
//     display: block;
// }

// .payment-error p {
//     color: #991b1b;
//     margin: 4px 0 0;
//     font-size: 14px;
// }

// /* ============================================
//    RÉCAPITULATIF
// ============================================ */
// .payment-summary {
//     background: #faf8f5;
//     border-radius: 12px;
//     padding: 20px 24px;
//     margin-bottom: 25px;
//     border: 1px solid #e8e0d5;
// }

// .payment-summary-title {
//     font-size: 16px;
//     color: var(--green-dark);
//     font-weight: 700;
//     margin: 0 0 15px 0;
//     display: flex;
//     align-items: center;
//     gap: 8px;
// }

// .payment-summary-title i {
//     color: var(--gold);
// }

// .payment-items {
//     list-style: none;
//     padding: 0;
//     margin: 0;
// }

// .payment-item {
//     display: flex;
//     justify-content: space-between;
//     align-items: flex-start;
//     gap: 12px;
//     padding: 10px 0;
//     border-bottom: 1px solid #e8e0d5;
//     font-size: 14px;
//     color: var(--green-dark);
// }

// .payment-item-name {
//     flex: 1;
//     min-width: 0;
//     line-height: 1.4;
// }

// .payment-item-qty {
//     color: var(--muted);
//     font-size: 13px;
//     margin-left: 4px;
// }

// .payment-item-price {
//     font-weight: 600;
//     white-space: nowrap;
//     flex-shrink: 0;
// }

// .payment-line {
//     display: flex;
//     justify-content: space-between;
//     gap: 12px;
//     padding: 10px 0;
//     border-bottom: 1px solid #e8e0d5;
//     font-size: 14px;
//     color: var(--muted);
// }

// .payment-line > span:last-child {
//     font-weight: 500;
//     color: var(--green-dark);
//     white-space: nowrap;
// }

// .payment-line-total {
//     padding: 15px 0 0;
//     margin-top: 5px;
//     border-top: 2px solid #e8e0d5;
//     border-bottom: none;
//     color: var(--green-dark);
// }

// .payment-line-total > span:first-child {
//     font-weight: 700;
//     font-size: 16px;
// }

// .payment-total-value {
//     font-weight: 800;
//     color: var(--green-dark);
//     font-size: clamp(18px, 2.5vw, 20px);
//     white-space: nowrap;
// }

// /* ============================================
//    ADRESSE
// ============================================ */
// .payment-address {
//     background: #e8f5e9;
//     border: 1px solid #c8e6c9;
//     border-radius: 10px;
//     padding: 16px 18px;
//     margin-bottom: 25px;
//     display: flex;
//     align-items: flex-start;
//     gap: 12px;
// }

// .payment-address > i {
//     color: var(--green-dark);
//     font-size: 18px;
//     margin-top: 2px;
//     flex-shrink: 0;
// }

// .payment-address > div {
//     font-size: 0.9rem;
//     color: var(--green-dark);
//     line-height: 1.5;
//     word-break: break-word;
// }

// .payment-address-city {
//     color: #4a7040;
// }

// /* ============================================
//    BOUTON CONFIRMATION
// ============================================ */
// .btn-payment-confirm {
//     width: 100%;
//     padding: 16px;
//     background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
//     color: var(--white);
//     border: none;
//     border-radius: 50px;
//     font-size: clamp(15px, 2vw, 18px);
//     font-weight: 700;
//     font-family: inherit;
//     cursor: pointer;
//     display: flex;
//     align-items: center;
//     justify-content: center;
//     gap: 10px;
//     box-shadow: 0 4px 15px rgba(11, 122, 72, 0.3);
//     transition: transform 0.3s, box-shadow 0.3s, opacity 0.3s;
// }

// .btn-payment-confirm:hover:not(:disabled) {
//     transform: translateY(-2px);
//     box-shadow: 0 8px 25px rgba(11, 122, 72, 0.4);
// }

// .btn-payment-confirm:disabled {
//     opacity: 0.7;
//     cursor: not-allowed;
//     transform: none;
// }

// /* ============================================
//    LIEN RETOUR
// ============================================ */
// .payment-back {
//     text-align: center;
//     margin-top: 20px;
// }

// .payment-back a {
//     color: var(--muted);
//     text-decoration: none;
//     font-size: 14px;
//     transition: color 0.3s;
//     display: inline-flex;
//     align-items: center;
//     gap: 6px;
//     line-height: 1.4;
// }

// .payment-back a:hover {
//     color: var(--green-dark);
// }

// /* ============================================
//    RESPONSIVE — PAGE CONFIRMATION
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .payment-wrapper {
//         padding: 40px 20px;
//         align-items: flex-start;
//     }

//     .payment-card {
//         border-radius: 16px;
//     }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .payment-wrapper {
//         padding: 20px 12px;
//     }

//     .payment-card {
//         border-radius: 14px;
//     }

//     .payment-header {
//         padding: 24px 20px 20px;
//     }

//     .payment-header-icon {
//         font-size: 38px;
//         margin-bottom: 8px;
//     }

//     .payment-header h1 {
//         font-size: 20px;
//     }

//     .payment-header-sub {
//         font-size: 13px;
//     }

//     .payment-header-bg {
//         font-size: 80px;
//         top: -20px;
//         right: -20px;
//     }

//     .payment-body {
//         padding: 20px 18px 24px;
//     }

//     .payment-summary {
//         padding: 16px 18px;
//         border-radius: 10px;
//         margin-bottom: 18px;
//     }

//     .payment-summary-title {
//         font-size: 15px;
//         margin-bottom: 12px;
//     }

//     .payment-item {
//         font-size: 13px;
//         padding: 8px 0;
//     }

//     .payment-item-name {
//         font-size: 13px;
//     }

//     .payment-item-price {
//         font-size: 13px;
//     }

//     .payment-line {
//         font-size: 13px;
//         padding: 8px 0;
//     }

//     .payment-line-total > span:first-child {
//         font-size: 15px;
//     }

//     .payment-total-value {
//         font-size: 18px;
//     }

//     .payment-address {
//         padding: 14px 16px;
//         margin-bottom: 18px;
//     }

//     .payment-address > div {
//         font-size: 0.85rem;
//     }

//     .btn-payment-confirm {
//         padding: 14px;
//         font-size: 15px;
//         border-radius: 40px;
//     }

//     .payment-back {
//         margin-top: 16px;
//     }

//     .payment-back a {
//         font-size: 13px;
//     }

//     .payment-error {
//         padding: 12px 14px;
//         font-size: 13px;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .payment-wrapper {
//         padding: 16px 10px;
//     }

//     .payment-header h1 {
//         font-size: 18px;
//     }

//     .payment-header-icon {
//         font-size: 34px;
//     }

//     .payment-body {
//         padding: 18px 14px 20px;
//     }

//     .payment-summary {
//         padding: 14px;
//     }

//     .payment-item {
//         font-size: 12px;
//     }

//     .payment-line {
//         font-size: 12px;
//     }

//     .payment-total-value {
//         font-size: 16px;
//     }

//     .btn-payment-confirm {
//         padding: 12px;
//         font-size: 14px;
//     }

//     .payment-back a {
//         font-size: 12px;
//     }
// }
// /* ============================================
//    PAGE MES COMMANDES (orders/index)
// ============================================ */

// .orders-wrapper {
//     max-width: 1200px;
//     margin: 0 auto;
//     padding: 40px 20px;
// }

// .orders-title {
//     font-size: clamp(24px, 3vw, 32px);
//     margin-bottom: 30px;
//     color: var(--text);
// }

// /* ============================================
//    ALERTES FLASH
// ============================================ */
// .alert {
//     padding: 15px;
//     border-radius: 8px;
//     margin-bottom: 20px;
//     font-size: 14px;
//     line-height: 1.5;
// }

// .alert-success {
//     background: #d4edda;
//     color: #155724;
// }

// .alert-error {
//     background: #f8d7da;
//     color: #721c24;
// }

// /* ============================================
//    CARTE PRINCIPALE
// ============================================ */
// .orders-card {
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
//     overflow: hidden;
// }

// /* ============================================
//    TABLEAU (desktop)
// ============================================ */
// .orders-table-wrapper {
//     display: block;
//     overflow-x: auto;
// }

// .orders-table {
//     width: 100%;
//     border-collapse: collapse;
// }

// .orders-table thead {
//     background: #f8f9fa;
// }

// .orders-table th {
//     padding: 15px;
//     text-align: left;
//     font-weight: 600;
//     font-size: 14px;
//     color: var(--text);
//     white-space: nowrap;
// }

// .orders-table th:nth-child(2),
// .orders-table th:nth-child(3),
// .orders-table th:nth-child(4),
// .orders-table th:nth-child(5) {
//     text-align: center;
// }

// .orders-table tbody tr {
//     border-bottom: 1px solid #eee;
//     transition: background 0.2s;
// }

// .orders-table tbody tr:last-child {
//     border-bottom: none;
// }

// .orders-table tbody tr:hover {
//     background: #faf8f5;
// }

// .orders-table td {
//     padding: 15px;
//     font-size: 14px;
//     color: var(--text);
//     vertical-align: middle;
// }

// .orders-cell-num strong {
//     color: var(--green-dark);
//     font-weight: 700;
// }

// .orders-cell-center { text-align: center; }

// .orders-cell-total {
//     font-weight: 700;
//     color: var(--green-dark);
//     white-space: nowrap;
// }

// /* ============================================
//    STATUTS
// ============================================ */
// .order-status {
//     display: inline-block;
//     padding: 6px 16px;
//     border-radius: 20px;
//     font-size: 13px;
//     font-weight: 600;
//     white-space: nowrap;
//     line-height: 1.2;
// }

// .status-pending   { background: #fff3e0; color: #e65100; }
// .status-paid      { background: #f3e5f5; color: #6a1b9a; }
// .status-validated { background: #e3f2fd; color: #1565c0; }
// .status-delivered { background: #e8f5e9; color: #2e7d32; }
// .status-cancelled { background: #ffebee; color: #c62828; }
// .status-default   { background: #eee;    color: #666; }

// /* ============================================
//    ACTIONS
// ============================================ */
// .orders-actions {
//     display: inline-flex;
//     gap: 5px;
//     flex-wrap: wrap;
//     justify-content: center;
// }

// .btn-order-view,
// .btn-order-ticket {
//     display: inline-flex;
//     align-items: center;
//     justify-content: center;
//     gap: 5px;
//     padding: 6px 18px;
//     border-radius: 6px;
//     text-decoration: none;
//     font-size: 13px;
//     font-weight: 600;
//     color: var(--white);
//     white-space: nowrap;
//     transition: transform 0.2s, box-shadow 0.2s;
// }

// .btn-order-view {
//     background: var(--green);
// }

// .btn-order-view:hover {
//     background: var(--green-dark);
//     transform: translateY(-1px);
//     box-shadow: 0 4px 12px rgba(11, 122, 72, 0.25);
// }

// .btn-order-ticket {
//     background: #1565c0;
// }

// .btn-order-ticket:hover {
//     background: #0d47a1;
//     transform: translateY(-1px);
//     box-shadow: 0 4px 12px rgba(21, 101, 192, 0.25);
// }

// /* ============================================
//    VUE MOBILE (cartes)
// ============================================ */
// .orders-mobile-list {
//     display: none;
//     padding: 16px;
//     gap: 14px;
//     flex-direction: column;
// }

// .order-mobile-item {
//     background: var(--white);
//     border: 1px solid var(--line);
//     border-radius: 12px;
//     padding: 16px;
//     display: flex;
//     flex-direction: column;
//     gap: 12px;
//     box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
// }

// .order-mobile-header {
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     gap: 10px;
//     flex-wrap: wrap;
//     padding-bottom: 12px;
//     border-bottom: 1px solid var(--line);
// }

// .order-mobile-num {
//     display: flex;
//     align-items: baseline;
//     gap: 6px;
//     min-width: 0;
// }

// .order-mobile-num strong {
//     color: var(--green-dark);
//     font-size: 15px;
//     word-break: break-all;
// }

// .order-mobile-label {
//     font-size: 11px;
//     color: var(--muted);
//     text-transform: uppercase;
//     letter-spacing: 0.05em;
//     font-weight: 600;
// }

// .order-mobile-body {
//     display: flex;
//     flex-direction: column;
//     gap: 8px;
// }

// .order-mobile-line {
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     gap: 10px;
//     font-size: 14px;
//     color: var(--text);
// }

// .order-mobile-total {
//     font-weight: 700;
//     color: var(--green-dark);
//     font-size: 15px;
//     white-space: nowrap;
// }

// .order-mobile-actions {
//     display: flex;
//     gap: 8px;
//     flex-wrap: wrap;
//     padding-top: 12px;
//     border-top: 1px solid var(--line);
// }

// .order-mobile-actions .btn-order-view,
// .order-mobile-actions .btn-order-ticket {
//     flex: 1 1 auto;
//     min-width: 0;
//     padding: 9px 14px;
//     font-size: 13px;
// }

// /* ============================================
//    PAGINATION
// ============================================ */
// .orders-pagination {
//     margin-top: 30px;
//     display: flex;
//     justify-content: center;
// }

// /* ============================================
//    ÉTAT VIDE
// ============================================ */
// .orders-empty {
//     text-align: center;
//     padding: 80px 20px;
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
// }

// .orders-empty-icon {
//     font-size: 64px;
//     margin-bottom: 20px;
//     line-height: 1;
// }

// .orders-empty h3 {
//     color: var(--text);
//     font-size: clamp(20px, 2.5vw, 24px);
//     margin-bottom: 10px;
// }

// .orders-empty p {
//     color: var(--muted);
//     margin-bottom: 20px;
//     font-size: 15px;
// }

// /* ============================================
//    RESPONSIVE — MES COMMANDES
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .orders-wrapper {
//         padding: 30px 16px;
//     }

//     .orders-title {
//         font-size: 26px;
//         margin-bottom: 20px;
//     }

//     /* Basculer tableau → cartes */
//     .orders-table-wrapper { display: none; }
//     .orders-mobile-list   { display: flex; }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .orders-wrapper {
//         padding: 20px 12px;
//     }

//     .orders-title {
//         font-size: 22px;
//         margin-bottom: 16px;
//     }

//     .alert {
//         padding: 12px;
//         font-size: 13px;
//         margin-bottom: 14px;
//     }

//     .orders-mobile-list {
//         padding: 12px;
//         gap: 10px;
//     }

//     .order-mobile-item {
//         padding: 14px;
//         border-radius: 10px;
//     }

//     .order-mobile-num strong {
//         font-size: 14px;
//     }

//     .order-status {
//         padding: 5px 12px;
//         font-size: 11px;
//     }

//     .order-mobile-line {
//         font-size: 13px;
//     }

//     .order-mobile-total {
//         font-size: 14px;
//     }

//     /* Actions en colonne */
//     .order-mobile-actions {
//         flex-direction: column;
//         gap: 8px;
//     }

//     .order-mobile-actions .btn-order-view,
//     .order-mobile-actions .btn-order-ticket {
//         width: 100%;
//         padding: 10px 14px;
//         justify-content: center;
//     }

//     /* État vide */
//     .orders-empty {
//         padding: 50px 16px;
//     }

//     .orders-empty-icon {
//         font-size: 48px;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .orders-title {
//         font-size: 20px;
//     }

//     .order-mobile-item {
//         padding: 12px;
//     }

//     .order-mobile-num strong {
//         font-size: 13px;
//     }

//     .order-mobile-line {
//         font-size: 12px;
//     }

//     .order-mobile-total {
//         font-size: 13px;
//     }

//     .order-mobile-actions .btn-order-view,
//     .order-mobile-actions .btn-order-ticket {
//         padding: 9px 12px;
//         font-size: 12px;
//     }
// }
// /* ============================================
//    PAGE DÉTAIL COMMANDE (orders/show)
// ============================================ */

// .order-show-wrapper {
//     max-width: 900px;
//     margin: 40px auto;
//     padding: 0 20px 60px;
// }

// /* ============================================
//    RETOUR
// ============================================ */
// .order-show-back {
//     color: var(--muted);
//     text-decoration: none;
//     font-size: 14px;
//     display: inline-block;
//     margin-bottom: 20px;
//     transition: color 0.2s, transform 0.2s;
// }

// .order-show-back:hover {
//     color: var(--green-dark);
//     transform: translateX(-2px);
// }

// /* ============================================
//    EN-TÊTE
// ============================================ */
// .order-show-header {
//     display: flex;
//     justify-content: space-between;
//     align-items: flex-start;
//     gap: 15px;
//     margin-bottom: 30px;
//     flex-wrap: wrap;
// }

// .order-show-header-left {
//     min-width: 0;
// }

// .order-show-header h1 {
//     font-size: clamp(22px, 3vw, 28px);
//     color: var(--text);
//     margin-bottom: 6px;
//     word-break: break-all;
//     line-height: 1.2;
// }

// .order-show-header p {
//     color: var(--muted);
//     font-size: 14px;
// }

// /* ============================================
//    LISTE DES ARTICLES
// ============================================ */
// .order-show-items {
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
//     overflow: hidden;
//     margin-bottom: 25px;
// }

// .order-show-item {
//     display: flex;
//     align-items: center;
//     gap: 20px;
//     padding: 18px 20px;
//     border-bottom: 1px solid #f0f0f0;
// }

// .order-show-item:last-child {
//     border-bottom: none;
// }

// .order-show-item img {
//     width: 60px;
//     height: 60px;
//     object-fit: cover;
//     border-radius: 8px;
//     flex-shrink: 0;
// }

// .order-show-item-info {
//     flex: 1;
//     min-width: 0;
// }

// .order-show-item-info h3 {
//     font-size: 15px;
//     color: var(--text);
//     margin-bottom: 4px;
//     line-height: 1.3;
//     word-break: break-word;
// }

// .order-show-item-info p {
//     font-size: 13px;
//     color: var(--muted);
// }

// .order-show-item-total {
//     font-weight: 700;
//     color: var(--text);
//     white-space: nowrap;
//     flex-shrink: 0;
// }

// /* ============================================
//    TOTAL
// ============================================ */
// .order-show-total {
//     background: var(--white);
//     border-radius: 12px;
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
//     padding: 25px;
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     gap: 15px;
//     flex-wrap: wrap;
// }

// .order-show-total > span:first-child {
//     font-size: 16px;
//     color: var(--text);
//     font-weight: 600;
// }

// .order-show-total-value {
//     font-size: clamp(20px, 3vw, 24px);
//     font-weight: 700;
//     color: var(--green-dark);
//     white-space: nowrap;
// }

// /* ============================================
//    ACTIONS / NOTES
// ============================================ */
// .order-show-action {
//     text-align: center;
//     margin-top: 25px;
// }

// .btn-primary-lg {
//     display: inline-block;
//     background: var(--green-dark);
//     color: var(--white);
//     padding: 14px 40px;
//     border-radius: 8px;
//     text-decoration: none;
//     font-weight: 600;
//     font-size: 15px;
//     transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
// }

// .btn-primary-lg:hover {
//     background: var(--green);
//     transform: translateY(-2px);
//     box-shadow: 0 6px 20px rgba(11, 122, 72, 0.3);
// }

// .order-show-note {
//     text-align: center;
//     color: var(--muted);
//     font-size: 13px;
//     margin-top: 20px;
//     line-height: 1.5;
// }

// /* ============================================
//    RESPONSIVE — DÉTAIL COMMANDE
// ============================================ */

// /* --- Tablette (<= 992px) --- */
// @media (max-width: 992px) {
//     .order-show-wrapper {
//         max-width: 100%;
//         margin: 30px auto;
//         padding: 0 16px 50px;
//     }
// }

// /* --- Mobile (<= 600px) --- */
// @media (max-width: 600px) {
//     .order-show-wrapper {
//         margin: 20px auto;
//         padding: 0 12px 40px;
//     }

//     .order-show-back {
//         font-size: 13px;
//         margin-bottom: 14px;
//     }

//     /* En-tête empilé */
//     .order-show-header {
//         margin-bottom: 20px;
//         gap: 10px;
//     }

//     .order-show-header h1 {
//         font-size: 20px;
//     }

//     .order-show-header p {
//         font-size: 13px;
//     }

//     .order-status {
//         padding: 5px 14px;
//         font-size: 12px;
//     }

//     /* Article */
//     .order-show-item {
//         padding: 14px 16px;
//         gap: 14px;
//     }

//     .order-show-item img {
//         width: 50px;
//         height: 50px;
//     }

//     .order-show-item-info h3 {
//         font-size: 14px;
//     }

//     .order-show-item-info p {
//         font-size: 12px;
//     }

//     .order-show-item-total {
//         font-size: 14px;
//     }

//     /* Total */
//     .order-show-total {
//         padding: 18px;
//         gap: 8px;
//     }

//     .order-show-total > span:first-child {
//         font-size: 15px;
//     }

//     .order-show-total-value {
//         font-size: 20px;
//     }

//     /* Actions */
//     .order-show-action {
//         margin-top: 20px;
//     }

//     .btn-primary-lg {
//         width: 100%;
//         padding: 14px 20px;
//         text-align: center;
//     }

//     .order-show-note {
//         font-size: 12px;
//         margin-top: 16px;
//     }

//     /* Alertes */
//     .alert {
//         padding: 12px;
//         font-size: 13px;
//         margin-bottom: 14px;
//     }
// }

// /* --- Très petit (<= 380px) --- */
// @media (max-width: 380px) {
//     .order-show-header h1 {
//         font-size: 18px;
//     }

//     .order-show-item {
//         padding: 12px;
//         gap: 12px;
//         flex-wrap: wrap;
//     }

//     .order-show-item img {
//         width: 44px;
//         height: 44px;
//     }

//     .order-show-item-info h3 {
//         font-size: 13px;
//     }

//     .order-show-item-total {
//         font-size: 13px;
//         width: 100%;
//         text-align: right;
//         padding-left: 56px;
//     }

//     .order-show-total-value {
//         font-size: 18px;
//     }

//     .btn-primary-lg {
//         padding: 12px 16px;
//         font-size: 14px;
//     }
// }