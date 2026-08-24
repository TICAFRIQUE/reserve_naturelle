<?php

namespace Database\Factories;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

class FournisseurFactory extends Factory
{
    protected $model = Fournisseur::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),

            'prenom' => fake()->firstName(),

            'tel' => '05' . fake()->numerify('########'),

            'adress' => fake()->streetAddress(),

            'ville' => fake()->randomElement([
                'Abidjan',
                'Bouaké',
                'Yamoussoukro',
                'Daloa',
                'Korhogo',
                'San-Pédro',
                'Man',
            ]),

            'date_ajout' => fake()->dateTimeBetween(
                '-2 years',
                'now'
            )->format('Y-m-d'),
        ];
    }
}