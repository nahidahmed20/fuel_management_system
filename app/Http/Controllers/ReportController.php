<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Expense;
use App\Models\FuelOut;
use App\Models\Product;
use App\Models\Borrower;
use App\Models\Customer;
use App\Models\FuelType;
use App\Models\MobilOut;
use App\Models\FuelStock;
use App\Models\MobilStock;
use App\Models\ProductOut;
use App\Models\CustomerDue;
use App\Models\LoanPayment;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\CustomerDuePayment;

class ReportController extends Controller
{
    public function fuelStock(Request $request)
    {
        $fuelTypes = FuelType::all();

        $query = FuelStock::with('fuelType');

        if ($request->fuel_type_id) {
            $query->where('fuel_type_id', $request->fuel_type_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $fuelStocks = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.fuel_stock_table', compact('fuelStocks'))->render();
        }

        return view('reports.fuel_stock', compact('fuelTypes','fuelStocks'));
    }


    public function fuelSales(Request $request)
    {
        $fuelTypes = FuelType::all();

        $query = FuelOut::with(['fuelType', 'nozzle']); 

        if ($request->fuel_type_id) {
            $query->where('fuel_type_id', $request->fuel_type_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $fuelSales = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.fuel_sales_table', compact('fuelSales'))->render();
        }
        return view('reports.fuel_sales', compact('fuelSales', 'fuelTypes'));
    }

    public function mobileStock(Request $request)
    {
        $query = MobilStock::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $mobileStocks = $query->latest()->get();

        // AJAX হলে partial return
        if ($request->ajax()) {
            return view('reports.partials.mobile_stock_table', compact('mobileStocks'))->render();
        }

        // Normal page load
        return view('reports.mobile_stock', compact('mobileStocks'));
    }

    public function mobileSales(Request $request)
    {
        $query = MobilOut::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $mobileSales = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.mobile_sales_table', compact('mobileSales'))->render();
        }

        // Normal page load
        return view('reports.mobile_sales', compact('mobileSales'));
    }

    public function productStock(Request $request)
    {
        $products = Product::all();

        $query = ProductStock::with('product');

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $productStocks = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.product_stock_table', compact('productStocks'))->render();
        }

        return view('reports.product_stock', compact('productStocks','products'));
    }

    public function productSales(Request $request)
    {
        $products = Product::all();

        $query = ProductOut::with('product');

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $productSales = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.product_sales_table', compact('productSales'))->render();
        }

        return view('reports.product_sales', compact('productSales','products'));
    }

    public function customerDue(Request $request)
    {
        $customers = Customer::all();

        $query = CustomerDue::with('customer');

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('due_date', [$request->from_date, $request->to_date]);
        }

        $customerDues = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.customer_due_table', compact('customerDues'))->render();
        }

        return view('reports.customer_due', compact('customerDues','customers'));
    }

    public function customerPayment(Request $request)
    {
        $customers = Customer::all();

        $query = CustomerDuePayment::with('customer');

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('payment_date', [$request->from_date, $request->to_date]);
        }

        $customerPayments = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.customer_payment_table', compact('customerPayments'))->render();
        }

        return view('reports.customer_payment', compact('customerPayments','customers'));
    }

    public function loan(Request $request)
    {
        $borrowers = Borrower::all(); // Dropdown filter

        $query = Loan::with('borrower'); // eager load borrower

        // Filter by borrower
        if ($request->borrower_id) {
            $query->where('borrower_id', $request->borrower_id);
        }

        // Filter by date range
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('loan_date', [$request->from_date, $request->to_date]);
        }

        $loans = $query->latest()->get();

        // AJAX request
        if ($request->ajax()) {
            return view('reports.partials.loan_table', compact('loans'))->render();
        }

        return view('reports.loan_index', compact('loans', 'borrowers'));
    }

    public function loanPayment(Request $request)
    {
        $borrowers = Borrower::all(); // dropdown

        $query = LoanPayment::with('borrower');

        if ($request->borrower_id) {
            $query->where('borrower_id', $request->borrower_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('payment_date', [$request->from_date, $request->to_date]);
        }

        $payments = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.loan_payment_table', compact('payments'))->render();
        }

        return view('reports.loan_payment', compact('payments', 'borrowers'));
    }

    public function expense(Request $request)
    {
        $query = Expense::query();

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $expenses = $query->latest()->get();

        if ($request->ajax()) {
            return view('reports.partials.expense_table', compact('expenses'))->render();
        }

        return view('reports.expense_index', compact('expenses'));
    }
}

