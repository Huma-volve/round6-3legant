<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         User::create([
            'fname' => 'Admin',
            'lname' => '1',
            'username' => 'admin',
            'email' => 'admin1@example.com',
            'phone' => '01000000000',
            'password' => Hash::make('1234567'),
            'role' => 'admin',
            'is_verified' => 1,
        ]);
    }
}
