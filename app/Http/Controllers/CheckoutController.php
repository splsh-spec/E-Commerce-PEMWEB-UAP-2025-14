<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        return view('checkout.index', [
            'products' => $products,
            'cart'     => $cart,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address'        => 'required|string',
            'shipping_type'  => 'required|in:regular,express',
            'payment_method' => 'required|in:saldo,va',
        ]);

        $shippingCost = $request->shipping_type === 'express' ? 20000 : 10000;
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        $subtotal = 0;
        foreach ($products as $p) {
            $subtotal += $p->price * $cart[$p->id];
        }

        $total = $subtotal + $shippingCost;

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'user_id'        => Auth::id(),
                'address'        => $request->address,
                'shipping_type'  => $request->shipping_type,
                'shipping_cost'  => $shippingCost,
                'payment_method' => $request->payment_method,
                'total_amount'   => $total,
                'status'         => 'pending',
            ]);

            foreach ($products as $p) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $p->id,
                    'price'          => $p->price,
                    'quantity'       => $cart[$p->id],
                    'subtotal'       => $p->price * $cart[$p->id],
                ]);

                $p->stock -= $cart[$p->id];
                $p->save();
            }

            if ($request->payment_method === 'saldo') {
                $user = Auth::user();

                if ($user->saldo < $total) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo tidak cukup');
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

            session()->forget('cart');

            return redirect('/history')->with('success', 'Checkout berhasil!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}