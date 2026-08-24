<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SousCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SousCategoryFactory extends Factory
{
    protected $model = SousCategory::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->words(2, true),

            'statut' => 'actif',

            'ordre' => fake()->numberBetween(1, 10),

            'description' => fake()->sentence(),

            'category_id' => Category::factory(),
        ];
    }
}