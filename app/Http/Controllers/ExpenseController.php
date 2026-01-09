<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('expense.index', compact('expenses'));
    }

    public function create()
    {
        return view('expense.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|max:255',
            'amount'   => 'required|numeric|min:0.01',
            'date'     => 'required|date',
            'note'     => 'nullable|string'
        ]);

        Expense::create($request->all());

        return redirect()->route('expense.index')->with('success', 'Expense added successfully.');
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expense.edit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'nullable|string|max:255',
            'amount'   => 'required|numeric|min:0.01',
            'date'     => 'required|date',
            'note'     => 'nullable|string'
        ]);

        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return redirect()->route('expense.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return back()->with('success', 'Expense deleted.');
    }

}
