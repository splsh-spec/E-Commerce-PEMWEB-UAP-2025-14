<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // List transaksi user
    public function index()
    {
        $transactions = Transaction::where('buyer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.transaction.index', compact('transactions'));
    }

    // Detail transaksi
    public function show($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('buyer_id', Auth::id())
            ->with(['transactionDetails.product'])
            ->firstOrFail();

        return view('customer.transaction.show', compact('transaction'));
    }
}