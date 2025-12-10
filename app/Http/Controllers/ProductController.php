<?php


namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\ProductCategory;




class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }
   public function create(): View
{
    $categories = ProductCategory::all();

    return view('products.create', compact('categories'));

}

 public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name'  => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'product_category_id' => 'required',
        'store_id' => 'required',
        'description' => 'required',
    ]);

    // Buat slug otomatis
   $validated['slug'] = Str::slug($request->name);


    Product::create($validated);

    return redirect()->route('products.index')->with('status', 'Product added');
}

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'product_category_id' => 'required',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('status', 'Product updated');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Product deleted');
    }
    
}