<?php

namespace App\Http\Controllers;

use App\Models\MobilOut;
use App\Models\MobilStock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MobilController extends Controller
{

    public function reportView($type)
    {
        $today = now()->toDateString();
        $month = now()->month;
        $year = now()->year;

        // Initialize query builders
        $stockQuery = MobilStock::query();
        $outQuery = MobilOut::query();

        // Filter by type
        if ($type === 'today') {
            $stockQuery->whereDate('date', $today);
            $outQuery->whereDate('date', $today);
        } elseif ($type === 'month') {
            $stockQuery->whereMonth('date', $month)->whereYear('date', $year);
            $outQuery->whereMonth('date', $month)->whereYear('date', $year);
        } elseif ($type === 'year') {
            $stockQuery->whereYear('date', $year);
            $outQuery->whereYear('date', $year);
        }

        // Get records
        $stocks = $stockQuery->orderBy('date', 'desc')->get();
        $outs = $outQuery->orderBy('date', 'desc')->get();

        // Summary calculations
        $totalStock = $stocks->sum('quantity');
        $totalOut = $outs->sum('quantity');
        $currentStock = $totalStock - $totalOut;

        // Latest price for profit calculation
        $latestStock = MobilStock::latest('date')->first();
        $buyingPrice = $latestStock?->buying_price ?? 0;
        $sellingPrice = $latestStock?->selling_price ?? 0;

        // Profit calculation based on type
        $profit = ($sellingPrice * $totalOut) - ($buyingPrice * $totalOut);

        return view('mobil.report', [
            'type'          => $type,
            'stocks'        => $stocks,
            'outs'          => $outs,
            'totalStock'    => $totalStock,
            'totalOut'      => $totalOut,
            'currentStock'  => $currentStock,
            'buyingPrice'   => $buyingPrice,
            'sellingPrice'  => $sellingPrice,
            'profitToday'   => $profit,
        ]);
    }




    public function download($type)
    {
        if ($type === 'today') {
            $date = now()->toDateString();
            $stocks = MobilStock::whereDate('date', $date)->get();
            $outs = MobilOut::whereDate('date', $date)->get();
        } elseif ($type === 'month') {
            $stocks = MobilStock::whereMonth('date', now()->month)->whereYear('date', now()->year)->get();
            $outs = MobilOut::whereMonth('date', now()->month)->whereYear('date', now()->year)->get();
        } elseif ($type === 'year') {
            $stocks = MobilStock::whereYear('date', now()->year)->get();
            $outs = MobilOut::whereYear('date', now()->year)->get();
        } else {
            abort(404); 
        }

        $totalStock = $stocks->sum('quantity');
        $totalOut = $outs->sum('quantity');
        $currentStock = $totalStock - $totalOut;

        $profitToday = 1000;  
        $profitMonth = 25000; 
        $profitYear = 300000; 

        $pdf = PDF::loadView('mobil.report_pdf', [
            'stocks' => $stocks,
            'outs' => $outs,
            'totalStock' => $totalStock,
            'totalOut' => $totalOut,
            'currentStock' => $currentStock,
            'profitToday' => $profitToday,
            'profitMonth' => $profitMonth,
            'profitYear' => $profitYear,
            'type' => $type,
        ]);

        return $pdf->download("mobil_{$type}_report_" . now()->format('Ymd') . ".pdf");
    }

}
