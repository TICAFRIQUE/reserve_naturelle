<?php

namespace Database\Factories;

use App\Models\Achat;
use App\Models\AchatProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchatProductFactory extends Factory
{
    protected $model = AchatProduct::class;

    public function definition(): array
    {
        $qteCommandee = fake()->numberBetween(10, 100);

        $prixUnitaire = fake()->numberBetween(5, 20) * 500;

        return [
            'achat_id' => Achat::query()->inRandomOrder()->value('id'),

            'product_id' => Product::query()->inRandomOrder()->value('id'),

            'qte_commandee' => $qteCommandee,

            'qte_recue' => fake()->numberBetween(
                0,
                $qteCommandee
            ),

            'prix_unitaire' => $prixUnitaire,
        ];
    }
}