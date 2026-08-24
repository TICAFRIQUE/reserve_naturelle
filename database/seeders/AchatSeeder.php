<?php

namespace Database\Seeders;

use App\Models\Achat;
use App\Models\AchatProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchatSeeder extends Seeder
{
    public function run(): void
    {
        $fournisseurs = Fournisseur::all();
        $users = User::whereIn('role', ['admin', 'user'])->get();
        $products = Product::all();

        for ($i = 1; $i <= 50; $i++) {

            DB::transaction(function () use (
                $i,
                $fournisseurs,
                $users,
                $products
            ) {

                $dateAchat = fake()->dateTimeBetween(
                    '-1 year',
                    'now'
                );

                $achat = Achat::create([
                    'numero' => 'ACH-' .
                        $dateAchat->format('Ymd') .
                        '-' .
                        str_pad($i, 4, '0', STR_PAD_LEFT),

                    'fournisseur_id' => $fournisseurs->random()->id,

                    'user_id' => $users->random()->id,

                    'statut' => fake()->randomElement([
                        'confirme',
                        'recu_partiel',
                        'recu_total',
                        'recu_total',
                    ]),

                    'date_achat' => $dateAchat->format('Y-m-d'),

                    'date_reception_prevue' => fake()
                        ->dateTimeBetween($dateAchat, '+30 days')
                        ->format('Y-m-d'),

                    'date_reception' => fake()
                        ->dateTimeBetween($dateAchat, 'now')
                        ->format('Y-m-d'),

                    'mt_total' => 0,

                    'mt_paye' => 0,

                    'date_paiement' => fake()
                        ->optional(0.8)
                        ->dateTimeBetween($dateAchat, 'now')
                        ?->format('Y-m-d'),

                    'notes' => fake()->optional(0.3)->sentence(),
                ]);

                $total = 0;

                /*
                 * 12 produits différents par achat
                 * = 600 lignes au total.
                 */
                $selectedProducts = $products
                    ->random(12);

                foreach ($selectedProducts as $product) {

                    $qteCommandee = fake()->numberBetween(10, 80);

                    if ($achat->statut === 'recu_total') {
                        $qteRecue = $qteCommandee;
                    } elseif ($achat->statut === 'recu_partiel') {
                        $qteRecue = fake()->numberBetween(
                            1,
                            max(1, $qteCommandee - 1)
                        );
                    } else {
                        $qteRecue = 0;
                    }

                    /*
                     * Le prix d'achat doit être inférieur
                     * au prix de vente.
                     */
                    $prixMax = max(
                        500,
                        (int) floor($product->prix_vente * 0.85)
                    );

                    $prixUnitaire = fake()->numberBetween(
                        500,
                        $prixMax
                    );

                    $sousTotal = $qteCommandee * $prixUnitaire;

                    AchatProduct::create([
                        'achat_id' => $achat->id,

                        'product_id' => $product->id,

                        'qte_commandee' => $qteCommandee,

                        'qte_recue' => $qteRecue,

                        'prix_unitaire' => $prixUnitaire,
                    ]);

                    $total += $sousTotal;

                    /*
                     * Mise à jour du stock uniquement
                     * pour les quantités réellement reçues.
                     */
                    if ($qteRecue > 0) {

                        $stockAvant = $product->qte_dispo;
                        $cmpAvant = $product->cmp;

                        $nouveauStock = $stockAvant + $qteRecue;

                        if ($nouveauStock > 0) {

                            $nouveauCmp = (int) round(
                                (
                                    ($stockAvant * $cmpAvant)
                                    +
                                    ($qteRecue * $prixUnitaire)
                                )
                                / $nouveauStock
                            );

                        } else {
                            $nouveauCmp = $prixUnitaire;
                        }

                        $product->update([
                            'qte_dispo' => $nouveauStock,
                            'cmp' => $nouveauCmp,
                        ]);
                    }
                }

                $mtPaye = fake()->boolean(70)
                    ? $total
                    : fake()->numberBetween(0, $total);

                $achat->update([
                    'mt_total' => $total,
                    'mt_paye' => $mtPaye,
                ]);
            });
        }
    }
}