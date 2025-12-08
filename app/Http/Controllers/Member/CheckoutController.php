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
            'shipping_type'=> 'required|in:regular,express',
            'payment_method'=> 'required|in:saldo,va',
        ]);

        // Ambil data produk
        $productIds = $request->input('product_id');
        $quantities = $request->input('qty');

        $products = Product::whereIn('id', $productIds)->get();

        // Hitung subtotal
        $subtotal = 0;
        foreach ($products as $p) {
            $qty = $quantities[$p->id] ?? 1;
            if ($p->stock < $qty) {
                return back()->with('error', "Stok {$p->name} tidak mencukupi.");
            }
            $subtotal += $p->price * $qty;
        }

        // Ongkir
        $shippingCost = $request->shipping_type === 'express' ? 20000 : 10000;
        $total = $subtotal + $shippingCost;

        DB::beginTransaction();

        try {
            // Buat transaksi utama
            $transaction = Transaction::create([
                'user_id'        => Auth::id(),
                'address'        => $request->address,
                'shipping_type'  => $request->shipping_type,
                'shipping_cost'  => $shippingCost,
                'payment_method' => $request->payment_method,
                'total_amount'   => $total,
                'status'         => 'pending',
            ]);

            // Simpan detail tiap produk
            foreach ($products as $p) {
                $qty = $quantities[$p->id] ?? 1;
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $p->id,
                    'price'          => $p->price,
                    'quantity'       => $qty,
                    'subtotal'       => $p->price * $qty,
                ]);

                // Kurangi stok
                $p->stock -= $qty;
                $p->save();
            }

            // Handle payment
            if ($request->payment_method === 'saldo') {
                $user = Auth::user();
                if ($user->saldo < $total) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo tidak cukup.');
                }
                $user->saldo -= $total;
                $user->save();
                $transaction->status = 'paid';
                $transaction->save();
            }

            if ($request->payment_method === 'va') {
                $transaction->va_number = '8888' . rand(10000000, 99999999);
                $transaction->save();
            }

            DB::commit();

            // Hapus session cart jika ada
            if (session()->has('cart')) {
                session()->forget('cart');
            }

            return redirect()->route('member.history')
                ->with('success', 'Checkout berhasil! Silakan selesaikan pembayaran.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}