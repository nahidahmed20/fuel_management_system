<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Account;
use App\Models\Expense;
use App\Models\FuelBuy;
use App\Models\FuelOut;
use App\Models\Product;
use App\Models\FuelType;
use App\Models\MobilBuy;
use App\Models\MobilOut;
use App\Models\FuelShort;
use App\Models\FuelStock;
use App\Models\MobilStock;
use App\Models\ProductBuy;
use App\Models\ProductOut;
use App\Models\CustomerDue;
use App\Models\LoanPayment;
use App\Models\CashWithdraw;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\CustomerDuePayment;

class ProfitController extends Controller
{
    

    public function profitOverview($type = 'today')
    {
        $today = Carbon::today();
        $queryDate = match ($type) {
            'today' => [$today->copy()->startOfDay(), $today->copy()->endOfDay()],
            default => [$today->copy()->startOfDay(), $today->copy()->endOfDay()],
        };

        $profits = [];
        $fuelTypes = FuelType::all();

        foreach ($fuelTypes as $fuel) {
            $fuelOuts = FuelOut::where('fuel_type_id', $fuel->id)
                        ->whereBetween('date', $queryDate)
                        ->get();

            $totalOut = $fuelOuts->sum('quantity');
            $totalPurchase = $fuelOuts->sum('total_sell');

            // ðŸ”§ Custom Calculation: quantity * buying_price
            $totalBuy = $fuelOuts->sum(function ($item) {
                return $item->quantity * $item->buying_price;
            });

            $latestStock = FuelStock::where('fuel_type_id', $fuel->id)
                        ->latest('id')->first();

            $buyPrice = $latestStock->buying_price ?? 0;
            $sellPrice = $latestStock->selling_price ?? 0;

            $profit = $totalPurchase - $totalBuy;

            $profits[] = [
                'fuel'           => $fuel->name,
                'total_out'      => $totalOut,
                'buy_price'      => $buyPrice,
                'sell_price'     => $sellPrice,
                'purchase_total' => $totalPurchase,
                'buy_total'      => $totalBuy,
                'profit'         => $profit,
            ];
        }

        // Mobil Profit
        $mobilOuts = MobilOut::whereBetween('date', $queryDate)->get();
        $mobilTotalBuy = $mobilOuts->sum(function ($item) {
            return $item->quantity * $item->buying_price;
        });
        $mobilTotalSell = $mobilOuts->sum('total_sell');
        $mobilProfit = $mobilTotalSell - $mobilTotalBuy;

        // Product Profits
        $productProfits = [];
        $products = Product::all();

        foreach ($products as $product) {
            $productOuts = ProductOut::where('product_id', $product->id)
                            ->whereBetween('date', $queryDate)
                            ->get();

            $totalBuy = $productOuts->sum(function ($item) {
                return $item->quantity * $item->buying_price;
            });

            $totalSell = $productOuts->sum('total_price');

            $productProfits[] = [
                'product'        => $product->name,
                'buy_total'      => $totalBuy,
                'purchase_total' => $totalSell,
                'profit'         => $totalSell - $totalBuy,
            ];
        }

        $totalExpense = Expense::whereBetween('date', $queryDate)->sum('amount');

        $totalFuelProfit = collect($profits)->sum('profit');
        $totalProductProfit = collect($productProfits)->sum('profit');

        // Fuel Short Profit Impact
        $fuelShorts = FuelShort::whereBetween('date', $queryDate)->get();

        $totalShortProfit = 0;
        $shortImpacts = [];

        foreach ($fuelShorts as $short) {
            $impact = $short->price;

            if ($short->short_type === '+') {
                $totalShortProfit += $impact;
            } elseif ($short->short_type === '-') {
                $totalShortProfit -= $impact;
            }

            $shortImpacts[] = [
                'fuel'       => $short->fuelType->name ?? 'N/A',
                'type'       => $short->short_type,
                'amount'     => $short->short_amount,
                'price'      => $short->price,
                'date'       => $short->date,
                'note'       => $short->note,
                'impact'     => $short->short_type === '+' ? $impact : -$impact,
            ];
        }

        $totalNetProfit = $totalFuelProfit + $mobilProfit + $totalProductProfit + $totalShortProfit - $totalExpense;

        return view('profit.index', compact(
            'profits',
            'mobilTotalBuy',
            'mobilTotalSell',
            'mobilProfit',
            'productProfits',
            'totalExpense',
            'totalFuelProfit',
            'totalProductProfit',
            'totalShortProfit',
            'shortImpacts',
            'totalNetProfit'
        ));

    }

    public function profitCustom(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();
        $queryDate = [$startDate, $endDate];
    
        /* =========================
           🔥 Fuel Profit (Correct)
        ==========================*/
        $profits   = [];
        $fuelTypes = FuelType::all();
    
        foreach ($fuelTypes as $fuel) {
    
            $fuelOuts = FuelOut::where('fuel_type_id', $fuel->id)
                ->whereBetween('date', $queryDate)
                ->get();
    
            $totalOut  = $fuelOuts->sum('quantity');
            $totalBuy  = $fuelOuts->sum('total_buy');
            $totalSell = $fuelOuts->sum('total_sell');
    
            $profits[] = [
                'fuel'           => $fuel->name,
                'total_out'      => $totalOut,
                'buy_total'      => $totalBuy,
                'purchase_total' => $totalSell,
                'profit'         => $totalSell - $totalBuy,
            ];
        }
    
        $totalFuelProfit = collect($profits)->sum('profit');
    
        /* =========================
           🛢 Mobil Profit
        ==========================*/
        $mobilOuts = MobilOut::whereBetween('date', $queryDate)->get();
        $mobilTotalBuy  = $mobilOuts->sum('total_buy');
        $mobilTotalSell = $mobilOuts->sum('total_sell');
        $mobilProfit    = $mobilTotalSell - $mobilTotalBuy;
    
        /* =========================
           📦 Product Profit
        ==========================*/
        $productProfits = [];
        $products = Product::all();
    
        foreach ($products as $product) {
    
            $productOuts = ProductOut::where('product_id', $product->id)
                ->whereBetween('date', $queryDate)
                ->get();
    
            $totalBuy  = $productOuts->sum('total_buy');
            $totalSell = $productOuts->sum('total_price');
    
            $productProfits[] = [
                'product'        => $product->name,
                'buy_total'      => $totalBuy,
                'purchase_total' => $totalSell,
                'profit'         => $totalSell - $totalBuy,
            ];
        }
    
        $totalProductProfit = collect($productProfits)->sum('profit');
    
        /* =========================
           ⚖ Fuel Short Impact
        ==========================*/
        $fuelShorts = FuelShort::whereBetween('date', $queryDate)->get();
    
        $totalPlus  = 0;
        $totalMinus = 0;
        $shortImpacts = [];
    
        foreach ($fuelShorts as $short) {
    
            if ($short->short_type === '+') {
                $totalPlus += $short->price;
            } else {
                $totalMinus += $short->price;
            }
    
            $shortImpacts[] = [
                'fuel'   => $short->fuelType->name ?? 'N/A',
                'type'   => $short->short_type,
                'amount' => $short->short_amount,
                'price'  => $short->price,
                'date'   => $short->date,
                'note'   => $short->note,
            ];
        }
    
        $totalShortProfit = $totalPlus - $totalMinus;
    
        /* =========================
           💸 Expense
        ==========================*/
        $totalExpense = Expense::whereBetween('date', $queryDate)->sum('amount');
    
        /* =========================
           📊 Net Profit
        ==========================*/
        $totalNetProfit =
            $totalFuelProfit +
            $mobilProfit +
            $totalProductProfit +
            $totalShortProfit -
            $totalExpense;
    
        return view('profit.index', compact(
            'profits',
            'mobilTotalBuy',
            'mobilTotalSell',
            'mobilProfit',
            'productProfits',
            'totalExpense',
            'totalFuelProfit',
            'totalProductProfit',
            'totalShortProfit',
            'shortImpacts',
            'totalNetProfit'
        ));
    }




    public function monthlyBalanceAndProfitOverview()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $label = $start->format('F Y');

        $totalProductValue = 0;
        $products = Product::all();
        foreach ($products as $product) {
            $stock = ProductStock::where('product_id', $product->id)->whereBetween('created_at', [$start, $end])->sum('quantity');
            $out = ProductOut::where('product_id', $product->id)->whereBetween('date', [$start, $end])->sum('quantity');
            $currentQty = $stock - $out;

            $latest = ProductStock::where('product_id', $product->id)->whereBetween('created_at', [$start, $end])->latest()->first();
            $latestPrice = $latest?->selling_price ?? 0;

            $totalProductValue += $currentQty * $latestPrice;
        }

        $totalFuelValue = 0;
        $fuelTypes = FuelType::all();
        foreach ($fuelTypes as $fuel) {
            $stock = FuelStock::where('fuel_type_id', $fuel->id)->whereBetween('created_at', [$start, $end])->sum('quantity');
            $out = FuelOut::where('fuel_type_id', $fuel->id)->whereBetween('date', [$start, $end])->sum('quantity');
            $currentQty = $stock - $out;

            $latest = FuelStock::where('fuel_type_id', $fuel->id)->whereBetween('created_at', [$start, $end])->latest()->first();
            $latestPrice = $latest?->selling_price ?? 0;

            $totalFuelValue += $currentQty * $latestPrice;
        }

        $mobilStock = MobilStock::whereBetween('created_at', [$start, $end])->sum('quantity');
        $mobilOut = MobilOut::whereBetween('date', [$start, $end])->sum('quantity');
        $mobilQty = $mobilStock - $mobilOut;
        $latestMobil = MobilStock::whereBetween('created_at', [$start, $end])->latest()->first();
        $mobilPrice = $latestMobil?->selling_price ?? 0;
        $totalMobilValue = $mobilQty * $mobilPrice;

        $customerDue = CustomerDue::whereBetween('due_date', [$start, $end])->sum('amount_due');
        $customerPayment = CustomerDuePayment::whereBetween('payment_date', [$start, $end])->sum('amount');
        $netCustomerDue = $customerDue - $customerPayment;

        $loanAmount = Loan::whereBetween('loan_date', [$start, $end])->sum('amount');
        $loanPayment = LoanPayment::whereBetween('payment_date', [$start, $end])->sum('amount');
        $netLoanDue = $loanAmount - $loanPayment;

        $productOutRevenue = ProductOut::whereBetween('date', [$start, $end])->sum('total_price');
        $fuelOutRevenue = FuelOut::whereBetween('date', [$start, $end])->sum('total_sell');
        $mobilOutRevenue = MobilOut::whereBetween('date', [$start, $end])->sum('total_sell');
        $totalOutRevenue = $productOutRevenue + $fuelOutRevenue + $mobilOutRevenue;

        $totalDeposit = Account::whereBetween('created_at', [$start, $end])->sum('amount');
        $totalWithdraw = CashWithdraw::whereBetween('created_at', [$start, $end])->sum('amount');

        $totalFuelShortValue = 0;
        $fuelShorts = FuelShort::whereBetween('date', [$start, $end])->get();
        foreach ($fuelTypes as $fuelType) {
            $plusShort = $fuelShorts->where('fuel_type_id', $fuelType->id)->where('short_type', '+')->sum('short_amount');
            $minusShort = $fuelShorts->where('fuel_type_id', $fuelType->id)->where('short_type', '-')->sum('short_amount');
            $netShortAmount = $plusShort - $minusShort;

            $latestStock = FuelStock::where('fuel_type_id', $fuelType->id)->whereBetween('created_at', [$start, $end])->latest()->first();
            $sellingPrice = $latestStock?->selling_price ?? 0;

            $totalFuelShortValue += $netShortAmount * $sellingPrice;
        }

        $currentBalance = $totalProductValue + $totalFuelValue + $totalMobilValue + $netCustomerDue - $netLoanDue + $totalOutRevenue + $totalDeposit - $totalWithdraw;

        $fuelProfit = 0;
        foreach ($fuelTypes as $fuel) {
            $fuelOuts = FuelOut::where('fuel_type_id', $fuel->id)->whereBetween('date', [$start, $end])->get();

            $totalPurchase = $fuelOuts->sum('total_sell');
            $totalBuy = $fuelOuts->sum(fn($item) => $item->quantity * $item->buying_price);

            $fuelProfit += $totalPurchase - $totalBuy;
        }

        $mobilOuts = MobilOut::whereBetween('date', [$start, $end])->get();
        $mobilProfit = $mobilOuts->sum('total_sell') - $mobilOuts->sum(fn($item) => $item->quantity * $item->buying_price);

        $productProfit = 0;
        foreach ($products as $product) {
            $outs = ProductOut::where('product_id', $product->id)->whereBetween('date', [$start, $end])->get();

            $totalSell = $outs->sum('total_price');
            $totalBuy = $outs->sum(fn($item) => $item->quantity * $item->buying_price);
            $productProfit += $totalSell - $totalBuy;
        }

        $shortImpact = 0;
        foreach ($fuelShorts as $short) {
            $impact = $short->price;
            $shortImpact += $short->short_type === '+' ? $impact : -$impact;
        }

        $totalExpense = Expense::whereBetween('date', [$start, $end])->sum('amount');

        $netProfit = $fuelProfit + $mobilProfit + $productProfit + $shortImpact - $totalExpense;

        return view('profit.blance', compact(
            'label',
            'currentBalance',
            'netProfit',
            'totalProductValue',
            'totalFuelValue',
            'totalMobilValue',
            'productOutRevenue',
            'fuelOutRevenue',
            'mobilOutRevenue',
            'totalOutRevenue',
            'netCustomerDue',
            'netLoanDue',
            'totalDeposit',
            'totalWithdraw'
        ));
    }




    public function currentCash()
    {
        $totalDeposit = Account::sum('amount');
        $fuelSell = FuelOut::sum('total_sell');
        $mobilSell = MobilOut::sum('total_sell');
        $productSell = ProductOut::sum('total_price');
        $customerPayment = CustomerDuePayment::sum('amount');
        $loan = Loan::sum('amount');

        $totalWithdraw = CashWithdraw::sum('amount');
        $fuelBuy = FuelBuy::sum('total_buying_price');
        $mobilBuy = MobilBuy::sum('total_buying_price');
        $productBuy = ProductBuy::sum('total_buying_price');
        $loanPayment = LoanPayment::sum('amount');
        $customerDue = CustomerDue::sum('amount_due');

        $totalAddCash = $totalDeposit + $fuelSell + $mobilSell + $productSell + $customerPayment+$loan;
        $totalSubCash = $totalWithdraw + $fuelBuy + $mobilBuy + $productBuy + $loanPayment + $customerDue;
        $currentCash = $totalAddCash - $totalSubCash;

        return view('profit.cash', compact(
            'totalDeposit',
            'fuelSell',
           'mobilSell',
            'productSell',
            'customerPayment',
            'totalWithdraw',
            'fuelBuy',
           'mobilBuy',
            'productBuy',
            'loanPayment',
            'customerDue',
            'totalAddCash',
            'totalSubCash',
            'currentCash',
            'loan'
        ));
    }

}
