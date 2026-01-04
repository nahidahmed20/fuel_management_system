<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuelType;
use Flasher\Laravel\Facade\Flasher;

class FuelTypeController extends Controller
{
    public function index()
    {
        $fuelTypes = FuelType::orderBy('id','desc')->get();
        return view('fuel_type.index', compact('fuelTypes'));
    }

    public function create()
    {
        return view('fuel_type.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:fuel_types,name'
        ]);

        FuelType::create([
            'name' => $request->name,
        ]);
        Flasher::addSuccess('Fuel Type Added Successfully', ['position' => 'bottom-right']);

        return redirect()->route('fuel-type.index');
    }

    public function edit($id)
    {
        $fuelType = FuelType::findOrFail($id);
        return view('fuel_type.edit', compact('fuelType'));
    }

    public function update(Request $request, $id)
    {
        $fuelType = FuelType::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:fuel_types,name,' . $fuelType->id,
        ]);
        
        $fuelType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('fuel-type.index')->with('success', 'Fuel type updated successfully!');
    }

    public function destroy($id)
    {
        $fuelType = FuelType::findOrFail($id);
        $fuelType->delete();

        return redirect()->route('fuel-type.index')->with('success', 'Fuel type deleted successfully!');
    }
}
