<?php

namespace Database\Seeders;

use Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Article::create([
        //     'title' => 'How to make a busy bathroom a place to relax',
        //     'slug' => Str::slug('How to make a busy bathroom a place to relax'),
        //     'excerpt' => 'Simple ways to turn a busy bathroom into a relaxing space using storage, ventilation, plants and calming materials.',
        //     'cover_image' => '/storage/articles/hero-bathroom.jpg',
        //     'author_id' => User::inRandomOrder()->first()?->id,
        //     'published_at' => Carbon::parse('2025-09-10 10:00:00'),
        // ]);

        Article::factory(10)->create();
    }
}
