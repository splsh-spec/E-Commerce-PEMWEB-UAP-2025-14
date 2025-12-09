<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;

class SellerWithStoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat akun SELLER
        $seller = User::create([
            'name' => 'Seller Satu',
            'email' => 'seller10@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        // 2. Buat TOKO milik seller ini
        Store::create([
            'user_id' => $seller->id,
            'name' => 'Toko Jaya Abadi',
            'logo' => 'default_logo.png',     // pastikan tidak null
            'about' => 'Toko perlengkapan olahraga & kesehatan',
            'phone' => '08123456789',
            'address_id' => 101,              // WAJIB! Tidak boleh null
            'city' => 'Malang',
            'address' => 'Jl. Veteran No. 20',
            'postal_code' => '65142',
            'is_verified' => true,            // seller bisa masuk dashboard
        ]);
    }
}
