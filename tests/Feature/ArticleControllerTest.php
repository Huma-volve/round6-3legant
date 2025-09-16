<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;
use App\Models\User;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $articles;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory(3)->create();
        $this->articles = Article::factory(5)->create();
    }

    /**
     * A feature test for getting articles list.
     */
    public function test_articles()
    {
        // dd(\DB::connection()->getDatabaseName());
        $response = $this->getJson('/api/articles/index');
        // dd($response->json());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'articles' => [
                        '*' => ['id', 'title', 'slug', 'cover_image', 'published_at']
                    ],
                ],
            ]);
    }

    /**
     * A feature test for getting a single article by slug.
     */
    public function test_single_article_show()
    {
        $article = $this->articles->first();
        $response = $this->getJson('/api/articles/show/' . $article->slug);
        // dd($response->json());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'articles' => [
                        'id',
                        'title',
                        'slug',
                        'cover_image',
                        'body',
                        'published_at',
                        'author'
                    ]
                ],
            ]);
    }
}

