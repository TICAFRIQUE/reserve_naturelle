<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $prixVente = fake()->numberBetween(5, 50) * 100;

        return [
            'reference_prod' => 'PROD-' . fake()->unique()->numerify('######'),

            'designation' => fake()->randomElement([
                'Riz complet',
                'Riz local',
                'Maïs jaune',
                'Maïs blanc',
                'Sorgho',
                'Mil',
                'Avoine',
                'Haricot rouge',
                'Haricot blanc',
                'Lentilles',
                'Pois chiches',
                'Huile de coco',
                'Huile de palme',
                'Huile d’arachide',
                'Farine de maïs',
                'Farine de manioc',
                'Arachides grillées',
                'Noix de cajou',
                'Graines de chia',
                'Graines de sésame',
                'Gingembre',
                'Curcuma',
                'Miel naturel',
                'Jus naturel',
            ]) . ' ' . fake()->numberBetween(1, 100),

            'description' => fake()->paragraph(),

            'prix_vente' => $prixVente,

            'qte_dispo' => 0,

            'stock_minimum' => fake()->numberBetween(5, 30),

            'cmp' => 0,

            'category_id' => Category::query()->inRandomOrder()->value('id'),

            'image_path' => null,
        ];
    }
}