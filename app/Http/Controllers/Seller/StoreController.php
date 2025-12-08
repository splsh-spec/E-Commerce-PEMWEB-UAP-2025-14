<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Dashboard Seller
     * Menampilkan ringkasan toko (produk, saldo, order masuk)
     */
    public function dashboard()
    {
        $store = Store::where('user_id', Auth::id())->firstOrFail();

        return view('seller.dashboard', [
            'store' => $store,
            'product_count' => $store->products()->count(),
            'order_count' => $store->transactions()->count(),
            'balance' => $store->balance->balance ?? 0,
        ]);
    }

    /**
     * Form Edit Profil Toko
     */
    public function edit()
    {
        $store = Store::where('user_id', Auth::id())->firstOrFail();
        return view('seller.profile.edit', compact('store'));
    }

    /**
     * Update Profil Toko
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'about'  => 'nullable|string',
            'phone'  => 'required|string|max:20',
            'logo'   => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'address' => 'required|string',
            'city'    => 'required|string',
            'postal_code' => 'required|string|max:10',
        ]);

        $store = Store::where('user_id', Auth::id())->firstOrFail();

        // upload logo jika ada
        if ($request->hasFile('logo')) {
            $filename = time() . '.' . $request->logo->extension();
            $request->logo->storeAs('public/stores', $filename);
            $store->logo = $filename;
        }

        $store->update([
            'name' => $request->name,
            'about' => $request->about,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
        ]);

        return redirect()->back()->with('success', 'Profil toko berhasil diperbarui.');
    }
}
