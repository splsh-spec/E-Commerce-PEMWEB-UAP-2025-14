<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function createSeller(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:6',
            'store_name'  => 'required|string',
            'about'       => 'nullable|string',
            'phone'       => 'required|string',
            'city'        => 'required|string',
            'address'     => 'required|string',
            'postal_code' => 'required|string',
        ]);

        // 1. Buat akun seller
        $seller = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'seller',
        ]);

        // 2. Buat toko
        Store::create([
            'user_id'     => $seller->id,
            'name'        => $request->store_name,
            'about'       => $request->about,
            'phone'       => $request->phone,
            'city'        => $request->city,
            'address'     => $request->address,
            'postal_code' => $request->postal_code,
            'logo'        => 'default_logo.png',
            'address_id'  => null,
            'is_verified' => 0, // default belum diverifikasi admin
        ]);

        return redirect()->back()->with('success', 'Seller + toko berhasil dibuat!');
    }
}
