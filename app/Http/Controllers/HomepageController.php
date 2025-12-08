<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageController extends Controller
{
    /**
     * Tampilkan daftar semua produk + filter kategori.
     */
    public function index(Request $request): View
    {
        // Ambil semua kategori untuk dropdown filter
        $categories = ProductCategory::all();

        // Ambil kategori yang dipilih dari query string
        $selectedCategory = $request->query('category');

        // Query produk
        $products = Product::query()
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                // Gunakan kolom yang benar di database
                $query->where('product_category_id', $selectedCategory);
            })
            ->with('store', 'productCategory', 'productImages') // eager load relasi
            ->get();

        // Kirim data ke view
        return view('homepage.index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}