<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\WithDrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Menampilkan daftar penarikan dana dan form withdraw.
     */
    public function index(Request $request)
    {
        $store = $request->user()->store;

        // Ambil saldo toko
        $balance = StoreBalance::where('store_id', $store->id)->first();

        // Ambil riwayat penarikan
        $withdrawals = WithDrawal::where('store_balance_id', $balance->id)
            ->latest()
            ->paginate(10);

        return view('seller.withdraw.index', compact('balance', 'withdrawals'));
    }

    /**
     * Memproses request penarikan dana.
     */
    public function requestWithdraw(Request $request)
    {
        $request->validate([
            'amount'              => 'required|numeric|min:10000',
            'bank_name'           => 'required|string|max:100',
            'bank_account_name'   => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
        ]);

        $store = $request->user()->store;

        // Ambil saldo
        $balance = StoreBalance::where('store_id', $store->id)->first();

        if (!$balance || $balance->balance <= 0) {
            return back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        if ($request->amount > $balance->balance) {
            return back()->with('error', 'Jumlah penarikan melebihi saldo.');
        }

        // Buat withdrawal
        $withdraw = WithDrawal::create([
            'store_balance_id'   => $balance->id,
            'amount'             => $request->amount,
            'bank_name'          => $request->bank_name,
            'bank_account_name'  => $request->bank_account_name,
            'bank_account_number'=> $request->bank_account_number,
            'status'             => 'pending',
        ]);

        // Kurangi saldo toko
        $balance->balance -= $request->amount;
        $balance->save();

        // Catat mutasi saldo
        StoreBalanceHistory::create([
            'store_balance_id' => $balance->id,
            'amount'           => -$request->amount,
            'type'             => 'withdraw',
            'description'      => 'Pengajuan penarikan dana #' . $withdraw->id,
        ]);

        return back()->with('success', 'Pengajuan penarikan dana berhasil dikirim.');
    }
}
