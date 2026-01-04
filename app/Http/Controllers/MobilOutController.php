<?php

namespace App\Http\Controllers;

use App\Models\MobilOut;
use App\Models\MobilStock;
use Illuminate\Http\Request;

class MobilOutController extends Controller
{
    public function indexOut()
    {
        $outs = MobilOut::orderByDesc('date')->get();
        return view('mobil_out.index', compact('outs'));
    }


    public function createOut()
    {
        $latestSellingPrice = MobilStock::latest('id')->value('selling_price');
        $latestBuyingPrice = MobilStock::latest('id')->value('buying_price');

        return view('mobil_out.create',compact('latestSellingPrice','latestBuyingPrice'));
    }

    public function storeOut(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'quantity'   => 'required|numeric|min:0.1',
            'total_buy'  => 'required|numeric|min:0.1',
            'total_sell' => 'required|numeric|min:0.1',
            'date'       => 'required|date',
        ]);
        $totalStock = MobilStock::sum('quantity');
        $totalOut = MobilOut::sum('quantity');
        $currentStock = $totalStock - $totalOut;

        if ($request->quantity > $currentStock) {
            return back()->with('error', 'Not enough stock. Available: ' . $currentStock . ' L');
        }

        MobilOut::create($request->only('name', 'quantity','total_buy', 'total_sell', 'date'));

        return redirect()->route('mobilOut.index')->with('success', 'Mobil out recorded successfully.');
    }

    public function editOut($id)
    {
        $latestStock = MobilStock::latest()->first();
        $out = MobilOut::findOrFail($id);
        $buyingPrice = $latestStock->buying_price;
        $sellingPrice = $latestStock->selling_price;
        
        return view('mobil_out.edit', compact('out','buyingPrice','sellingPrice'));
    }

    public function updateOut(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'quantity'    => 'required|numeric|min:0.1',
            'total_buy'   => 'required|numeric|min:0.1',
            'total_sell'  => 'required|numeric|min:0.1',
            'date'        => 'required|date',
        ]);

        $out = MobilOut::findOrFail($id);
        $outQty = $out->quantity;
        $newQty = $request->quantity;
         
        $totalStockQty = MobilStock::where('name', $request->name)->sum('quantity');
      
        $totalOutQty = MobilOut::where('name', $request->name)->sum('quantity');

        $availableStock = $totalStockQty + $outQty - $totalOutQty ;
        
        if ($newQty > $availableStock) {
            return back()->with('error', 'Stock is not enough to update the quantity.');
        }


        $out->update([
            'name'        => $request->name,
            'quantity'    => $newQty,
            'total_buy'   => $request->total_buy,
            'total_sell'  => $request->total_sell,
            'date'        => $request->date,
        ]);

        return redirect()->route('mobilOut.index')->with('success', 'Mobil Out updated successfully.');
    }


    public function destroyOut($id)
    {
        $out = MobilOut::findOrFail($id);
        $out->delete();

        return redirect()->route('mobilOut.index')->with('success', 'Mobil Sell record deleted successfully.');
    }

}
