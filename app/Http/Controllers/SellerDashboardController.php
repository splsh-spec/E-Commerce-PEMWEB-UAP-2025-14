<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

        // Ambil semua produk milik toko seller
        $products = Product::where('store_id', $seller->store_id)
            ->with('productImages', 'productCategory')
            ->latest()
            ->get();

        return view('seller.dashboard', [
            'seller' => $seller,
            'products' => $products,
        ]);
    }
}
