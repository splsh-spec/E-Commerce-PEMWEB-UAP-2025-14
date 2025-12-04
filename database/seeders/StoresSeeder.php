<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'user_id' => 2,
            'logo'=> 'default.png',
            'about'=> 'Toko yang menjual produk olahraga',
            'phone'=> '08123456789',
            'name' => 'Toko Olahraga Sehat',
            'address' => 'Jl. Veteran, Malang',
            'address_id'=> '101',
            'city'=> 'Malang',
            'postal_code'=> '1234',
            'is_verified'=> true,
        ]);
    }
}
