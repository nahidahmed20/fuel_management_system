<?php

namespace App\Http\Controllers;

use App\Models\Nozzle;
use App\Models\FuelOut;
use App\Models\FuelType;
use App\Models\FuelStock;
use Illuminate\Http\Request;

class FuelSellController extends Controller
{
    public function index()
    {
        $fuelSells = FuelOut::with(['fuelType', 'nozzle'])
            ->orderBy('id', 'desc')
            ->get();

        $fuelSummary = FuelOut::with('fuelType')
            ->selectRaw('fuel_type_id, SUM(quantity) as total_quantity, SUM(total_sell) as total_sell')
            ->groupBy('fuel_type_id')
            ->get();

        return view('fuel_sell.index', compact('fuelSells', 'fuelSummary'));
    }

    public function summary(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $fuelSells = FuelOut::with(['fuelType', 'nozzle'])
            ->orderBy('id', 'desc')
            ->get();

        // filtered summary
        $query = FuelOut::with('fuelType')
            ->selectRaw('fuel_type_id, SUM(quantity) as total_quantity, SUM(total_sell) as total_sell')
            ->groupBy('fuel_type_id');

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $fuelSummary = $query->get();

        return view('fuel_sell.index', compact('fuelSummary', 'startDate', 'endDate','fuelSells'));
    }


    public function create()
    {
        $fuelTypes = FuelType::all();
        return view('fuel_sell.create', compact('fuelTypes'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'nozzle_id' => 'required|exists:nozzles,id',
            'quantity' => 'required|numeric|min:0.01',
            'total_sell' => 'required|numeric|min:0',
            'total_buy' => 'nullable|numeric|min:0',
            'date' => 'required|date',
        ]);
        
        // dd($request->all());

        $fuelTypeId = $request->fuel_type_id;
        $sellQuantity = $request->quantity;

        $totalStock = FuelStock::where('fuel_type_id', $fuelTypeId)->sum('quantity');

        $totalSold = FuelOut::where('fuel_type_id', $fuelTypeId)->sum('quantity');

        $availableStock = $totalStock - $totalSold;

        if ($sellQuantity > $availableStock) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Stock is insufficient. Available stock for this fuel type is '.$availableStock.' liters.');
        }


        FuelOut::create([
            'fuel_type_id' => $fuelTypeId,
            'nozzle_id' => $request->nozzle_id,
            'quantity' => $sellQuantity,
            'total_sell' => $request->total_sell,
            'total_buy' => $request->total_buy,
            'date' => $request->date,
        ]);

        return redirect()->route('fuel.sell.index')->with('success', 'Fuel sell record added successfully.');
    }

    public function show($id)
    {
        //
    }

    // Edit view
    public function edit($id)
    {
        $fuelSell = FuelOut::findOrFail($id);
        $fuelTypes = FuelType::all();
        return view('fuel_sell.edit', compact('fuelSell', 'fuelTypes'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'nozzle_id' => 'required|exists:nozzles,id',
            'quantity' => 'required|numeric|min:0.01',
            'total_sell' => 'required|numeric|min:0',
            'total_buy' => 'nullable|numeric|min:0',
            'date' => 'required|date',
        ]);

        $fuelSell = FuelOut::findOrFail($id);

        $fuelTypeId = $request->fuel_type_id;
        $newQuantity = $request->quantity;

        $totalStock = FuelStock::where('fuel_type_id', $fuelTypeId)->sum('quantity');

        $totalSoldExcludingCurrent = FuelOut::where('fuel_type_id', $fuelTypeId)
            ->where('id', '!=', $id)
            ->sum('quantity');

        $availableStock = $totalStock - $totalSoldExcludingCurrent;

        if ($newQuantity > $availableStock) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity' => 'Stock is insufficient. Available stock for this fuel type is '.$availableStock.' liters.']);
        }

        $fuelSell->update([
            'fuel_type_id' => $fuelTypeId,
            'nozzle_id' => $request->nozzle_id,
            'quantity' => $newQuantity,
            'total_sell' => $request->total_sell,
            'total_buy' => $request->total_buy,
            'date' => $request->date,
        ]);

        return redirect()->route('fuel.sell.index')->with('success', 'Fuel sell record updated successfully.');
    }

    public function destroy($id)
    {
        $fuelSell = FuelOut::findOrFail($id);
        $fuelSell->delete();

        return redirect()->route('fuel.sell.index')->with('success', 'Fuel sell record deleted successfully.');
    }

    public function getLatestSellingPrice($fuel_type_id)
    {
        $latestStock = FuelStock::where('fuel_type_id', $fuel_type_id)
                        ->orderByDesc('id')
                        ->first();

        if ($latestStock) {
            return response()->json([
                'selling_price' => $latestStock->selling_price,
                'buying_price' => $latestStock->buying_price
            ]);
        }

        return response()->json([
            'selling_price' => 0,
            'buying_price' => 0
        ]);
    }

    public function getNozzlesByFuelType(Request $request)
    {
        $fuelTypeId = $request->fuel_type_id;

        $nozzles = Nozzle::where('fuel_type_id', $fuelTypeId)->get(['id', 'name']);

        return response()->json($nozzles);
    }

    public function getNozzleMeter($nozzle_id)
    {
        $nozzle = Nozzle::findOrFail($nozzle_id);

        return response()->json([
            'prev_meter' => $nozzle->previous_meter_reading ?? 0,
            'curr_meter' => $nozzle->current_meter_reading ?? 0
        ]);
    }
}
