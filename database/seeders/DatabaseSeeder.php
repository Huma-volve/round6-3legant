<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */




    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->create([
            'fname' => 'John',
            'lname' => 'Doe',
            'username' => 'johndoe',
            'email' => 'test@example.com',
        ]);


        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
            ArticleSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            UsersTableSeeder::class,

        ]);





        User::factory()->create([
            'fname' => 'Test User',
            'email' => 'test@example.com',
        ]);


    }
}
