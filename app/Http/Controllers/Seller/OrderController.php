<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List semua pesanan milik store seller.
     */
    public function index(Request $request)
    {
        $store = $request->user()->store;

        $orders = Transaction::where('store_id', $store->id)
            ->with(['buyer', 'transactionDetails.product'])
            ->latest()
            ->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Detail pesanan.
     */
    public function show($id, Request $request)
    {
        $store = $request->user()->store;

        $order = Transaction::where('store_id', $store->id)
            ->with([
                'buyer',
                'transactionDetails.product',
                'transactionDetails.product.productImages'
            ])
            ->findOrFail($id);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update status pembayaran (pending, paid, failed).
     */
    public function updatePaymentStatus($id, Request $request)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $store = $request->user()->store;

        $order = Transaction::where('store_id', $store->id)->findOrFail($id);

        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * Perbarui nomor resi pengiriman.
     */
    public function updateTracking($id, Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $store = $request->user()->store;

        $order = Transaction::where('store_id', $store->id)->findOrFail($id);

        $order->update([
            'tracking_number' => $request->tracking_number,
        ]);

        return back()->with('success', 'Nomor resi berhasil diperbarui.');
    }

    /**
     * Tandai pesanan sebagai selesai (finish).
     */
    public function markAsCompleted($id, Request $request)
    {
        $store = $request->user()->store;

        $order = Transaction::where('store_id', $store->id)->findOrFail($id);

        $order->update([
            'payment_status' => 'completed',
        ]);

        return back()->with('success', 'Pesanan berhasil ditandai sebagai selesai.');
    }

    /**
     * Hapus pesanan (opsional).
     */
    public function destroy($id, Request $request)
    {
        $store = $request->user()->store;

        $order = Transaction::where('store_id', $store->id)->findOrFail($id);

        $order->delete();

        return redirect()->route('seller.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
