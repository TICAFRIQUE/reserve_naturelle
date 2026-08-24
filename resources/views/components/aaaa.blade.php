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