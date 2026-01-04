<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Report PDF</title>
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

    <h2>Surma Filling Station — {{ $title }}</h2>
    <p>Report Date: {{ $dateText }}</p>

    <h4 class="section-title">Expense Summary</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Amount (Taka)</th>
                <th>Date</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @foreach ($expenses as $index => $expense)
                @php $totalAmount += $expense->amount; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>{{ number_format($expense->amount, 3) }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</td>
                    <td>{{ $expense->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        @if($expenses->count() > 0)
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">Total:</td>
                <td style="font-weight: bold;">{{ number_format($totalAmount, 3) }} Taka</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

</body>
</html>
