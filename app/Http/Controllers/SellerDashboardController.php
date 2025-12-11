<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerDashboardController extends Controller
{
    /**
     * Halaman Dashboard Seller
     */
    public function index(): View
    {
        $seller = Auth::user();

        if (!$seller->store) {
            abort(403, "Seller belum punya toko");
        }

        // Ambil produk milik toko seller
        $products = Product::where('store_id', $seller->store->id)
            ->with(['productImages', 'productCategory'])
            ->get();

        // Tambah data kategori jika ingin filter kategori di dashboard
        $categories = ProductCategory::all();
        $selectedCategory = request('category');

        return view('seller.products.index', [
            'seller' => $seller,
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}