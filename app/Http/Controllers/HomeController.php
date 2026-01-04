<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\FuelOut;
use App\Models\FuelType;
use App\Models\MobilOut;
use App\Models\MobilStock;
use App\Models\ProductOut;
use App\Models\CustomerDue;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function home()
    {
        $today = now()->toDateString();

        // Available product stock হিসাব
        $productStockIn = ProductStock::sum('quantity');
        $productStockOut = ProductOut::sum('quantity');
        $availableProductStock = $productStockIn - $productStockOut;

        $mobilStockIn = MobilStock::sum('quantity');
        $mobilStockOut = MobilOut::sum('quantity');
        $availableMobilStock = $mobilStockIn - $mobilStockOut;

        return view('home.index', [
            'fuelSaleToday' => FuelOut::whereDate('date', $today)->sum('total_sell'),
            'productSaleToday' => ProductOut::whereDate('date', $today)->sum('total_price'),
            'productStock' => $availableProductStock, 
            'expenseToday' => Expense::whereDate('date', $today)->sum('amount'),
            'dueToday' => CustomerDue::whereDate('due_date', $today)->sum('amount_due'),

            'fuelChartLabels' => FuelOut::selectRaw('DATE(date) as date')
                ->whereBetween('created_at', [now()->subDays(6), now()])
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('date'),

            'petrolData' => $this->getFuelData(FuelType::where('name', 'Petrol')->value('id')),
            'dieselData' => $this->getFuelData(FuelType::where('name', 'Diesel')->value('id')),
            'octenData' => $this->getFuelData(FuelType::where('name', 'Octen')->value('id')),

            'latestFuelSales' => FuelOut::latest()->take(5)->get(),

            'fuelStocks' => FuelType::all()->map(function ($fuel) {
                $stockIn = $fuel->stocks()->sum('quantity');
                $stockOut = $fuel->outs()->sum('quantity');
                $fuel->available_stock = $stockIn - $stockOut;
                return $fuel;
            }),
            'mobilStock' => $availableMobilStock,
        ]);
    }



    private function getFuelData($fuelTypeId)
    {
        if (!$fuelTypeId) return [];

        return FuelOut::where('fuel_type_id', $fuelTypeId)
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->selectRaw('DATE(date) as date, SUM(quantity) as qty')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('qty');
    }

}
