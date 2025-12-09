<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout.
     * Bisa dari keranjang (multiple) atau langsung satu produk.
     */
    public function index(Request $request, $productId = null)
    {
        $cart = session('cart', []);

        if ($productId) {
            // Checkout satu produk
            $product = Product::findOrFail($productId);
            return view('customer.checkout.index', [
                'products' => [$product],
                'cart'     => [$product->id => 1],
            ]);
        }

        if (empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        return view('customer.checkout.index', [
            'products' => $products,
            'cart'     => $cart,
        ]);
    }

    /**
     * Proses checkout.
     */
    public function process(Request $request)
    {
    $request->validate([
        'product_id.*' => 'required|exists:products,id',
        'qty.*'        => 'required|integer|min:1',
        'address'      => 'required|string',
        'city'         => 'required|string',
        'postal_code'  => 'required|string',
        'shipping_type'=> 'required|in:regular,express',
        'payment_method'=> 'required|in:saldo,va',
    ]);

    $user = Auth::user();

    $productIds = $request->product_id;
    $quantities = $request->qty;

    $products = Product::whereIn('id', $productIds)->get();

    // Hitung subtotal
    $subtotal = 0;
    foreach ($products as $p) {
        $qty = $quantities[$p->id];
        if ($p->stock < $qty) {
            return back()->with('error', "Stok {$p->name} tidak cukup.");
        }
        $subtotal += $p->price * $qty;
    }

    $shippingCost = $request->shipping_type === 'express' ? 20000 : 10000;

    $grandTotal = $subtotal + $shippingCost;

    DB::beginTransaction();

    try {

        // === 1. CREATE TRANSACTION ===
        $transaction = Transaction::create([
            'code'          => 'TRX-' . strtoupper(uniqid()),
            'buyer_id'      => $user->id,
            'store_id'      => $products->first()->store_id, // ambil dari produk
            'address'       => $request->address,
            'address_id'    => 'ADDR-' . rand(10000, 99999),
            'city'          => $request->city,
            'postal_code'   => $request->postal_code,
            'shipping'      => 'jne', // fix bisa disesuaikan
            'shipping_type' => $request->shipping_type,
            'shipping_cost' => $shippingCost,
            'tax'           => 0,
            'grand_total'   => $grandTotal,
        ]);

        // === 2. DETAIL ===
        foreach ($products as $p) {
            $qty = $quantities[$p->id];

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $p->id,
                'price'          => $p->price,
                'quantity'       => $qty,
                'subtotal'       => $p->price * $qty,
            ]);

            $p->stock -= $qty;
            $p->save();
        }

        // === 3. PAYMENT ===
        if ($request->payment_method === 'saldo') {
            if ($user->saldo < $grandTotal) {
                DB::rollBack();
                return back()->with('error', 'Saldo tidak cukup.');
            }
            $user->saldo -= $grandTotal;
            $user->save();
        }

        DB::commit();

        session()->forget('cart');

        return redirect()->route('member.history')
            ->with('success', 'Checkout berhasil!');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
    }
    }
}