<?php

namespace App\Http\Controllers;

use App\Models\FuelBuy;
use App\Models\FuelType;
use App\Models\FuelStock;
use App\Models\NozzleMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\FuelOut;

class FuelStockController extends Controller
{
    public function index()
    {
        $fuelStocks = FuelStock::orderByDesc('id')->get();
        $fuelTypes = FuelType::all(); 

        $currentStock = [];

        foreach ($fuelTypes as $type) {
            // Total stock from fuel_stocks table
            $totalStock = FuelStock::where('fuel_type_id', $type->id)->sum('quantity');

            // Total fuel out from fuel_outs table
            $totalOut = FuelOut::where('fuel_type_id', $type->id)->sum('quantity');

            // Available stock = stock - out
            $currentStock[$type->id] = $totalStock - $totalOut;
        }

        return view('fuel_stock.index', compact('fuelStocks', 'fuelTypes', 'currentStock'));
    }

    public function create()
    {
        $fuelTypes = FuelType::all();

        return view('fuel_stock.create', compact('fuelTypes'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'fuel_type_id'   => 'required|exists:fuel_types,id',
            'quantity'       => 'required|numeric',
            'buying_price'   => 'required|numeric',
            'selling_price'  => 'required|numeric',
            'truck_number'   => 'required|string|max:255',
            'company_name'   => 'required|string|max:255',
            'date'           => 'required|date',
        ]);

        // Store in database
        $fuelStock =FuelStock::create([
            'fuel_type_id'   => $request->fuel_type_id,
            'quantity'       => $request->quantity,
            'buying_price'   => $request->buying_price,
            'selling_price'  => $request->selling_price,
            'truck_number'   => $request->truck_number,
            'company_name'   => $request->company_name,
            'date'           => $request->date,
        ]);

        $totalBuying  = $request->quantity*$request->buying_price;

        FuelBuy::create([
            'fuel_stock_id'      => $fuelStock->id, 
            'fuel_type_id'       => $request->fuel_type_id,
            'quantity'           => $request->quantity,
            'buying_price'       => $request->buying_price,
            'total_buying_price' => $totalBuying,
            'date'               => $request->date,
        ]);


        return redirect()->route('fuel.stock.index')->with('success', 'Fuel stock added successfully.');
    }


    public function edit($id)
    {
        $fuelStock = FuelStock::find($id);
        $fuelTypes = FuelType::all();

        return view('fuel_stock.edit', compact('fuelStock', 'fuelTypes'));
    }

    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'fuel_type_id'   => 'required|exists:fuel_types,id',
            'quantity'       => 'required|numeric',
            'buying_price'   => 'required|numeric',
            'selling_price'  => 'required|numeric',
            'truck_number'   => 'required|string|max:255',
            'company_name'   => 'required|string|max:255',
            'date'           => 'required|date',
        ]);

        // Find the FuelStock record
        $fuelStock = FuelStock::findOrFail($id);

        // Update FuelStock fields
        $fuelStock->update([
            'fuel_type_id'   => $request->fuel_type_id,
            'quantity'       => $request->quantity,
            'buying_price'   => $request->buying_price,
            'selling_price'  => $request->selling_price,
            'truck_number'   => $request->truck_number,
            'company_name'   => $request->company_name,
            'date'           => $request->date,
        ]);

        // Calculate total buying
        $totalBuying = $request->quantity * $request->buying_price;

        // Update related FuelBuy record
        $fuelBuy = FuelBuy::where('fuel_stock_id', $fuelStock->id)->first();

        if ($fuelBuy) {
            $fuelBuy->update([
                'fuel_type_id'       => $request->fuel_type_id,
                'quantity'           => $request->quantity,
                'buying_price'       => $request->buying_price,
                'total_buying_price' => $totalBuying,
                'date'               => $request->date,
            ]);
        }

        return redirect()->route('fuel.stock.index')->with('success', 'Fuel stock updated successfully.');
    }


    public function destroy($id)
    {
        $fuelStock = FuelStock::find($id);
        $fuelStock->delete();

        return redirect()->route('fuel.stock.index')->with('success', 'Fuel stock deleted successfully.');
    }
}
