<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('store')->get();
        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::with('store')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'role'  => 'required|in:member,seller,admin'
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // jika user punya store, hapus store
        if ($user->store) {
            $user->store->delete();
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}