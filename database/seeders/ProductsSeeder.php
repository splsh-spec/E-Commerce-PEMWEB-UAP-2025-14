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
            ['Baju Olahraga', 55000, 10, 1, '',5 , ''],
            ['Celana Training', 27000, 40, 2, '', 5, ''],
            ['Sepatu Bola', 160000, 30, 3, '', 5, ''],
            ['Bola Kaki', 55000, 35, 4, '',5 , ''],
            ['Barbel 10kg', 200000, 20, 5, '',5, ''],
            ['Kaos Polos', 25000, 50, 1, '',5 , ''],
            ['Celana Pendek', 20000, 5, 2, '',5 , ''],
            ['Sepatu Hiking', 220000, 25, 3, '', 5, ''],
            ['Bola Basket', 55000, 100, 4, '', 5, ''],
            ['Treadmill', 1250000, 80, 5, '', 5, ''],
        ];

        foreach ($products as $i => $p) {
            Product::create([
                'store_id' => $store->id,
                'product_category_id' => $categories[$i % count($categories)]->id,
                'name' => $p[0],
                'price' => $p[1],
                'stock' => $p[2],
                'slug'=> $p[0],
                'description'=> $p[4],
                'weight'=> $p[5],
                'condition'=> 'new',
            ]);
        }
    }
}
