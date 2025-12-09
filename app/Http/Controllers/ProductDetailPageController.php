<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailPageController extends Controller
{
    public function show($slug)
    {
        // Ambil produk beserta relasinya
        $product = Product::with([
            'store',
            'productImages',
            'productReviews.user' // review + user yang memberi review
        ])->where('slug', $slug)->firstOrFail();

        return view('products.detail', compact('product'));
    }
}