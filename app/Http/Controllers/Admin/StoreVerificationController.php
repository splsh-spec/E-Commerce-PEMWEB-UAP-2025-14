<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreVerificationController extends Controller
{
    public function index()
    {
        // hanya menampilkan toko yang belum diverifikasi
        $stores = Store::where('is_verified', false)->get();

        return view('admin.verification.index', compact('stores'));
    }

    public function approve($id)
    {
        $store = Store::findOrFail($id);
        $store->is_verified = true;
        $store->save();

        return redirect()->back()->with('success', 'Store berhasil diverifikasi!');
    }

    public function reject($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->back()->with('success', 'Pendaftaran store berhasil ditolak!');
    }
}