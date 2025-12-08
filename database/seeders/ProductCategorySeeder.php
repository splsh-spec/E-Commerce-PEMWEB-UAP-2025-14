<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $categories = [
            ['Baju', 'baju', '', '', ''],
            ['Celana', 'celana', '', '', ''],
            ['Sepatu', 'sepatu', '', '', ''],
            ['Bola', 'bola', '', '', ''],
            ['Alat Olahraga', 'alat-olahraga', '', '', '']
        ];


        foreach ($categories as $cat => $ps) {
            ProductCategory::create([
                'image' => null,
                'name' => $ps[0],
                'slug' => $ps[1],
                'tagline' => $ps[2],
                'description' => $ps[3]
            ]);

        }
    }
}
