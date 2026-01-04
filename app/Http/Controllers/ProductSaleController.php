<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOut;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductSaleController extends Controller
    
{
    public function index()
    {
        $sales = ProductOut::with('product')->orderBy('id', 'desc')->get();
    
        $summary = $sales->groupBy('product_id')->map(function ($items, $productId) {
            $product = $items->first()->product;
    
            // Total quantity sold
            $total_quantity = $items->sum('quantity');
    
            // Total buy cost
            $total_buy = 0;
            foreach ($items as $item) {
                // Latest stock before or on the sale date
                $stock = ProductStock::where('product_id', $item->product_id)
                            ->whereDate('date', '<=', $item->date)
                            ->latest('date')
                            ->first();
    
                if ($stock) {
                    $total_buy += $stock->buying_price * $item->quantity;
                }
            }
    
            // Total sell
            $total_sell = $items->sum('total_price');
    
            return (object)[
                'product' => $product,
                'total_quantity' => $total_quantity,
                'total_buy' => $total_buy,
                'total_sell' => $total_sell,
            ];
        });
    
        return view('product_sell.index', compact('summary','sales'));
    }

    
    public function productSummary(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;
    
        $query = ProductOut::with('product')->orderBy('id', 'desc');
    
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }
    
        $sales = $query->paginate(10);
    
        // 🔹 Summary (total quantity, total buy, total sell)
        $summary = ProductOut::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_buy) as total_buy'),
                DB::raw('SUM(total_price) as total_sell')
            )
            ->with('product')
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->get();
    
        return view('product_sell.index', compact('sales', 'summary', 'startDate', 'endDate'));
    }


    public function create()
    {
        $products = Product::all();

        $latestSellingPrices = [];
        $latestBuyingPrices = [];

        foreach ($products as $product) {
            $latestStock = ProductStock::where('product_id', $product->id)
                                ->orderByDesc('id')
                                ->first();

            $latestSellingPrices[$product->id] = $latestStock?->selling_price ?? 0;
            $latestBuyingPrices[$product->id] = $latestStock?->buying_price ?? 0;
        }

        return view('product_sell.create', compact('products', 'latestSellingPrices', 'latestBuyingPrices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|numeric|min:0.01',
            'total_price'  => 'required|numeric',
            'total_buy'    => 'required|numeric',
            'date'         => 'required|date',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        $totalStock = ProductStock::where('product_id', $productId)->sum('quantity');
        $totalOut = ProductOut::where('product_id', $productId)->sum('quantity');
        $availableStock = $totalStock - $totalOut;

        if ($quantity > $availableStock) {
            return redirect()->back()->withInput()->withErrors([
                'message' => "Only {$availableStock} quantity is available in stock for this product.",
            ]);
        }

        ProductOut::create([
            'product_id'   => $productId,
            'quantity'     => $quantity,
            'total_price'  => $request->total_price,
            'total_buy'    => $request->total_buy,
            'date'         => $request->date,
        ]);

        return redirect()->route('product.sales.index')->with('success', 'Product sold successfully.');
    }

    public function edit($id)
    {
        // Get the sale record to edit
        $sale = ProductOut::findOrFail($id);

        // Get all products
        $products = Product::all();

        // Prepare latest selling and buying prices per product
        $latestSellingPrices = [];
        $latestBuyingPrices = [];

        foreach ($products as $product) {
            $latestStock = ProductStock::where('product_id', $product->id)
                                ->orderByDesc('date')
                                ->first();

            $latestSellingPrices[$product->id] = $latestStock?->selling_price ?? 0;
            $latestBuyingPrices[$product->id]  = $latestStock?->buying_price ?? 0;
        }

        return view('product_sell.edit', compact('sale', 'products', 'latestSellingPrices', 'latestBuyingPrices'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|numeric|min:0.01',
            'total_price'  => 'required|numeric',
            'total_buy'    => 'required|numeric',
            'date'         => 'required|date',
        ]);

        $sale = ProductOut::findOrFail($id);

        $productId = $request->product_id;

        $totalStock = ProductStock::where('product_id', $productId)->sum('quantity');
        $totalOut = ProductOut::where('product_id', $productId)
                        ->where('id', '!=', $sale->id) 
                        ->sum('quantity');
        

        $availableStock = $totalStock - $totalOut;

        if ($request->quantity > $availableStock) {
            return redirect()->back()->withInput()->withErrors([
                'quantity' => "Only {$availableStock} quantity is available in stock for this product.",
            ]);
        }

        $sale->product_id   = $productId;
        $sale->quantity     = $request->quantity;
        $sale->total_price  = $request->total_price;
        $sale->total_buy    = $request->total_buy;
        $sale->date         = $request->date;
        $sale->save();

        return redirect()->route('product.sales.index')->with('success', 'Product sell updated successfully.');
    }

    public function destroy($id)
    {
        $sale = ProductOut::findOrFail($id);

        $sale->delete();

        return redirect()->route('product.sales.index')->with('success', 'Product sell record deleted successfully.');
    }
}
