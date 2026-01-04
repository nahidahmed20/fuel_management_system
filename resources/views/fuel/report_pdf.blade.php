<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fuel Report PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        p {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #999;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .section-title {
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 16px;
            color: #1d3557;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <h2>Surma Filling Station — {{ ucfirst($type) }} Fuel Report</h2>
    <p>Report Date: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>

    {{-- Profit Summary --}}
    <h4 class="section-title">Profit Summary</h4>
    <table>
        <thead>
            <tr>
                <th>Fuel Type</th>
                <th>Total In (L)</th>
                <th>Total Out (L)</th>
                <th>Buy Price</th>
                <th>Sell Price</th>
                <th>Total Purchase</th>
                <th>Total Sell</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profitSummary as $fuel => $data)
            <tr>
                <td>{{ ucfirst($fuel) }}</td>
                <td>{{ number_format($data['total_in'] ?? 0, 3) }}</td>
                <td>{{ number_format($data['total_out'] ?? 0, 3) }}</td>
                <td>{{ number_format($data['buying_price'] ?? 0, 3) }}</td>
                <td>{{ number_format($data['selling_price'] ?? 0, 3) }}</td>
                <td>{{ number_format($data['total_purchase'] ?? 0, 3) }}</td>
                <td>{{ number_format($data['total_sell'] ?? 0, 3) }}</td>
                <td style="color: green; font-weight: bold;">
                    {{ number_format($data['profit'] ?? 0, 3) }}
                </td>
            </tr>
            @endforeach
            <tr class="table-success fw-bold">
                <td colspan="5" class="text-end">Total</td>
                <td>{{ number_format($totalPurchaseSum ?? 0, 3) }}</td>
                <td>{{ number_format($totalSellSum ?? 0, 3) }}</td>
                <td class="text-success">{{ number_format($totalProfitSum ?? 0, 3) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Fuel Stock Entries --}}
    <h4 class="section-title">Fuel Stock Entries</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Fuel</th>
                <th>Quantity (L)</th>
                <th>Buy Price</th>
                <th>Sell Price</th>
                <th>Truck No.</th>
                <th>Company</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fuelStocks as $stock)
            <tr>
                <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M Y') }}</td>
                <td>{{ ucfirst($stock->fuelType->name) }}</td>
                <td>{{ number_format($stock->quantity, 3) }}</td>
                <td>{{ number_format($stock->buying_price, 3) }}</td>
                <td>{{ number_format($stock->selling_price, 3) }}</td>
                <td>{{ $stock->truck_number }}</td>
                <td>{{ $stock->company_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Fuel Out Entries --}}
    <h4 class="section-title">Fuel Out Entries</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Fuel</th>
                <th>Nozzle</th>
                <th>Quantity (L)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fuelOuts as $out)
            <tr>
                <td>{{ \Carbon\Carbon::parse($out->date)->format('d M Y') }}</td>
                <td>{{ ucfirst($out->fuelType->name) }}</td>
                <td>{{ $out->nozzle->name }}</td>
                <td>{{ number_format($out->quantity, 3) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ✅ Nozzle Meter Entries --}}
    <h4 class="section-title">Nozzle Meter Entries</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Fuel Type</th>
                <th>Nozzle</th>
                <th>Previous Meter</th>
                <th>Current Meter</th>
                <th>Difference (L)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nozzleMeters as $meter)
            <tr>
                <td>{{ \Carbon\Carbon::parse($meter->date)->format('d M Y') }}</td>
                <td>{{ ucfirst($meter->nozzle->fuelType->name ?? '-') }}</td>
                <td>{{ $meter->nozzle->name ?? '-' }}</td>
                <td>{{ number_format($meter->prev_meter, 3) }}</td>
                <td>{{ number_format($meter->curr_meter, 3) }}</td>
                <td>{{ number_format($meter->curr_meter - $meter->prev_meter, 3) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
