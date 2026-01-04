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
        ]);

        Product::create(['name' => $request->name]);

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
        ]);

        $product->update([
            'name' => $request->name,
        ]);

        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }

    public function reportView($type)
    {
        $queryDate = now();

        if ($type == 'monthly') {
            $products = Product::with([
                'stocks' => function ($q) use ($queryDate) {
                    $q->whereMonth('date', $queryDate->month)->whereYear('date', $queryDate->year);
                },
                'outs' => function ($q) use ($queryDate) {
                    $q->whereMonth('date', $queryDate->month)->whereYear('date', $queryDate->year);
                },
            ])->get();
        } elseif ($type == 'yearly') {
            $products = Product::with([
                'stocks' => function ($q) use ($queryDate) {
                    $q->whereYear('date', $queryDate->year);
                },
                'outs' => function ($q) use ($queryDate) {
                    $q->whereYear('date', $queryDate->year);
                },
            ])->get();
        } else {
            // today
            $products = Product::with([
                'stocks' => function ($q) use ($queryDate) {
                    $q->whereDate('date', $queryDate->toDateString());
                },
                'outs' => function ($q) use ($queryDate) {
                    $q->whereDate('date', $queryDate->toDateString());
                },
            ])->get();
        }

        // Calculate total profit
        $totalProfit = 0;
        foreach ($products as $product) {
            $in = $product->stocks->sum('quantity');
            $out = $product->outs->sum('quantity');

            $totalBuying = $product->stocks->sum(fn($stock) => $stock->quantity * $stock->buying_price);
            $totalSelling = $product->stocks->sum(fn($stock) => $stock->quantity * $stock->selling_price);

            $avgBuyingPrice = $in > 0 ? $totalBuying / $in : 0;
            $avgSellingPrice = $in > 0 ? $totalSelling / $in : 0;

            $profit = ($avgSellingPrice * $out) - ($avgBuyingPrice * $out);
            $totalProfit += $profit;
        }

        return view('product.report', compact('products', 'type', 'totalProfit'));
    }


    public function downloadProductPdf($type)
    {
        $queryDate = now();

        if ($type === 'monthly') {
            $products = Product::with([
                'stocks' => fn($q) => $q->whereMonth('date', $queryDate->month)->whereYear('date', $queryDate->year),
                'outs' => fn($q) => $q->whereMonth('date', $queryDate->month)->whereYear('date', $queryDate->year),
            ])->get();
        } elseif ($type === 'yearly') {
            $products = Product::with([
                'stocks' => fn($q) => $q->whereYear('date', $queryDate->year),
                'outs' => fn($q) => $q->whereYear('date', $queryDate->year),
            ])->get();
        } else {
            $products = Product::with([
                'stocks' => fn($q) => $q->whereDate('date', $queryDate->toDateString()),
                'outs' => fn($q) => $q->whereDate('date', $queryDate->toDateString()),
            ])->get();
        }

        $pdf = Pdf::loadView('product.report_pdf', compact('products', 'type'));
        return $pdf->stream('product_report_'.$type.'.pdf');
    }

}
