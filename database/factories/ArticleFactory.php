<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id'   => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'title'       => $this->faker->sentence(),
            'slug'        => $this->faker->unique()->slug(),
            'cover_image' => $this->faker->imageUrl,
            'body' => '<p>' . implode('</p><p>', $this->faker->paragraphs(5)) . '</p>',
            'attachments' => [
                ['url' => $this->faker->imageUrl(), 'alt' => 'Example image']
            ],
            'status'      => $this->faker->randomElement(['draft', 'published', 'archived']),
            'published_at'=> now(),
        ];
    }
}
