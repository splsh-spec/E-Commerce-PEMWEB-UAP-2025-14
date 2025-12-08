<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreBalance;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    /**
     * Menampilkan saldo toko saat ini.
     */
    public function index(Request $request)
    {
        $store = $request->user()->store;

        // Ambil saldo store
        $balance = StoreBalance::where('store_id', $store->id)->first();

        if (!$balance) {
            // Jika belum pernah dibuat, buat data saldo awal 0
            $balance = StoreBalance::create([
                'store_id' => $store->id,
                'balance' => 0,
            ]);
        }

        return view('seller.balance.index', compact('balance'));
    }

    /**
     * Menampilkan riwayat mutasi saldo.
     */
    public function history(Request $request)
    {
        $store = $request->user()->store;

        $balance = StoreBalance::where('store_id', $store->id)->first();

        $histories = $balance->storeBalanceHistories()
            ->latest()
            ->paginate(10);

        return view('seller.balance.history', compact('balance', 'histories'));
    }
}
