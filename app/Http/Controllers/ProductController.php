<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOut;
use Illuminate\Support\Str;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
   public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();

        return view('product.index', compact('products'));
    }

    public function createProduct()
    {
        return view('product.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:products,name',
            'sku'  => 'required'
        ]);

        Product::create(['name' => $request->name, 'sku' => $request->sku]);

        return redirect()->route('product.index')->with('success', 'Product added.');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'sku'  => 'required'
        ]);

        $product->update([
            'name' => $request->name,
            'sku'  => $request->sku,
        ]);

        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }

}
