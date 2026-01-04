<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:customers,name',
            'mobile' => 'nullable|string|max:15|unique:customers,mobile',
            'address' => 'nullable|string|max:255',
        ]);


        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show($id)
    {
        $customer = Customer::with(['dues', 'duePayments'])->findOrFail($id);

        $totalDue = $customer->dues->sum('amount_due'); 
       
        $totalPaid = $customer->duePayments->sum('amount'); 
        $currentDue = $totalDue - $totalPaid; 

        return view('customer.show', compact('customer', 'totalDue', 'totalPaid', 'currentDue'));
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:customers,name,' . $customer->id,
            'mobile' => 'nullable|string|max:15|unique:customers,mobile,' . $customer->id,
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($request->only('name', 'mobile', 'address'));

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
