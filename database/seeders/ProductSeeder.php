<?php

namespace Database\Seeders;
use App\Models\Category;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Category::count() === 0) {
            Category::factory()->count(10)->create();
        }

        Product::factory()->count(50)->create();
    }
}
