<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    public function handle($request, Closure $next)
    {
        // User harus login
        if (!Auth::check()) {
            abort(403, 'Anda harus login.');
        }

        $user = Auth::user();

        // Role harus seller
        if ($user->role !== 'seller') {
            abort(403, 'Akses ditolak. Halaman ini khusus seller.');
        }

        // Cek apakah user sudah punya toko
        if (!$user->store) {
            abort(403, 'Anda belum memiliki toko.');
        }

        // Cek apakah toko sudah diverifikasi admin
        if (!$user->store->is_verified) {
            abort(403, 'Toko Anda belum diverifikasi Admin.');
        }

        return $next($request);
    }
}
