<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Role harus seller
        if ($user->role !== 'seller') {
            return redirect()->route('login.redirect');
        }

        // Jika belum punya toko → arahkan ke halaman registrasi toko
        if (!$user->store) {
            return redirect()->route('store.register')
                ->with('error', 'Anda harus membuat toko terlebih dahulu.');
        }

        // Jika toko belum diverifikasi admin
        if (!$user->store->is_verified) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Toko Anda menunggu verifikasi admin.');
        }

        return $next($request);
    }
}