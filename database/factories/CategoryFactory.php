<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->randomElement([
                'Céréales',
                'Légumineuses',
                'Huiles',
                'Farines',
                'Fruits secs',
                'Graines',
                'Épices',
                'Produits naturels',
                'Boissons naturelles',
                'Produits transformés',
            ]),

            'statut' => 'actif',

            'ordre' => fake()->numberBetween(1, 10),

            'description' => fake()->sentence(),
        ];
    }
}