<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         User::updateOrCreate(
            ['email' => 'admin@gmail.com'], 
            [
               'fname' => 'Admin',
                'lname' => 'User',
                'username' => 'admin',
                'phone' => '01000000000',
                'role' => 'admin', 
                'is_verified' => true,
                'password' => Hash::make('1234567'), 
            ]
        );
    }
}
