<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mobil Report PDF</title>
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

    <h2>Surma Filling Station — {{ ucfirst($type) }} Mobil Report</h2>
    <p>Report Date: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>


    {{-- Stock Entries --}}
    <h4 class="section-title">Mobil Stock Entries</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Quantity (L)</th>
                <th>Buy Price</th>
                <th>Sell Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
            <tr>
                <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M Y') }}</td>
                <td>{{ number_format($stock->quantity, 3) }}</td>
                <td>{{ number_format($stock->buying_price, 2) }}</td>
                <td>{{ number_format($stock->selling_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Out Entries --}}
    <h4 class="section-title">Mobil Out Records</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Quantity (L)</th>
                <th>Total Buy</th>
                <th>Total Sell</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outs as $out)
            <tr>
                <td>{{ \Carbon\Carbon::parse($out->date)->format('d M Y') }}</td>
                <td>{{ number_format($out->quantity, 3) }}</td>
                <td>{{ number_format($out->total_buy, 2) }}</td>
                <td>{{ number_format($out->total_sell, 2) }}</td>
                <td style="color: green; font-weight: bold;">
                    {{ number_format($out->total_sell - $out->total_buy, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
