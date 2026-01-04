<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\CustomerDue;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CustomerDuePayment;

class CustomerDuePaymentController extends Controller
{
    public function index(Request $request)
    {
        $customerId = $request->customer_id;

        $payments = CustomerDuePayment::when($customerId, function($query) use ($customerId) {
                        return $query->where('customer_id', $customerId);
                    })
                    ->with('customer')
                    ->latest()
                    ->get();

        $customer = $customerId ? Customer::findOrFail($customerId) : null;

        return view('customer-due-payment.index', compact('payments', 'customer'));
    }




    // Show form to create new payment
    public function create(Request $request)
    {
        $customerId = $request->customer_id;
        $customer = null;
        $totalDue = 0;

        if ($customerId) {
            $customer = Customer::findOrFail($customerId);

            $totalDue = $customer->dues()->sum('amount_due') - $customer->duePayments()->sum('amount');
        }

        return view('customer-due-payment.create', compact('customer', 'totalDue'));
    }


    // Store new payment
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        CustomerDuePayment::create($request->all());

        return redirect()->route('customer-due-payments.index', ['customer_id' => $request->customer_id])
                         ->with('success', 'Payment added successfully.');
    }

    // Show edit form
    public function edit(CustomerDuePayment $customerDuePayment)
    {
        $customer = Customer::findOrFail($customerDuePayment->customer_id);
        return view('customer-due-payment.edit', compact('customerDuePayment', 'customer'));
    }

    // Update payment
    public function update(Request $request, CustomerDuePayment $customerDuePayment)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        $customerDuePayment->update($request->only('payment_date', 'amount', 'note'));

        return redirect()->route('customer-due-payments.index', ['customer_id' => $customerDuePayment->customer_id])
                         ->with('success', 'Payment updated successfully.');
    }

    // Delete payment
    public function destroy(CustomerDuePayment $customerDuePayment)
    {
        $customerDuePayment->delete();

        return redirect()->route('customer-due-payments.index', ['customer_id' => $customerDuePayment->customer_id])
                         ->with('success', 'Payment deleted successfully.');
    }


    public function customerDueReportView($type)
    {
        $queryDate = Carbon::now();

        if ($type === 'monthly') {
            $dues = CustomerDue::with('customer')
                ->whereMonth('due_date', $queryDate->month)
                ->whereYear('due_date', $queryDate->year)
                ->get();
        } elseif ($type === 'yearly') {
            $dues = CustomerDue::with('customer')
                ->whereYear('due_date', $queryDate->year)
                ->get();
        } else {
            $dues = CustomerDue::with('customer')
                ->whereDate('due_date', $queryDate->toDateString())
                ->get();
        }

        $totalDue = $dues->sum('amount_due'); 

        return view('customer_due.report', compact('dues', 'type', 'totalDue'));
    }

    public function customerDueReportPdf($type)
    {
        $today = Carbon::now();
        
        $duesQuery = CustomerDue::with('customer');

        if ($type === 'monthly') {
            $duesQuery->whereMonth('due_date', $today->month)
                    ->whereYear('due_date', $today->year);
        } elseif ($type === 'yearly') {
            $duesQuery->whereYear('due_date', $today->year);
        } else {
            $duesQuery->whereDate('due_date', $today->toDateString());
        }

        $dues = $duesQuery->get();

        $totalDue = $dues->sum('due_amount');

        $pdf = Pdf::loadView('customer_due.pdf', compact('dues', 'type', 'totalDue'));
        return $pdf->stream("customer_due_report_{$type}.pdf");
    }


    public function customerPaymentReportView($type)
    {
        $queryDate = Carbon::now();

        if ($type === 'monthly') {
            $payments = CustomerDuePayment::with('customer')
                ->whereMonth('payment_date', $queryDate->month)
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } elseif ($type === 'yearly') {
            $payments = CustomerDuePayment::with('customer')
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } else {
            $payments = CustomerDuePayment::with('customer')
                ->whereDate('payment_date', $queryDate->toDateString())
                ->get();
        }

        $totalPaid = $payments->sum('amount');

        return view('customer-due-payment.report', compact('payments', 'type', 'totalPaid'));
    }

    public function customerPaymentReportPdf($type)
    {
        $queryDate = Carbon::now();

        if ($type === 'monthly') {
            $payments = CustomerDuePayment::with('customer')
                ->whereMonth('payment_date', $queryDate->month)
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } elseif ($type === 'yearly') {
            $payments = CustomerDuePayment::with('customer')
                ->whereYear('payment_date', $queryDate->year)
                ->get();
        } else {
            $payments = CustomerDuePayment::with('customer')
                ->whereDate('payment_date', $queryDate->toDateString())
                ->get();
        }

        $totalPaid = $payments->sum('amount');

        $pdf = Pdf::loadView('customer-due-payment.pdf', compact('payments', 'type', 'totalPaid'));
        return $pdf->stream('customer_payment_report_' . $type . '.pdf');
    }


}
