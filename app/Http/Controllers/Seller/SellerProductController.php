<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SellerProductController extends Controller
{
    /**
     * Tampilkan semua produk milik seller.
     */
    public function index(Request $request)
    {
        $store = $request->user()->store;

        $products = Product::where('store_id', $store->id)
            ->with('productCategory')
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Form tambah produk.
     */
    public function create(Request $request)
    {
        $categories = ProductCategory::all();

        return view('seller.products.create', compact('categories'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'condition'           => 'required|in:new,used',
            'price'               => 'required|numeric|min:0',
            'weight'              => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
        ]);

        $store = $request->user()->store;

        $product = Product::create([
            'store_id'            => $store->id,
            'product_category_id' => $request->product_category_id,
            'name'                => $request->name,
            'slug'                => Str::slug($request->name) . '-' . Str::random(5),
            'description'         => $request->description,
            'condition'           => $request->condition,
            'price'               => $request->price,
            'weight'              => $request->weight,
            'stock'               => $request->stock,
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Form edit produk.
     */
    public function edit($id, Request $request)
    {
        $store = $request->user()->store;

        $product = Product::where('store_id', $store->id)->findOrFail($id);

        $categories = ProductCategory::all();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Update produk.
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'condition'           => 'required|in:new,used',
            'price'               => 'required|numeric|min:0',
            'weight'              => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
        ]);

        $store = $request->user()->store;

        $product = Product::where('store_id', $store->id)->findOrFail($id);

        $product->update([
            'product_category_id' => $request->product_category_id,
            'name'                => $request->name,
            'slug'                => Str::slug($request->name) . '-' . Str::random(5),
            'description'         => $request->description,
            'condition'           => $request->condition,
            'price'               => $request->price,
            'weight'              => $request->weight,
            'stock'               => $request->stock,
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk.
     */
    public function destroy($id, Request $request)
    {
        $store = $request->user()->store;

        $product = Product::where('store_id', $store->id)->findOrFail($id);

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
