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
            'user_id' => config('seed.member_owner_id'),
            'name' => 'Toko Olahraga Sehat',
            'address' => 'Jl. Veteran, Malang',
        ]);
    }
}
