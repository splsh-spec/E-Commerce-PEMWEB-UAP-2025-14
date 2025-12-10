<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Form Top Up
     */
    public function topupForm()
    {
        $this->ensureMember();

        return view('customer.wallet.topup');
    }

    /**
     * Membuat permintaan topup
     */
    public function makeTopup(Request $request)
    {
        $this->ensureMember();

        $request->validate([
            'amount' => 'required|integer|min:1000'
        ]);

        // Generate nomor VA 10 digit
        $vaNumber = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);

        $topup = Topup::create([
            'user_id'   => Auth::id(),
            'amount'    => $request->amount,
            'status'    => 'waiting_payment',
            'va_number' => $vaNumber,
        ]);

        return redirect()
            ->route('wallet.payment')
            ->with('topup_id', $topup->id);
    }

    /**
     * Halaman pembayaran topup
     */
    public function paymentPage()
    {
        $topupId = session('topup_id');

        $topup = Topup::where('id', $topupId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.wallet.payment', compact('topup'));
    }

    /**
     * Konfirmasi pembayaran & update saldo
     */
    public function confirmPayment(Request $request)
    {
        $this->ensureMember();

        $request->validate([
            'topup_id' => 'required|exists:topups,id'
        ]);

        $topup = Topup::where('id', $request->topup_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Ambil user
        $user = Auth::user();

        // Jika user belum punya record balance, buat dulu
        if (!$user->balance) {
            $user->balance()->create([
                'balance' => 0
            ]);
        }

        // Tambah saldo
        $user->balance()->increment('balance', $topup->amount);

        // Update status topup
        $topup->update(['status' => 'success']);

        return redirect()
            ->route('wallet.topup')
            ->with('success', 'Pembayaran berhasil! Saldo Anda telah ditambahkan.');
    }

    /**
     * Cek role user wajib member
     */
    private function ensureMember()
    {
        if (Auth::user()->role !== 'member') {
            abort(403, 'Akses ditolak, fitur ini hanya untuk member.');
        }
    }
}