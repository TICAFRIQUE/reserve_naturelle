<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SousCategory;
use Illuminate\Database\Seeder;

class SousCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            SousCategory::factory()
                ->count(3)
                ->create([
                    'category_id' => $category->id,
                ]);
        }
    }
}