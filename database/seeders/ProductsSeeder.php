<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store = Store::first();  // toko milik member
        $categories = ProductCategory::all();

        $products = [
            ['Baju Olahraga', 55000, 10],
            ['Celana Training', 27000, 40],
            ['Sepatu Bola', 160000, 30],
            ['Bola Kaki', 55000, 35],
            ['Barbel 10kg', 200000, 20],
            ['Kaos Polos', 25000, 50],
            ['Celana Pendek', 20000, 5],
            ['Sepatu Hiking', 220000, 25],
            ['Bola Basket', 55000, 100],
            ['Treadmill', 1250000, 80],
        ];

        foreach ($products as $i => $p) {
            Product::create([
                'store_id' => $store->id,
                'category_id' => $categories[$i % count($categories)]->id,
                'name' => $p[0],
                'price' => $p[1],
                'stock' => $p[2],
            ]);
        }
    }
}
