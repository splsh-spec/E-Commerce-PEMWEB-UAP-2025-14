<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function topupForm()
    {
        return view('customer.wallet.topup');
    }

    public function makeTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1000'
        ]);

        $topup = Topup::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'status' => 'waiting_payment',
            'va_number' => '1234567890', // contoh statis
        ]);

        return redirect()->route('payment.page')->with('topup_id', $topup->id);
    }

    public function paymentPage(Request $request)
    {
        $topupId = session('topup_id');

        $topup = Topup::where('id', $topupId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.wallet.payment', compact('topup'));
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'topup_id' => 'required|exists:topups,id'
        ]);

        $topup = Topup::where('id', $request->topup_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // update saldo user
        $user = Auth::user();
        $user->wallet_balance += $topup->amount;
        $user->save();

        // update status topup
        $topup->status = 'success';
        $topup->save();

        return redirect()->route('wallet.topup')->with('success', 'Pembayaran berhasil, saldo bertambah.');
    }
}
