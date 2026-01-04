<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    public function index()
    {
        $borrowers = Borrower::latest()->get();
        return view('borrower.index', compact('borrowers'));
    }

    public function create()
    {
        return view('borrower.create');
    }

    public function show(Borrower $borrower)
    {
        return view('borrower.show', compact('borrower'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255|unique:borrowers,name',
            'mobile' => 'nullable|string|max:20|unique:borrowers,mobile',
            'address' => 'nullable|string|max:255',
        ]);
        
        Borrower::create($request->all());

        return redirect()->route('borrowers.index')->with('success', 'Borrower added successfully.');
    }

    public function edit(Borrower $borrower)
    {
        return view('borrower.edit', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:borrowers,name,'. $borrower->id,
            'mobile' => 'nullable|string|max:20|unique:borrowers,mobile,'. $borrower->id,
            'address' => 'nullable|string|max:255',
        ]);

        $borrower->update($request->all());

        return redirect()->route('borrowers.index')->with('success', 'Borrower updated successfully.');
    }

    public function destroy(Borrower $borrower)
    {
        $borrower->delete();
        return redirect()->route('borrowers.index')->with('success', 'Borrower deleted.');
    }

    public function paymentForm(Request $request)
    {
        $borrowerId = $request->query('borrower_id');
        $borrower = Borrower::with(['loans','payments'])->findOrFail($borrowerId);
        $totalLoanAmount = $borrower->loans->sum('amount');
        $totalPaidAmount = $borrower->payments->sum('amount');
        $currentDue = $totalLoanAmount - $totalPaidAmount;

        return view('borrower.show', compact('borrower', 'totalLoanAmount', 'totalPaidAmount', 'currentDue'));
    }
}
