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
            'Baju',
            'Celana',
            'Sepatu',
            'Bola',
            'Alat Olahraga',
        ];

        foreach ($categories as $cat) {
            ProductCategory::create(['name' => $cat]);
        }
    }
}
