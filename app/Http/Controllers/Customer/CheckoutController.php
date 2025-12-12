<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request, $productId = null)
    {
        $cart = session('cart', []);

        if ($productId) {
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

    public function process(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'address'       => 'required',
        'city'          => 'required',
        'postal_code'   => 'required',
        'shipping'      => 'required',       
        'shipping_type' => 'required',       
        'payment_method'=> 'required',
        'product_id'    => 'required|array',
        'qty'           => 'required|array'
    ]);

    DB::beginTransaction();

    try {

        // Ambil produk
        $products = Product::whereIn('id', $request->product_id)->get();

        // Hitung ongkir
        $shippingCost = $request->shipping_type === 'express' ? 20000 : 10000;

        // Generate address_id
        $addressId = 'ADDR-' . strtoupper(uniqid());

        // Buat transaction
        $transaction = Transaction::create([
            'code'          => 'TRX-' . strtoupper(Str::random(8)),
            'buyer_id'      => $user->id,
            'store_id'      => $products->first()->store_id,
            'address_id'    => $addressId,
            'address'       => $request->address,
            'city'          => $request->city,
            'postal_code'   => $request->postal_code,
            'shipping'      => $request->shipping,
            'shipping_type' => $request->shipping_type,
            'payment_method'=> $request->payment_method,
            'shipping_cost' => $shippingCost,
            'tax'           => 0,
            'grand_total'   => 0,
            'status'        => 'pending',
            'payment_status'=> 'unpaid',
        ]);

        $total = 0;

        // Simpan transaction detail
        foreach ($products as $product) {
            $qty = $request->qty[$product->id];

            $subtotal = $product->price * $qty;
            $total += $subtotal;

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $product->id,
                'qty'            => $qty,
                'subtotal'       => $subtotal,
            ]);

            // Kurangi stok
            $product->decrement('stock', $qty);
        }

        // Total + ongkir
        $grandTotal = $total + $shippingCost;

        $transaction->update([
            'grand_total'   => $grandTotal,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN MENGGUNAKAN SALDO
        |--------------------------------------------------------------------------
        */
        if ($request->payment_method === 'saldo') {

            // Pastikan user punya record saldo
            if (!$user->balance) {
                return back()->with('error', 'Saldo tidak ditemukan. Silakan top up.');
            }

            $currentBalance = $user->balance->balance;

            if ($currentBalance < $grandTotal) {
                return back()->with('error', 'Saldo Anda tidak cukup untuk checkout.');
            }

            // Kurangi saldo
            $user->balance->decrement('balance', $grandTotal);

            // Update payment status
            $transaction->update([
                'payment_status' => 'paid'
            ]);
        }

        DB::commit();

        return redirect()->route('home')
            ->with('success', 'Checkout berhasil!');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}