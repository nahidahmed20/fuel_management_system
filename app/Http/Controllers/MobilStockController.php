<?php

namespace App\Http\Controllers;

use App\Models\FuelBuy;
use App\Models\MobilBuy;
use App\Models\MobilStock;
use Illuminate\Http\Request;

class MobilStockController extends Controller
{
    public function index()
    {
        $stocks = MobilStock::orderBy('id', 'desc')->get();

        return view('mobil.index', compact('stocks'));
    }

    public function create()
    {
        return view('mobil.create');
    }

    public function storeStock(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'quantity'      => 'required|numeric|min:0.1',
            'buying_price'  => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'date'          => 'required|date',
        ]);

        MobilStock::create($request->only('name', 'quantity', 'buying_price', 'selling_price', 'date'));

        $totalBuying  = $request->quantity*$request->buying_price;

        MobilBuy::create([
            'name'               => $request->name,
            'quantity'           => $request->quantity,
            'buying_price'       => $request->buying_price,
            'total_buying_price' => $totalBuying,
            'date'               => $request->date,
        ]);

        return redirect()->route('mobil.index')->with('success', 'Stock added.');
    }

    public function editStock($id)
    {
        $stock = MobilStock::find($id);
        return view('mobil.edit', compact('stock'));
    }

    public function updateStock(Request $request, MobilStock $stock)
    {
        $request->validate([
            'name'          => 'required',
            'quantity'      => 'required|numeric|min:0.1',
            'buying_price'  => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'date'          => 'required|date',
        ]);

        // Update MobilStock
        $stock->update($request->only('name', 'quantity', 'buying_price', 'selling_price', 'date'));

        // Calculate new total buying
        $totalBuying = $request->quantity * $request->buying_price;

        // Update FuelBuy entry
        $fuelBuy = MobilBuy::where('name', $stock->name)->latest()->first();

        if ($fuelBuy) {
            $fuelBuy->update([
                'name'         => $request->name,
                'quantity'           => $request->quantity,
                'buying_price'       => $request->buying_price,
                'total_buying_price' => $totalBuying,
                'date'               => $request->date,
            ]);
        } else {
            // Optional fallback: create if not exists
            MobilBuy::create([
                'name'               => $request->name,
                'quantity'           => $request->quantity,
                'buying_price'       => $request->buying_price,
                'total_buying_price' => $totalBuying,
                'date'               => $request->date,
            ]);
        }

        return redirect()->route('mobil.index')->with('success', 'Stock updated.');
    }


    public function deleteStock(MobilStock $stock)
    {
        $stock->delete();
        return redirect()->route('mobil.index')->with('success', 'Stock deleted.');
    }
}
