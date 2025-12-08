<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsPageController extends Controller
{
    /**
     * Menampilkan detail produk lengkap
     * - semua productImages
     * - nama store
     * - kategori produk
     * - productReviews (+user jika ada)
     */
    public function show($slug)
    {
        $product = Product::with([
            'store',
            'productCategory',
            'productImages',
            'productReviews.user'
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        return view('products.page', [
            'product' => $product
        ]);
    }
}