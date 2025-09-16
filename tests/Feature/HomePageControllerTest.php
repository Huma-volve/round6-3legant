<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use Database\Seeders\OrderSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory(10)->create();
        Category::factory(3)->create(['parent_id' => null, 'is_featured' => true])->each(function ($category) {
            Category::factory(2)->create(['parent_id' => $category->id]);
        });
        Product::factory(5)->create();
    }

    /**
     * A feature test for homeCategories method.
     */
    public function test_homeCategories(): void
    {
        $response = $this->getJson('/api/home/categories');
        // dd($response->json());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'categories' => [
                        '*' => ['id', 'name', 'image']
                    ]
                ],
            ]);
    }

    /**
     * A feature test for newProducts method.
     */
    public function test_newProducts()
    {
        $response = $this->getJson('/api/home/products/new');
        // dd($response->json());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'products' => [
                        '*' => ['id', 'name', 'price', 'created_at', 'updated_at']
                    ]
                ],
            ]);
    }

    /**
     * A feature test for mostViewedProducts method.
     */
    public function test_mostViewedProducts()
    {
        Review::factory(30)->create();
        $response = $this->getJson('/api/home/products/most-viewed');
        // dd($response->json());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'products' => [
                        '*' => ['id', 'name', 'description', 'price', 'stock', 'add_info', 'subcategory_id', 'created_at', 'updated_at', 'reviews_avg_rating']
                    ]
                ],
            ]);
    }

    /**
     * A feature test for featuredCollections method.
     */

    public function test_featuredCollections()
    {
        $response = $this->getJson('/api/home/collections/featured');
        // dd($response->json());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'categories' => [
                        '*' => [
                            'id',
                            'name',
                            'image',
                            'children' => [
                                '*' => ['id', 'name', 'parent_id', 'image']
                            ]
                        ]
                    ]
                ],
            ]);
    }

    /**
     * A feature test for bestSellerProducts method.
     */
    public function test_bestSellerProducts()
    {
        $this->seed(OrderSeeder::class);
        $response = $this->getJson('/api/home/products/best-sellers');
        // dd($response->json());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'price',
                            'stock',
                            'add_info',
                            'subcategory_id',
                            'created_at',
                            'updated_at',
                            'total_sold',
                            'total_orders'
                        ]
                    ]
                ],
            ]);

    }


}
