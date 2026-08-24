<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'qte' => fake()->numberBetween(1, 10),

            'order_id' => Order::query()->inRandomOrder()->value('id'),

            'product_id' => Product::query()->inRandomOrder()->value('id'),
        ];
    }
}