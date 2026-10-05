<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\StockMouvement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Entrée en stock (achat, retour client, ajustement manuel positif)
     */
    public function entreeStock(
        ProductVariant $variant,
        int $quantite,
        string $type,
        ?object $source = null,
        ?string $notes = null,
    ): void {
        DB::transaction(function () use ($variant, $quantite, $type, $source, $notes) {

            // Recharger et verrouiller la variante
            $variant = ProductVariant::whereKey($variant->id)->lockForUpdate()->firstOrFail();
            $stockAvant = $variant->qte_dispo;
            $cmpAvant = $variant->cmp;
            $nouveauCmp = $cmpAvant;

            if ($type === 'entree_achat') {
                $ligne = $source->produits
                    ->where('product_variant_id', $variant->id)
                    ->first();

                if (!$ligne) {
                    throw new \Exception(
                        "Impossible de trouver la ligne d'achat de la variante {$variant->reference_prod}."
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

            $variant->update([
                'qte_dispo' => $stockApres,
                'cmp' => $nouveauCmp,
            ]);

            StockMouvement::create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'type' => $type,
                'sens' => 'entree',
                'quantite' => $quantite,
                'stock_avant' => $stockAvant,
                'stock_apres' => $stockApres,
                'cmp_avant' => $cmpAvant,
                'cmp_apres' => $nouveauCmp,
                'source_type' => $source ? get_class($source) : null,
                'source_id' => $source?->id,
                'user_id' => Auth::id(),
                'notes' => $notes,
            ]);
        }, 3);
    }

    /**
     * Sortie de stock (commande expédiée, retour fournisseur, perte/casse, ajustement manuel négatif)
     */
    public function sortieStock(
        ProductVariant $variant,
        int $quantite,
        string $type,
        ?object $source = null,
        ?string $notes = null
    ): void {
        DB::transaction(function () use ($variant, $quantite, $type, $source, $notes) {

            // Recharger et verrouiller la variante
            $variant = ProductVariant::whereKey($variant->id)->lockForUpdate()->firstOrFail();
            $stockAvant = $variant->qte_dispo;
            $cmpAvant = $variant->cmp;

            // Bloquer si stock insuffisant (y compris perte/casse : on ne peut pas perdre plus que le stock
            // physique ; si le stock est faux, corriger par un ajustement d'inventaire)
            if ($quantite > $stockAvant) {
                throw new \RuntimeException(
                    "Stock insuffisant pour la variante {$variant->reference_prod}."
                );
            }
            $stockApres = max(0, $stockAvant - $quantite);
            $variant->update([
                'qte_dispo' => $stockApres,
            ]);

            StockMouvement::create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'type' => $type,
                'sens' => 'sortie',
                'quantite' => $quantite,
                'stock_avant' => $stockAvant,
                'stock_apres' => $stockApres,
                'cmp_avant' => $cmpAvant,
                'cmp_apres' => $cmpAvant,
                'source_type' => $source ? get_class($source) : null,
                'source_id' => $source?->id,
                'user_id' => Auth::id(),
                'notes' => $notes,
            ]);
        }, 3);
    }

    /**
     * Ajustement manuel (inventaire physique ou correction) — source toujours requise (Inventaire)
     */
    public function ajustementStock(
        ProductVariant $variant,
        int $qteReelle,
        string $type,
        object $source,
        ?string $notes = null
    ): void {
        DB::transaction(function () use ($variant, $qteReelle, $type, $source, $notes) {

            // Recharger et verrouiller la variante
            $variant = ProductVariant::whereKey($variant->id)->lockForUpdate()->firstOrFail();
            $stockAvant = $variant->qte_dispo;
            $ecart = $qteReelle - $stockAvant;
            if ($ecart === 0) {
                return;
            }

            $sens = $ecart > 0 ? 'entree' : 'sortie';
            $variant->update([
                'qte_dispo' => $qteReelle,
            ]);

            StockMouvement::create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'type' => $type,
                'sens' => $sens,
                'quantite' => abs($ecart),
                'stock_avant' => $stockAvant,
                'stock_apres' => $qteReelle,
                'cmp_avant' => $variant->cmp,
                'cmp_apres' => $variant->cmp,
                'source_type' => get_class($source),
                'source_id' => $source->id,
                'user_id' => Auth::id(),
                'notes' => $notes,
            ]);
        }, 3);
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

        if ($totalUnites === 0) {
            return $prixUnitaire;
        }
        return (int) round(
            ($stockActuel * $cmpActuel + $qteEntree * $prixUnitaire) / $totalUnites
        );
    }
}