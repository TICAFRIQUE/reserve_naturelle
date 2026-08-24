<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMouvement;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Entrée en stock (achat, retour client, ajustement manuel positif)
     */
    public function entreeStock(
        Product $product,
        int $quantite,
        string $type,
        ?object $source = null,
        ?string $notes = null,
    ): void {
        $stockAvant = $product->qte_dispo;
        $cmpAvant   = $product->cmp;

        $nouveauCmp = $cmpAvant;
        if ($type === 'entree_achat') {
            $ligne = $source->produits->where('product_id', $product->id)->first();

            if (!$ligne) {
                throw new \Exception("Impossible de trouver la ligne d'achat du produit {$product->designation}.");
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

        $product->update([
            'qte_dispo' => $stockApres,
            'cmp'       => $nouveauCmp,
        ]);

        StockMouvement::create([
            'product_id'  => $product->id,
            'type'        => $type,
            'sens'        => 'entree',
            'quantite'    => $quantite,
            'stock_avant' => $stockAvant,
            'stock_apres' => $stockApres,
            'cmp_avant'   => $cmpAvant,
            'cmp_apres'   => $nouveauCmp,
            'source_type' => $source ? get_class($source) : null,
            'source_id'   => $source?->id,
            'user_id'     => Auth::id(),
            'notes'       => $notes,
        ]);
    }

    /**
     * Sortie de stock (commande expédiée, retour fournisseur, perte/casse, ajustement manuel négatif)
     */
    public function sortieStock(
        Product $product,
        int $quantite,
        string $type,
        ?object $source = null,
        ?string $notes = null
    ): void {
        $stockAvant = $product->qte_dispo;

        // Bloque si stock insuffisant (sauf perte/casse)
        if ($type !== 'perte_casse' && $quantite > $stockAvant) {
            throw new \Exception("Stock insuffisant pour le produit {$product->designation}.");
        }

        $stockApres = max(0, $stockAvant - $quantite);

        $product->update(['qte_dispo' => $stockApres]);

        StockMouvement::create([
            'product_id'  => $product->id,
            'type'        => $type,
            'sens'        => 'sortie',
            'quantite'    => $quantite,
            'stock_avant' => $stockAvant,
            'stock_apres' => $stockApres,
            'cmp_avant'   => $product->cmp,
            'cmp_apres'   => $product->cmp,
            'source_type' => $source ? get_class($source) : null,
            'source_id'   => $source?->id,
            'user_id'     => Auth::id(),
            'notes'       => $notes,
        ]);
    }

    /**
     * Ajustement manuel (inventaire physique ou correction) — source toujours requise (Inventaire)
     */
    public function ajustementStock(
        Product $product,
        int $qteReelle,
        string $type,
        object $source,
        ?string $notes = null
    ): void {
        $stockAvant = $product->qte_dispo;
        $ecart      = $qteReelle - $stockAvant;

        if ($ecart === 0) return;

        $sens = $ecart > 0 ? 'entree' : 'sortie';
        $product->update(['qte_dispo' => $qteReelle]);

        StockMouvement::create([
            'product_id'  => $product->id,
            'type'        => $type,
            'sens'        => $sens,
            'quantite'    => abs($ecart),
            'stock_avant' => $stockAvant,
            'stock_apres' => $qteReelle,
            'cmp_avant'   => $product->cmp,
            'cmp_apres'   => $product->cmp,
            'source_type' => get_class($source),
            'source_id'   => $source->id,
            'user_id'     => Auth::id(),
            'notes'       => $notes,
        ]);
    }

    /**
     * CMP = (stock_actuel * cmp_actuel + qte_entree * prix_unitaire) / (stock_actuel + qte_entree)
     */
    private function recalculerCMP(
        int $stockActuel,
        int $cmpActuel,
        int $qteEntree,
        int $prixUnitaire
    ): int {
        $totalUnites = $stockActuel + $qteEntree;
        if ($totalUnites === 0) return $prixUnitaire;

        return (int) round(
            ($stockActuel * $cmpActuel + $qteEntree * $prixUnitaire) / $totalUnites
        );
    }
}