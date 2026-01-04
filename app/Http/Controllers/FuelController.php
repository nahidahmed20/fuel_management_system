<?php

namespace App\Http\Controllers;

use App\Models\Nozzle;
use App\Models\FuelOut;
use App\Models\FuelType;
use App\Models\FuelStock;
use App\Models\NozzleMeter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class FuelController extends Controller
{
    
    public function reportView($type)
    {
        $today = Carbon::today();
        $queryDate = match ($type) {
            'today' => [$today, $today->copy()->endOfDay()],
            'month' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
            'year' => [$today->copy()->startOfYear(), $today->copy()->endOfYear()],
            default => [$today, $today],
        };

        $nozzleMeters = NozzleMeter::with('nozzle.fuelType')
            ->whereBetween('date', $queryDate)
            ->get();

        $fuelStocks = FuelStock::with('fuelType')
            ->whereBetween('date', $queryDate)
            ->get();

        $fuelOuts = FuelOut::with('fuelType', 'nozzle')
            ->whereBetween('date', $queryDate)
            ->get();

        $fuelTypes = FuelType::all();

        // Profit summary calculation
        $profitSummary = [];
        $totalPurchaseSum = 0;
        $totalSellSum = 0;
        $totalProfitSum = 0;

        foreach ($fuelTypes as $typee) {
            $outs = $fuelOuts->where('fuel_type_id', $typee->id);
            $ins = $fuelStocks->where('fuel_type_id', $typee->id);

            $totalQtyOut = $outs->sum('quantity');
            $totalQtyIn = $ins->sum('quantity');

            // Latest stock
            $latestStock = $ins->sortByDesc('date')->first();

            if (!$latestStock) {
                $latestStock = FuelStock::where('fuel_type_id', $typee->id)
                    ->where('date', '<', $queryDate[0])
                    ->orderByDesc('date')
                    ->first();
            }

            $buyingPrice = $latestStock->buying_price ?? 0;
            $sellingPrice = $latestStock->selling_price ?? 0;

            $totalPurchase = $totalQtyOut * $buyingPrice;
            $totalSell = $totalQtyOut * $sellingPrice;
            $profit = $totalSell - $totalPurchase;

            // Store individual fuel type summary
            $profitSummary[$typee->name] = [
                'total_in'       => $totalQtyIn,
                'total_out'      => $totalQtyOut,
                'buying_price'   => $buyingPrice,
                'selling_price'  => $sellingPrice,
                'total_purchase' => $totalPurchase,
                'total_sell'     => $totalSell,
                'profit'         => $profit,
            ];

            // Add to total summary
            $totalPurchaseSum += $totalPurchase;
            $totalSellSum += $totalSell;
            $totalProfitSum += $profit;
        }

        return view('fuel.report', compact(
            'type',
            'fuelStocks',
            'fuelOuts',
            'profitSummary',
            'fuelTypes',
            'nozzleMeters',
            'totalPurchaseSum',
            'totalSellSum',
            'totalProfitSum'
        ));
    }

    public function downloadPdf($type)
    {
        $today = Carbon::today();
        $startDate = $today;
        $endDate = $today;

        if ($type == 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($type == 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        }

        $fuelStocks = FuelStock::with('fuelType')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $fuelOuts = FuelOut::with('fuelType', 'nozzle')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $nozzleMeters = NozzleMeter::with('nozzle.fuelType')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $fuelTypes = FuelType::all();
        $profitSummary = [];

        $totalPurchaseSum = 0;
        $totalSellSum = 0;
        $totalProfitSum = 0;

        foreach ($fuelTypes as $fuelType) {
            $typeId = $fuelType->id;
            $typeName = $fuelType->name;

            $outs = $fuelOuts->where('fuel_type_id', $typeId);
            $ins = $fuelStocks->where('fuel_type_id', $typeId);

            $totalQtyOut = $outs->sum('quantity');
            $totalQtyIn = $ins->sum('quantity');

            $latestStock = $ins->sortByDesc('date')->first();

            if (!$latestStock) {
                $latestStock = FuelStock::where('fuel_type_id', $typeId)
                    ->where('date', '<', $startDate)
                    ->orderByDesc('date')
                    ->first();
            }

            $buyingPrice = $latestStock->buying_price ?? 0;
            $sellingPrice = $latestStock->selling_price ?? 0;

            $totalPurchase = $totalQtyOut * $buyingPrice;
            $totalSell = $totalQtyOut * $sellingPrice;
            $profit = $totalSell - $totalPurchase;

            $profitSummary[$typeName] = [
                'total_in'       => $totalQtyIn,
                'total_out'      => $totalQtyOut,
                'buying_price'   => $buyingPrice,
                'selling_price'  => $sellingPrice,
                'total_purchase' => $totalPurchase,
                'total_sell'     => $totalSell,
                'profit'         => $profit,
            ];

            // Add to grand totals
            $totalPurchaseSum += $totalPurchase;
            $totalSellSum += $totalSell;
            $totalProfitSum += $profit;
        }

        $pdf = Pdf::loadView('fuel.report_pdf', compact(
            'fuelStocks',
            'fuelOuts',
            'profitSummary',
            'nozzleMeters',
            'type',
            'totalPurchaseSum',
            'totalSellSum',
            'totalProfitSum'
        ));

        return $pdf->download("fuel_report_{$type}.pdf");
    }

}

