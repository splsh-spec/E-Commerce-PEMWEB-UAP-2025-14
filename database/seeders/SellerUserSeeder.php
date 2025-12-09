<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SellerUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Seller Satu',
            'email' => 'seller1@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);
    }
}
