<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\CustomerDue;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CustomerDuePayment;


class CustomerDueController extends Controller
{
    // Show all dues
    public function index()
    {
        $customers = CustomerDue::with('customer')->latest()->get();

        $totalDue = CustomerDue::sum('amount_due'); 
        $totalPayment = CustomerDuePayment::sum('amount');
        $currentDue = $totalDue - $totalPayment;

        return view('customer_due.index', compact('customers','totalDue', 'totalPayment', 'currentDue'));
    }



    public function filterByDate(Request $request)
    {
        // Validate date input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // Filtered customers (optional: show all customers with dues in this range)
        $customers = CustomerDue::with('customer')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->latest()
            ->get();

        // Total Due in the date range
        $totalDue = CustomerDue::whereBetween('due_date', [$startDate, $endDate])
            ->sum('amount_due');

        // Total Payment in the date range
        $totalPayment = CustomerDuePayment::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        // Current Due
        $currentDue = $totalDue - $totalPayment;

        return view('customer_due.index', compact('customers','totalDue', 'totalPayment', 'currentDue', 'startDate','endDate'));
    }


    // Show form to create due
    public function create()
    {
        $customers = Customer::all();
        return view('customer_due.create', compact('customers'));
    }

    // Store due
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount_due' => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
        ]);

        CustomerDue::create($request->all());

        return redirect()->route('customer_due.index')->with('success', 'Customer due added successfully.');
    }

    public function edit($id)
    {
        $due = CustomerDue::findOrFail($id);
        $customers = Customer::all();
        if (!$due->date) {
            $due->date = now(); 
        }

        return view('customer_due.edit', compact('due', 'customers'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount_due' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $due = CustomerDue::findOrFail($request->id);
        $due->update($request->all());

        return redirect()->route('customer_due.index')->with('success', 'Customer due updated successfully.');
    }

    public function destroy($id)
    {
        $due = CustomerDue::findOrFail($id);
        $due->delete();

        return redirect()->route('customer_due.index')->with('success', 'Customer due deleted successfully.');
    }

    public function downloadTodayDuePDF()
    {
        $today = CustomerDue::with('customer')->whereDate('due_date', Carbon::today())->get();
        $total = $today->sum('amount_due');
        $date = Carbon::today()->format('d F, Y');

        $pdf = Pdf::loadView('customer_due.pdf', compact('today', 'total', 'date'));
        return $pdf->download('customer-due-today.pdf');
    }

    public function downloadMonthDuePDF()
    {
        $month = CustomerDue::with('customer')->whereMonth('due_date', Carbon::now()->month)->get();
        $total = $month->sum('amount_due');
        $date = Carbon::now()->format('F, Y');

        $pdf = Pdf::loadView('customer_due.pdf', compact('month', 'total', 'date'));
        return $pdf->download('customer-due-month.pdf');
    }

    public function downloadYearDuePDF()
    {
        $year = CustomerDue::with('customer')->whereYear('due_date', Carbon::now()->year)->get();
        $total = $year->sum('amount_due');
        $date = Carbon::now()->format('Y');

        $pdf = Pdf::loadView('customer_due.pdf', compact('year', 'total', 'date'));
        return $pdf->download('customer-due-year.pdf');
    }

    public function getTotalDue(Request $request)
    {
        $customerId = $request->customer_id;

        if (!$customerId) {
            return response()->json(['total_due' => 0]);
        }

        // মোট due from customer_due table
        $totalDue = CustomerDue::where('customer_id', $customerId)->sum('amount_due');

        // মোট paid from customer_due_payments table
        $totalPaid = CustomerDuePayment::where('customer_id', $customerId)->sum('amount');

        // মোট বাকি due (due - paid)
        $dueBalance = max($totalDue - $totalPaid, 0);

        return response()->json(['total_due' => $dueBalance]);
    }

}
