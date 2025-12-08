<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        return view('customer.checkout.index', compact('product'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        // Kurangi stok
        $product->stock -= $request->qty;
        $product->save();

        // Buat transaksi
        Transaction::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'qty' => $request->qty,
            'total_price' => $product->price * $request->qty,
            'status' => 'pending'
        ]);

        return redirect()->route('member.history')
            ->with('success', 'Checkout berhasil, silakan selesaikan pembayaran.');
    }
}
