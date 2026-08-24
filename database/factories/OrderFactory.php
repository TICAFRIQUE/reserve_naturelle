<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $dateOrder = fake()->dateTimeBetween(
            '-1 year',
            'now'
        );

        return [
            'num_order' => 'CMD-' .
                $dateOrder->format('Ymd') .
                '-' .
                fake()->unique()->numerify('#####'),

            'mt_total' => 0,

            'date_order' => $dateOrder->format('Y-m-d'),

            'statut' => fake()->randomElement([
                'en_attente',
                'payee',
                'validee',
                'en_livraison',
                'livree',
                'livree',
                'livree',
                'annulee',
            ]),

            'remise' => fake()->optional(0.2)->randomElement([
                'pourcentage',
                'valeur',
            ]),

            'user_id' => User::query()
                ->where('role', 'user')
                ->inRandomOrder()
                ->value('id'),

            'adresse_precise' => fake()->streetAddress(),

            'zone_id' => Zone::query()
                ->inRandomOrder()
                ->value('id'),

            'ville_expedition' => fake()->randomElement([
                'Abidjan',
                'Bouaké',
                'Yamoussoukro',
                'Daloa',
            ]),

            'tarif_livraison' => fake()->randomFloat(
                2,
                1000,
                5000
            ),

            'montant_ttc' => 0,

            'mode_livraison' => fake()->randomElement([
                'domicile',
                'domicile',
                'domicile',
                'expedition',
            ]),
        ];
    }
}