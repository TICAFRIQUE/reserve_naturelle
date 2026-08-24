<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Zone;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        $products = Product::where(
            'qte_dispo',
            '>',
            0
        )->get();

        $zones = Zone::all();

        $stockService = app(StockService::class);

        if ($users->isEmpty()) {
            throw new \RuntimeException(
                'Aucun utilisateur avec le rôle user.'
            );
        }

        if ($products->count() < 10) {
            throw new \RuntimeException(
                'Il faut au moins 10 produits avec du stock.'
            );
        }

        if ($zones->isEmpty()) {
            throw new \RuntimeException(
                'Aucune zone disponible.'
            );
        }

        for ($i = 1; $i <= 150; $i++) {

            DB::transaction(function () use (
                $i,
                $users,
                $products,
                $zones,
                $stockService
            ) {

                /*
                |--------------------------------------------------------------------------
                | UTILISATEUR
                |--------------------------------------------------------------------------
                |
                | On sélectionne UN utilisateur pour cette commande.
                | Son ID sera utilisé :
                | - dans orders.user_id
                | - dans stock_mouvements.user_id
                |
                */

                $user = $users->random();

                /*
                |--------------------------------------------------------------------------
                | DATE
                |--------------------------------------------------------------------------
                */

                $dateOrder = fake()->dateTimeBetween(
                    '-1 year',
                    'now'
                );

                /*
                |--------------------------------------------------------------------------
                | MODE DE LIVRAISON
                |--------------------------------------------------------------------------
                */

                $modeLivraison = fake()->randomElement([
                    'domicile',
                    'domicile',
                    'domicile',
                    'expedition',
                ]);

                $zone = $zones->random();

                /*
                |--------------------------------------------------------------------------
                | STATUT
                |--------------------------------------------------------------------------
                */

                $statut = fake()->randomElement([
                    'en_attente',
                    'payee',
                    'validee',
                    'en_livraison',
                    'livree',
                    'livree',
                    'livree',
                ]);

                /*
                |--------------------------------------------------------------------------
                | CRÉATION DE LA COMMANDE
                |--------------------------------------------------------------------------
                */

                $order = Order::create([

                    'num_order' => 'CMD-' .
                        $dateOrder->format('Ymd') .
                        '-' .
                        str_pad(
                            $i,
                            5,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'mt_total' => 0,

                    'date_order' =>
                        $dateOrder->format('Y-m-d'),

                    'statut' => $statut,

                    'remise' => fake()
                        ->optional(0.2)
                        ->randomElement([
                            'pourcentage',
                            'valeur',
                        ]),

                    'user_id' => $user->id,

                    'adresse_precise' =>
                        fake()->streetAddress(),

                    'zone_id' => $zone->id,

                    'ville_expedition' =>
                        fake()->randomElement([
                            'Abidjan',
                            'Bouaké',
                            'Yamoussoukro',
                            'Daloa',
                        ]),

                    'tarif_livraison' => $zone->tarif,

                    'montant_ttc' => 0,

                    'mode_livraison' => $modeLivraison,
                ]);

                $total = 0;

                /*
                |--------------------------------------------------------------------------
                | 10 PRODUITS PAR COMMANDE
                |--------------------------------------------------------------------------
                |
                | 150 commandes × 10 produits = 1500 lignes
                |
                */

                $selectedProducts = $products->random(10);

                foreach ($selectedProducts as $product) {

                    /*
                    |--------------------------------------------------------------------------
                    | STOCK ACTUEL
                    |--------------------------------------------------------------------------
                    */

                    $stockDisponible = $product->qte_dispo;

                    if ($stockDisponible <= 0) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | QUANTITÉ
                    |--------------------------------------------------------------------------
                    */

                    $qteMax = min(
                        5,
                        $stockDisponible
                    );

                    $qte = fake()->numberBetween(
                        1,
                        $qteMax
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | LIGNE DE COMMANDE
                    |--------------------------------------------------------------------------
                    */

                    OrderItem::create([
                        'qte' => $qte,
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | CALCUL DU TOTAL
                    |--------------------------------------------------------------------------
                    */

                    $sousTotal =
                        $qte * $product->prix_vente;

                    $total += $sousTotal;

                    /*
                    |--------------------------------------------------------------------------
                    | SORTIE DE STOCK
                    |--------------------------------------------------------------------------
                    |
                    | Les commandes :
                    |
                    | - en_attente → aucune sortie
                    | - payee → aucune sortie
                    | - validee → sortie
                    | - en_livraison → sortie
                    | - livree → sortie
                    |
                    */

                    if (in_array($statut, [
                        'validee',
                        'en_livraison',
                        'livree',
                    ])) {

                        $stockService->sortieStock(
                            $product,
                            $qte,
                            'sortie_commande',
                            $order,
                            'Sortie de stock générée par le Seeder',
                            $user->id
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | TOTAL COMMANDE
                |--------------------------------------------------------------------------
                */

                $totalAvecLivraison =
                    $total +
                    ($order->tarif_livraison ?? 0);

                $order->update([
                    'mt_total' =>
                        $totalAvecLivraison,

                    'montant_ttc' =>
                        $totalAvecLivraison,
                ]);
            });
        }
    }
}