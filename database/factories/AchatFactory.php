<?php

namespace Database\Factories;

use App\Models\Achat;
use App\Models\Fournisseur;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchatFactory extends Factory
{
    protected $model = Achat::class;

    public function definition(): array
    {
        $dateAchat = fake()->dateTimeBetween(
            '-1 year',
            'now'
        );

        return [
            'numero' => 'ACH-' . $dateAchat->format('Ymd') . '-' .
                fake()->unique()->numerify('####'),

            'fournisseur_id' => Fournisseur::query()->inRandomOrder()->value('id'),

            'user_id' => User::query()
                ->whereIn('role', ['admin', 'user'])
                ->inRandomOrder()
                ->value('id'),

            'statut' => fake()->randomElement([
                'confirme',
                'recu_partiel',
                'recu_total',
                'recu_total',
                'recu_total',
            ]),

            'date_achat' => $dateAchat->format('Y-m-d'),

            'date_reception_prevue' => fake()->dateTimeBetween(
                $dateAchat,
                '+30 days'
            )->format('Y-m-d'),

            'date_reception' => fake()->optional(0.8)->dateTimeBetween(
                $dateAchat,
                'now'
            )?->format('Y-m-d'),

            'mt_total' => 0,

            'mt_paye' => 0,

            'date_paiement' => fake()->optional(0.7)->dateTimeBetween(
                $dateAchat,
                'now'
            )?->format('Y-m-d'),

            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}