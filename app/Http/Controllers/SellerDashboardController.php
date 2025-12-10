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

if (!$seller->store) {
    abort(403, "Seller belum punya toko");
}

$products = Product::where('store_id', $seller->store->id)
    ->with('productImages', 'productCategory')
    ->get();

return view('seller.dashboard', [
    'seller' => $seller,
    'products' => $products,
]);

    }
}
