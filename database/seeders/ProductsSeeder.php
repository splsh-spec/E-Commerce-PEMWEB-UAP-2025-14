<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset tabel (agar selalu fresh setiap seed)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductImage::truncate();
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $store = Store::first();
        $categories = ProductCategory::all();

        if (!$store) {
            dd("Gagal: Store belum ada. Jalankan seeder Store terlebih dahulu.");
        }

        if ($categories->count() == 0) {
            dd("Gagal: Category belum ada. Seed ProductCategory dulu.");
        }

        $products = [
            ['Baju Olahraga', 55000, 10, 1, '', 5, 'Baju olahraga.jpg'],
            ['Celana Training', 27000, 40, 2, '', 5, 'Celana training.jpg'],
            ['Sepatu Bola', 160000, 30, 3, '', 5, 'Sepatu bola.jpg'],
            ['Bola Kaki', 55000, 35, 4, '', 5, 'Bola kaki.jpg'],
            ['Barbel 10kg', 200000, 20, 5, '', 5, 'Barbel 10kg.webp'],
            ['Kaos Polos', 25000, 50, 1, '', 5, 'Baju kaos.jpg'],
            ['Celana Pendek', 20000, 5, 2, '', 5, 'Celana pendek.jpg'],
            ['Sepatu Hiking', 220000, 25, 3, '', 5, 'Sepatu hiking.webp'],
            ['Bola Basket', 55000, 100, 4, '', 5, 'Bola basket.jpg'],
            ['Treadmill', 1250000, 80, 5, '', 5, 'Treadmill.jpg'],
        ];

        foreach ($products as $i => $p) {

            // Buat slug unik
            $slug = Str::slug($p[0]) . '-' . ($i + 1);

            // Buat produk
            $product = Product::create([
                'store_id'              => $store->id,
                'product_category_id'   => $categories[$i % $categories->count()]->id,
                'name'                  => $p[0],
                'price'                 => $p[1],
                'stock'                 => $p[2],
                'slug'                  => $slug,
                'description'           => $p[4] ?: $p[0] . ' berkualitas tinggi.',
                'weight'                => $p[5],
                'condition'             => 'new',
            ]);

            // Tambahkan gambar product
            ProductImage::create([
                'product_id'    => $product->id,
                'image'         => 'products/' . $p[6], // pastikan file ini ada
                'is_thumbnail'  => true
            ]);
        }

        echo "Products seeder berhasil dijalankan!\n";
    }
}