<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Borrower;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanPaymentController extends Controller
{

    public function index()
    {
        $loanPayments = LoanPayment::with('borrower')->get();

        $borrowerLoans = Borrower::with(['loans', 'payments'])->get()->map(function ($borrower) {
            $totalLoan = $borrower->loans->sum('amount');
            $totalPaid = $borrower->payments->sum('amount');
            $due = $totalLoan - $totalPaid;

            return [
                'name' => $borrower->name,
                'totalLoan' => $totalLoan,
                'totalPaid' => $totalPaid,
                'due' => $due,
            ];
        });

        return view('loan_payment.index', compact('loanPayments', 'borrowerLoans'));
    }

    public function create()
    {
        $borrower_id = request('borrower_id');
        
        return view('loan_payment.create', compact('borrower_id'));
    }

    public function store(Request $request)
    {   
        
        $request->validate([
            'borrower_id'  => 'required|exists:borrowers,id',
            'amount'       => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'note'         => 'nullable|string',
        ]);
        
        LoanPayment::create($request->all());

        return redirect()->route('loan-payments.index')->with('success', 'Payment added successfully.');
    }
    
    public function show($id)
    {
        $borrower = Borrower::with(['loans', 'payments'])->findOrFail($id);

        $totalLoanAmount = $borrower->loans->sum('amount');     
       
        $totalPaidAmount = $borrower->payments->sum('amount'); 
     
        $currentDue = $totalLoanAmount - $totalPaidAmount; 

        return view('borrower.show', compact('borrower', 'totalLoanAmount', 'totalPaidAmount', 'currentDue'));
    }

    public function edit($id)
    {
        $loanPayment = LoanPayment::with('borrower')->find($id);
        return view('loan_payment.edit', compact('loanPayment'));
    }

    public function update(Request $request, LoanPayment $loanPayment)
    {
        $request->validate([
            'loan_id' => 'required|exists:borrowers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $loanPayment->update($request->all());

        return redirect()->route('loan-payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(LoanPayment $loanPayment)
    {
        $loanPayment->delete();
        return redirect()->route('loan-payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function getTotalBorrow(Request $request)
    {
        $borrowerId = $request->borrower_id;

        if (!$borrowerId) {
            return response()->json(['total_borrow' => 0]);
        }

        $totalBorrow = Loan::where('borrower_id', $borrowerId)->sum('amount');
        $totalPaid = LoanPayment::where('borrower_id', $borrowerId)->sum('amount');

        $borrowBalance = max($totalBorrow - $totalPaid, 0);
       
        return response()->json(['total_borrow' => $borrowBalance]);
    }

}
