<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;

class AdminController extends Controller
{
    public function index()
    {
        // Statistik ringkas (opsional tapi sangat umum untuk dashboard)
        $totalUsers = User::count();
        $totalStores = Store::count();
        $unverifiedStores = Store::where('is_verified', false)->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalStores' => $totalStores,
            'unverifiedStores' => $unverifiedStores,
        ]);
    }
}