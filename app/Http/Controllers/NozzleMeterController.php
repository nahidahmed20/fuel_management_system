<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\Nozzle;
use App\Models\FuelType;
use App\Models\NozzleMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class NozzleMeterController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nozzle_id' => 'required|exists:nozzles,id',
            'curr_meter' => 'required|numeric|min:0',
        ]);

        $latestMeter = NozzleMeter::where('nozzle_id', $request->nozzle_id)
                            ->latest()
                            ->first();

        $prev = $latestMeter ? $latestMeter->curr_meter : $request->prev_meter;
        $curr = $request->curr_meter;
        $sold = $curr - $prev;

        NozzleMeter::create([
            'nozzle_id' => $request->nozzle_id,
            'prev_meter' => $prev,
            'curr_meter' => $curr,
            'sold_quantity' => $sold,
            'date' => $request->date,
        ]);

        return redirect()->route('fuel.show')->with('success', 'Nozzle meter updated successfully.');
    }

    public function getNozzlesByFuel($fuelTypeId)
    {
        $nozzles = Nozzle::where('fuel_type_id', $fuelTypeId)->get(['id', 'name']);
        return response()->json($nozzles);
    }

    public function getPrevMeter($nozzleId)
    {
        $meter = NozzleMeter::where('nozzle_id', $nozzleId)->latest()->first();
        return response()->json([
            'prev_meter' => $meter ? number_format($meter->curr_meter, 2) : '0.00'
        ]);
    }

    public function getNozzleMeter($nozzleId)
    {
        $meter = NozzleMeter::where('nozzle_id', $nozzleId)->latest()->first();

        return response()->json([
            'prev_meter' => $meter?->prev_meter ?? 0,
            'curr_meter' => $meter?->curr_meter ?? 0,
        ]);
    }


    // Show edit form
    public function edit($id)
    {
        $meter = NozzleMeter::findOrFail($id);
        $fuelTypes = FuelType::all();
        return view('fuel.nozzle_meters_edit', compact('meter', 'fuelTypes'));
    }

    // Update existing record
    public function update(Request $request, $id)
    {
        $request->validate([
            'nozzle_id1' => 'required|exists:nozzles,id',
            'curr_meter' => 'required|numeric|min:0',
        ]);

        $meter = NozzleMeter::findOrFail($id);

        $prevMeter = $meter->prev_meter;
        $currMeter = $request->curr_meter;
        $soldQty = $currMeter - $prevMeter;

        $meter->update([
            'nozzle_id'     => $request->nozzle_id1,
            'curr_meter'    => $currMeter,
            'sold_quantity' => $soldQty,
            'date'          => $request->date,
        ]);

        return redirect()->route('fuel.show')->with('success', 'Nozzle meter updated successfully.');
    }

    // Delete record
    public function destroy($id)
    {
        $meter = NozzleMeter::findOrFail($id);
        $meter->delete();
        return redirect()->back()->with('success', 'Nozzle meter deleted successfully.');
    }


}
