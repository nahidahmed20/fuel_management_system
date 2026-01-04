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

    public function expenseReport(Request $request)
    {
        $type = $request->query('type', 'today');

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($startDate && $endDate) {
            $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();
        } else {
            if ($type === 'month') {
                $expenses = Expense::whereYear('date', now()->year)
                                ->whereMonth('date', now()->month)
                                ->get();
            } elseif ($type === 'year') {
                $expenses = Expense::whereYear('date', now()->year)->get();
            } else { // today
                $expenses = Expense::whereDate('date', now()->toDateString())->get();
            }
        }

        return view('expense.report', compact('expenses', 'type'));
    }


    // Generate PDF for the given report type
    public function expenseReportPdf(Request $request, $type)
    {
        $query = Expense::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
            $title = "Expense Report from " . \Carbon\Carbon::parse($request->start_date)->format('d M, Y') .
                    " to " . \Carbon\Carbon::parse($request->end_date)->format('d M, Y');
            $dateText = $title;
        } else {
            if ($type === 'month') {
                $query->whereYear('date', now()->year)->whereMonth('date', now()->month);
                $title = "Monthly Expense Report";
                $dateText = now()->format('F Y');
            } elseif ($type === 'year') {
                $query->whereYear('date', now()->year);
                $title = "Yearly Expense Report";
                $dateText = now()->format('Y');
            } else {
                $query->whereDate('date', now()->toDateString());
                $title = "Today's Expense Report";
                $dateText = now()->format('d M, Y');
            }
        }

        $expenses = $query->get();

        $pdf = PDF::loadView('expense.report-pdf', compact('expenses', 'title', 'dateText'));

        return $pdf->stream("expense_report_{$type}_" . now()->format('Ymd') . ".pdf");
    }


}
