<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Due Report PDF</title>
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

    <h2>Surma Filling Station — {{ ucfirst($type) }} Customer Due Report</h2>
    <p>Report Date: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>

    <h4 class="section-title">Customer Due Summary</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Mobile</th>
                <th>Due Date</th>
                <th>Total Due</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDue = 0; @endphp
            @foreach($dues as $index => $due)
                @php $totalDue += $due->due_amount; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $due->customer->name }}</td>
                    <td>{{ $due->customer->mobile }}</td>
                    <td>{{ \Carbon\Carbon::parse($due->due_date)->format('d M Y') }}</td>
                    <td style="color: {{ $due->due_amount <= 0 ? 'green' : 'red' }}; font-weight: bold;">
                        {{ number_format($due->due_amount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total Due:</td>
                <td style="font-weight: bold;">{{ number_format($totalDue, 2) }} T</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
