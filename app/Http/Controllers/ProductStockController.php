<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBuy;
use App\Models\ProductOut;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{

    public function index()
    {
        $productOuts = ProductOut::all();

        $productStocks = ProductStock::with('product')->get();

        $groupedStocks = $productStocks->groupBy('product_id');

        $availableSummary = [];

        foreach ($groupedStocks as $productId => $stocks) {
            $product = $stocks->first()->product; 
            $totalStockIn = $stocks->sum('quantity');

            $totalOut = $productOuts
                ->where('product_id', $productId)
                ->sum('quantity');

            $availableSummary[] = [
                'product_name'     => $product->name,
                'stock_in'         => $totalStockIn,
                'stock_out'        => $totalOut,
                'available_stock'  => $totalStockIn - $totalOut,
            ];
        }

        return view('product_stock.index', compact('productStocks', 'availableSummary'));
    }


    public function create()
    {
        $products = Product::all();
        return view('product_stock.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|numeric|min:0.01',
            'buying_price'  => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'date'          => 'required|date',
        ]);

        $productStock =ProductStock::create($request->all());

        ProductBuy::create([
            'product_stock_id'   => $productStock->id,
            'product_id'         => $request->product_id,
            'quantity'           => $request->quantity,
            'buying_price'       => $request->buying_price,
            'total_buying_price' => $request->buying_price * $request->quantity,
            'date'               => $request->date,
        ]);

        return redirect()->route('product.stock.index')->with('success', 'Stock In created successfully.');
    }

    public function edit($id)
    {
        $stock = ProductStock::findOrFail($id);
        $products = Product::all();

        return view('product_stock.edit', compact('stock', 'products'));
    }

    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|numeric|min:0.01',
            'buying_price'  => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'date'          => 'required|date',
        ]);

        // Find the product stock
        $stock = ProductStock::findOrFail($id);

        // Update product stock
        $stock->update([
            'product_id'    => $request->product_id,
            'quantity'      => $request->quantity,
            'buying_price'  => $request->buying_price,
            'selling_price' => $request->selling_price,
            'date'          => $request->date,
        ]);

        // Calculate total buying
        $totalBuying = $request->buying_price * $request->quantity;

        // Update related ProductBuy record if exists
        $productBuy = ProductBuy::where('product_stock_id', $stock->id)->first();
        if ($productBuy) {
            $productBuy->update([
                'product_id'         => $request->product_id,
                'quantity'           => $request->quantity,
                'buying_price'       => $request->buying_price,
                'total_buying_price' => $totalBuying,
                'date'               => $request->date,
            ]);
        }

        return redirect()->route('product.stock.index')->with('success', 'Stock updated successfully.');
    }
    
    public function destroy($id)
    {
        $stock = ProductStock::findOrFail($id);
        $stock->delete();

        return redirect()->route('product.stock.index')->with('success', 'Stock In deleted successfully.');
    }
}
