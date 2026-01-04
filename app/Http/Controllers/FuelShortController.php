<?php

namespace App\Http\Controllers;

use App\Models\FuelType;
use App\Models\FuelShort;
use Illuminate\Http\Request;

class FuelShortController extends Controller
{
    public function index()
    {
        $fuelShorts = FuelShort::orderByDesc('id')->with('fuelType')->get();
        
        return view('fuel-short.index', compact('fuelShorts'));
    }
    public function create()
    {
        $fuelTypes = FuelType::all();
        return view('fuel-short.create', compact('fuelTypes'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'fuel_type_id' => 'required',
            // 'short_amount' => 'required',
            'short_type'   => 'required',
            'price'        => 'required',
            'date'         => 'required',
            'note'         => 'nullable',
        ]);

        FuelShort::create([
            'fuel_type_id' => $request->fuel_type_id,
            'short_type'   => $request->short_type,
            // 'short_amount' => $request->short_amount,
            'price'        => $request->price,
            'date'         => $request->date,
            'note'         => $request->note,
        ]);

        return redirect()->route('fuel.short.index')->with('success', 'Fuel short record added successfully.');
    }
    public function show($id)
    {
        // code to show fuel shortage data
        }
    // Edit
    public function edit($id)
    {
        $fuelShort = FuelShort::findOrFail($id);
        $fuelTypes = FuelType::all();
        return view('fuel-short.edit', compact('fuelShort', 'fuelTypes'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'fuel_type_id' => 'required',
            'short_type'   => 'required',
            // 'short_amount' => 'required|numeric|min:0',
            'price'        => 'required',
            'date'         => 'required',
            'note'         => 'nullable|string',
        ]);

        $fuelShort = FuelShort::findOrFail($id);
        $fuelShort->update([
            'fuel_type_id' => $request->fuel_type_id,
            'short_type'   => $request->short_type,
            // 'short_amount' => $request->short_amount,
            'price'        => $request->price,
            'date'         => $request->date,
            'note'         => $request->note,
        ]);

        return redirect()->route('fuel.short.index')->with('success', 'Fuel short updated successfully.');
    }

    // Delete
    public function destroy($id)
    {
        $fuelShort = FuelShort::findOrFail($id);
        $fuelShort->delete();

        return back()->with('success', 'Fuel short deleted successfully.');
    }
}
