<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreManagementController extends Controller
{
    public function index()
    {
        $stores = Store::with('user')->get();
        return view('admin.stores.index', compact('stores'));
    }

    public function edit($id)
    {
        $store = Store::with('user')->findOrFail($id);
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $request->validate([
            'name'        => 'required',
            'description' => 'nullable',
            'address'     => 'nullable',
            'is_verified' => 'required|boolean',
        ]);

        $store->update([
            'name'        => $request->name,
            'description' => $request->description,
            'address'     => $request->address,
            'is_verified' => $request->is_verified,
        ]);

        return redirect()->route('admin.stores')
            ->with('success', 'Store berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Store::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Store berhasil dihapus.');
    }
}