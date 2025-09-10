<?php

namespace Database\Factories;


use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => $this->faker->words(3, true), 
            'description'    => $this->faker->sentence(),
            'price'          => $this->faker->randomFloat(2, 50, 2000),
            'stock'          => $this->faker->numberBetween(0, 50),
            'add_info'       => [
                'color'    => $this->faker->safeColorName(),
                'material' => $this->faker->randomElement(['Wood', 'Metal', 'Fabric', 'Leather']),
                'brand'    => $this->faker->company(),
            ],
            'subcategory_id' => Category::inRandomOrder()->first()?->id,
        ];
    }
}

