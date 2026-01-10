<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\ProductBuy;
use App\Models\ProductOut;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['stocks.product'])->latest()->get();
        $allStocks = ProductStock::with('product')->get();
        $groupedStocks = $allStocks->groupBy('product_id');
        $productOutSums = ProductOut::select('product_id', DB::raw('SUM(quantity) as total_out'))
            ->groupBy('product_id')
            ->pluck('total_out', 'product_id'); 

        $availableSummary = [];

        foreach ($groupedStocks as $productId => $stocks) {
            $product = $stocks->first()->product;

            $totalStockIn = $stocks->sum('quantity');
            $totalOut = $productOutSums->get($productId, 0);

            $availableSummary[] = [
                'product_name'    => $product->name,
                'stock_in'        => $totalStockIn,
                'stock_out'       => $totalOut,
                'available_stock' => $totalStockIn - $totalOut,
            ];
        }

        return view('product_stock.index', compact('purchases', 'availableSummary'));
    }


    public function create()
    {
        $products = Product::select('id', 'name', 'sku')->get();
        return view('product_stock.create', compact('products'));
    }

    public function store(Request $request)
    { 
        $request->validate([
            'date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.buying_price' => 'required|numeric|min:0',
            'products.*.selling_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $purchase = Purchase::create([
                'date' => $request->date,
            ]);

            foreach ($request->products as $item) {
                $stock = ProductStock::create([
                    'purchase_id'   => $purchase->id,
                    'product_id'    => $item['product_id'],
                    'quantity'      => $item['quantity'],
                    'buying_price'  => $item['buying_price'],
                    'selling_price' => $item['selling_price'],
                    'date'          => $request->date,
                ]);

                ProductBuy::create([
                    'purchase_id'        => $purchase->id,
                    'product_stock_id'   => $stock->id,
                    'product_id'         => $item['product_id'],
                    'quantity'           => $item['quantity'],
                    'buying_price'       => $item['buying_price'],
                    'total_buying_price' => $item['quantity'] * $item['buying_price'],
                    'date'               => $request->date,
                ]);
            }
        });

        return redirect()
            ->route('product.stock.index')
            ->with('success', 'Product stock added successfully.');
    }

    public function edit($purchase_id) 
    {
        $purchase = Purchase::with('stocks.product')->findOrFail($purchase_id);

        $stockItems = $purchase->stocks; 

        $products = Product::select('id', 'name', 'sku')->get(); 

        return view('product_stock.edit', compact('purchase', 'stockItems', 'products'));
    }

    public function update(Request $request, $purchase_id)
    {
        $request->validate([
            'date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.stock_id' => 'required|exists:product_stocks,id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.buying_price' => 'required|numeric|min:0',
            'products.*.selling_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchase_id) {
            $purchase = Purchase::findOrFail($purchase_id);
            $purchase->update([
                'date' => $request->date,
            ]);

            foreach ($request->products as $item) {

                $stock = ProductStock::findOrFail($item['stock_id']);
                $stock->update([
                    'quantity'      => $item['quantity'],
                    'buying_price'  => $item['buying_price'],
                    'selling_price' => $item['selling_price'],
                ]);

                ProductBuy::updateOrCreate(
                    ['product_stock_id' => $stock->id],
                    [
                        'purchase_id'        => $purchase->id,
                        'product_id'         => $stock->product_id,
                        'quantity'           => $item['quantity'],
                        'buying_price'       => $item['buying_price'],
                        'total_buying_price' => $item['quantity'] * $item['buying_price'],
                        'date'               => $request->date,
                    ]
                );
            }
        });

        return redirect()
            ->route('product.stock.index')
            ->with('success', 'Product stock updated successfully.');
    }

    public function destroy($purchase_id)
    {
        DB::transaction(function () use ($purchase_id) {

            $purchase = Purchase::findOrFail($purchase_id);
            $stockIds = $purchase->stocks->pluck('id'); 
            ProductBuy::whereIn('product_stock_id', $stockIds)->delete();

            // Delete stock entries
            $purchase->stocks()->delete();

            // Delete purchase
            $purchase->delete();
        });

        return redirect()
            ->route('product.stock.index')
            ->with('success', 'Purchase deleted successfully.');
    }

}
