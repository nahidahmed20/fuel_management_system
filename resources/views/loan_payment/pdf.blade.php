<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Payment Report PDF</title>
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

    <h2>Surma Filling Station — {{ ucfirst($type) }} Loan Payment Report</h2>
    <p>Report Date: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>

    <h4 class="section-title">Loan Payment Summary</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Borrower Name</th>
                <th>Mobile</th>
                <th>Payment Date</th>
                <th>Amount T</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPaid = 0; @endphp
            @foreach($payments as $index => $payment)
                @php $totalPaid += $payment->amount; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payment->borrower->name ?? 'N/A' }}</td>
                    <td>{{ $payment->borrower->mobile ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->note ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total Paid:</td>
                <td colspan="2" style="font-weight: bold;">{{ number_format($totalPaid, 2) }} T</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
