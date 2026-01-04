<?php

namespace App\Http\Controllers;

use App\Models\Nozzle;
use App\Models\FuelType;
use App\Models\NozzleMeter;
use Illuminate\Http\Request;

class NozzleController extends Controller
{
    public function index()
    {
        $nozzles = Nozzle::with('fuelType')->orderBy('id', 'desc')->get();
        return view('nozzle.index', compact('nozzles'));
    }

    public function create()
    {
        $fuelTypes = FuelType::all();
        return view('nozzle.create', compact('fuelTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'fuel_type_id' => 'required|exists:fuel_types,id',
        ]);

        Nozzle::create($request->only('name', 'fuel_type_id'));

        return redirect()->route('nozzle.index')->with('success', 'Nozzle created successfully.');
    }

    public function edit($id)
    {
        $nozzle = Nozzle::findOrFail($id);
        $fuelTypes = FuelType::all();
        return view('nozzle.edit', compact('nozzle', 'fuelTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'fuel_type_id' => 'required|exists:fuel_types,id',
        ]);

        $nozzle = Nozzle::findOrFail($id);
        $nozzle->update($request->only('name', 'fuel_type_id'));

        return redirect()->route('nozzle.index')->with('success', 'Nozzle updated successfully.');
    }

    public function destroy($id)
    {
        $stock = Nozzle::findOrFail($id);
        $stock->delete();

        return redirect()->back()->with('success', 'Nozzle deleted successfully.');
    }

    public function nozzleMeterCreate(Request $request)
    {
        $nozzles = Nozzle::all();
        return view('nozzle.nozzle_meter_create', compact('nozzles'));
    }
    

    public function NozzleMeterList()
    {
        $meters = NozzleMeter::orderBy('id', 'desc')->get();
        return view('nozzle.nozzle_meter_index', compact('meters'));
    }

    public function nozzleMeterStore(Request $request)
    {
        $request->validate([
            'nozzle_id'    => 'required|exists:nozzles,id',
            'prev_meter'   => 'required|numeric|min:0',
            'curr_meter'   => 'required|numeric|min:0|gte:prev_meter',
            'date'         => 'required|date',
        ]);

        $sold_quantity = $request->curr_meter - $request->prev_meter;

        NozzleMeter::create([
            'nozzle_id'     => $request->nozzle_id,
            'prev_meter'    => $request->prev_meter,
            'curr_meter'    => $request->curr_meter,
            'sold_quantity' => $sold_quantity,
            'date'          => $request->date,
        ]);

        return redirect()->route('nozzle.meter.index')->with('success', 'Nozzle meter added successfully.');
    }

    public function nozzleMeterEdit($id)
    {
        $meter = NozzleMeter::findOrFail($id);

        $fuelTypeId = $meter->nozzle->fuelType->id ?? null;

        if ($fuelTypeId) {
            $nozzles = Nozzle::where('fuel_type_id', $fuelTypeId)->get();
        } else {
            
            $nozzles = Nozzle::all();
        }

        return view('nozzle.nozzle_meter_edit', compact('meter', 'nozzles'));
    }


    public function nozzleMeterUpdate(Request $request, $id)
    {
        $request->validate([
            'curr_meter' => 'required|numeric|min:0',
            'nozzle_id'  => 'required',
            'date'       => 'required|date',
        ]);

        $meter = NozzleMeter::findOrFail($id);

        if ($request->curr_meter < $meter->prev_meter) {
            return back()->withErrors(['curr_meter' => 'Current meter must be greater than or equal to previous meter.'])->withInput();
        }

        $soldQuantity = $request->curr_meter - $meter->prev_meter;
        $meter->nozzle_id      = $request->nozzle_id;
        $meter->curr_meter     = $request->curr_meter;
        $meter->sold_quantity  = $soldQuantity;
        $meter->date           = $request->date;
        $meter->updated_at     = now();
        $meter->save();

        return redirect()->route('nozzle.meter.index')->with('success', 'Nozzle meter updated successfully.');
    }
    
    public function nozzleMeterDestroy($id)
    {
        $meter = NozzleMeter::findOrFail($id);
        $meter->delete();
        
        return redirect()->route('nozzle.meter.index')->with('success', 'Nozzle meter delete successfully.');
    }


    public function getLatestMeter($id)
    {
        $latest = NozzleMeter::where('nozzle_id', $id)->orderByDesc('id')->first();
    // dd($latest);
        return response()->json([
            'curr_meter' => $latest ? $latest->curr_meter : 0
        ]);
    }

}

