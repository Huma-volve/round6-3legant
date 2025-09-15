<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $mainCategories = [
            'Living Room',
            'Bedroom',
            'Dining Room',
            'Office',
            'Outdoor',
        ];

        foreach ($mainCategories as $main) {
            $parent = Category::create([
                'name'        => $main,
                'slug'        => Str::slug($main),
                'description' => "Shop for $main furniture",
                'parent_id'   => null,
                'is_featured' => true,
            ]);

            // Example child categories
            $children = match ($main) {
                'Living Room' => ['Sofas', 'Coffee Tables', 'TV Stands', 'Recliners'],
                'Bedroom'     => ['Beds', 'Wardrobes', 'Nightstands', 'Dressers'],
                'Dining Room' => ['Dining Tables', 'Chairs', 'Bar Stools'],
                'Office'      => ['Desks', 'Office Chairs', 'Bookcases'],
                'Outdoor'     => ['Patio Sets', 'Outdoor Chairs', 'Umbrellas'],
                default       => [],
            };

            foreach ($children as $child) {
                Category::create([
                    'name'        => $child,
                    'slug'        => Str::slug($child),
                    'description' => "Shop for $child",
                    'parent_id'   => $parent->id,
                ]);
            }
        }
    }


}
