<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        protected $model = User::class;


    public function definition(): array
    {
        //'fname' => $this->faker->firstName, ->property
        //'fname' => $this->faker->firstName(),->method 


        return [
            'fname'             => $this->faker->firstName,
            'lname'             => $this->faker->lastName,
            'username'          => $this->faker->unique()->userName,
            'email'             => $this->faker->unique()->safeEmail,
            'phone'             => $this->faker->optional()->phoneNumber,
            'password'          => Hash::make('1234567'), 
            'role'              => $this->faker->randomElement(['user', 'admin']),
            'email_verified_at' => null,
            'verification_code' => Str::uuid(),
            'is_verified'       => false,
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
     public function verified()
    {
        return $this->state(fn () => [
            'is_verified' => true,
            'email_verified_at' => now(),
            'verification_code' => null,
        ]);
    }
}
