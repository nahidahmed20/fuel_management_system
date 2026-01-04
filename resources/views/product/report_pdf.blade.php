<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Report PDF</title>
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

    <h2>Surma Filling Station — {{ ucfirst($type) }} Product Report</h2>
    <p>Report Date: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>

    {{-- Product Summary Table --}}
    <h4 class="section-title">Product Summary</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Total In</th>
                <th>Total Out</th>
                <th>Current Stock</th>
                <th>Profit Taka</th>
            </tr>
        </thead>
        <tbody>
            @php $totalProfit = 0; @endphp
            @foreach($products as $index => $product)
                @php
                    $in = $product->stocks->sum('quantity');
                    $out = $product->outs->sum('quantity');

                    $totalBuying = $product->stocks->sum(fn($stock) => $stock->quantity * $stock->buying_price);
                    $totalSelling = $product->stocks->sum(fn($stock) => $stock->quantity * $stock->selling_price);

                    $avgBuyingPrice = $in > 0 ? $totalBuying / $in : 0;
                    $avgSellingPrice = $in > 0 ? $totalSelling / $in : 0;

                    $profit = ($avgSellingPrice * $out) - ($avgBuyingPrice * $out);
                    $totalProfit += $profit;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($in, 3) }}</td>
                    <td>{{ number_format($out, 3) }}</td>
                    <td style="color: {{ ($in - $out) <= 0 ? 'red' : 'green' }}; font-weight: bold;">
                        {{ number_format($in - $out, 3) }}
                    </td>
                    <td style="color: {{ $profit < 0 ? 'red' : 'green' }}; font-weight: bold;">
                        {{ number_format($profit, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">Total Profit:</td>
                <td style="font-weight: bold;">{{ number_format($totalProfit, 2) }} T</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
