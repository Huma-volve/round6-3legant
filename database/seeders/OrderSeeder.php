<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $productCount = 50;
        $products = Product::factory()->count($productCount)->create();

        $users = User::factory()->count(20)->create();

        $ordersToCreate = 300;

        for ($i = 0; $i < $ordersToCreate; $i++) {
            $user = $users->random();

            $status = $faker->randomElement(['completed', 'processing', 'cancelled']);
            $paymentStatus = $status === 'completed' ? 'paid' : 'pending';

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => 0,
                'status' => $status,
                'payment_status' => $paymentStatus,
            ]);

            $itemsCount = rand(1, 5);
            $orderTotal = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                if (rand(1, 100) <= 60) {
                    $product = $products->slice(0, 15)->random();
                } else {
                    $product = $products->random();
                }

                $quantity = rand(1, 4);
                $price = (float) $product->price;
                $subtotal = $quantity * $price;

                OrderItem::insert([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $orderTotal += $subtotal;
            }
            
            $order->update(['total_price' => round($orderTotal, 2)]);
        }

        $this->command->info("Seeded {$productCount} products, {$users->count()} users and {$ordersToCreate} orders (with items).");
    }
}
