<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanController extends Controller
{
   public function index()
    {
        $loans  = Loan::orderBy('id', 'desc')->get();

        return view('loan.index', compact('loans'));
    }


    public function create()
    {
        $borrowers = Borrower::all();
        return view('loan.create', compact('borrowers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrower_id' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'loan_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        Loan::create($request->all());

        return redirect()->route('loans.index')->with('success', 'Loan added successfully.');
    }

    public function show($id)
    {
        $borrower = Borrower::with(['loans', 'payments'])->findOrFail($id);

        $totalLoan = $borrower->loans->sum('amount'); // All loan amounts
        $totalPaid = $borrower->payments->sum('amount'); // Total paid amount
        $currentDue = $totalLoan - $totalPaid; // Remaining due

        return view('loan.show', compact('borrower', 'totalLoan', 'totalPaid', 'currentDue'));
    }



    public function edit(Loan $loan)
    {
        $borrowers = Borrower::all();
        return view('loan.edit', compact('loan', 'borrowers'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'amount' => 'required|numeric|min:0.01',
            'loan_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $loan->update($request->all());

        return redirect()->route('loans.index')->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted successfully.');
    }

    public function downloadTodayPdf()
    {
        $today = Loan::with('borrower')->whereDate('loan_date', Carbon::today())->get();
        $total = $today->sum('amount');
        $date = Carbon::now()->format('d M, Y');

        $pdf = Pdf::loadView('loan.pdf', compact('today', 'total', 'date'))->setPaper('a4', 'portrait');
        return $pdf->download('today_loans.pdf');
    }

    public function downloadMonthPdf()
    {
        $month = Loan::with('borrower')->whereMonth('loan_date', now()->month)->whereYear('loan_date', now()->year)->get();
        $total = $month->sum('amount');
        $date = now()->format('F Y');

        $pdf = Pdf::loadView('loan.pdf', ['today' => $month, 'total' => $total, 'date' => $date])->setPaper('a4');
        return $pdf->download('monthly_loans.pdf');
    }

    public function downloadYearPdf()
    {
        $year = Loan::with('borrower')->whereYear('loan_date', now()->year)->get();
        $total = $year->sum('amount');
        $date = now()->format('Y');

        $pdf = Pdf::loadView('loan.pdf', ['today' => $year, 'total' => $total, 'date' => $date])->setPaper('a4');
        return $pdf->download('yearly_loans.pdf');
    }
}
