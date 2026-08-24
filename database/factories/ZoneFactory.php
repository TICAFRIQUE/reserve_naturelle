<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->randomElement([
                'Abidjan Centre',
                'Cocody',
                'Yopougon',
                'Marcory',
                'Treichville',
                'Abobo',
                'Port-Bouët',
                'Bingerville',
                'Anyama',
                'Grand-Bassam',
            ]),

            'tarif' => fake()->randomElement([
                1000,
                1500,
                2000,
                2500,
                3000,
                3500,
            ]),

            'est_expedition' => fake()->boolean(20),
        ];
    }
}