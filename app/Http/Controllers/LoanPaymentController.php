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



    public function loanDueReportView($type)
    {
        $queryDate = Carbon::now();

        if ($type === 'monthly') {
            $dues = Loan::with('borrower')
                ->whereMonth('loan_date', $queryDate->month)
                ->whereYear('loan_date', $queryDate->year)
                ->get();
        } elseif ($type === 'yearly') {
            $dues = Loan::with('borrower')
                ->whereYear('loan_date', $queryDate->year)
                ->get();
        } else {
            $dues = Loan::with('borrower')
                ->whereDate('loan_date', $queryDate->toDateString())
                ->get();
        }

        $totalDue = $dues->sum('amount'); 

        return view('loan.report', compact('dues', 'type', 'totalDue'));
    }

    public function loanDueReportPdf($type)
    {
        $today = Carbon::now();
        
        $duesQuery = Loan::with('borrower');

        if ($type === 'monthly') {
            $duesQuery->whereMonth('loan_date', $today->month)
                    ->whereYear('loan_date', $today->year);
        } elseif ($type === 'yearly') {
            $duesQuery->whereYear('loan_date', $today->year);
        } else {
            $duesQuery->whereDate('loan_date', $today->toDateString()); 
        }

        $dues = $duesQuery->get();

        $totalDue = $dues->sum('due_amount');

        $pdf = Pdf::loadView('loan.pdf', compact('dues', 'type', 'totalDue'));
        return $pdf->stream("loan_due_report_{$type}.pdf");
    }


    public function loanPaymentReportView($type)
    {
        $queryDate = Carbon::now();

        if ($type === 'monthly') {
            $payments = LoanPayment::with('borrower')   
                ->whereMonth('payment_date', $queryDate->month)
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } elseif ($type === 'yearly') {
            $payments = LoanPayment::with('borrower')
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } else {
            $payments = LoanPayment::with('borrower')
                ->whereDate('payment_date', $queryDate->toDateString())
                ->get();
        }

        $totalPaid = $payments->sum('amount');

        return view('loan_payment.report', compact('payments', 'type', 'totalPaid'));
    }

    public function loanPaymentReportPdf($type)
    {
        $queryDate = Carbon::now();

        if ($type === 'monthly') {
            $payments = LoanPayment::with('borrower')   
                ->whereMonth('payment_date', $queryDate->month)
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } elseif ($type === 'yearly') {
            $payments = LoanPayment::with('borrower')
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } else {
            $payments = LoanPayment::with('borrower')   
                ->whereDate('payment_date', $queryDate->toDateString())
                ->get();
        }

        $totalPaid = $payments->sum('amount');

        $pdf = Pdf::loadView('loan_payment.pdf', compact('payments', 'type', 'totalPaid'));
        return $pdf->stream('loan_payment_report_' . $type . '.pdf');
    }

}
