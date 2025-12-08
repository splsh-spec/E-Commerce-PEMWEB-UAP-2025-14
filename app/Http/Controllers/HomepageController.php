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
        $categories = ProductCategory::all();
        $selectedCategory = $request->query('category');
        $products = Product::query()
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('category_id', $selectedCategory);
            })
            ->with('store', 'category', 'images')
            ->get();

        return view('homepage.index', [
            'products'         => $products,
            'categories'       => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}