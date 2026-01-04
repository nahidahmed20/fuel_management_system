{{-- invoice_content.blade.php --}}
<div style="padding: 20px; font-family: sans-serif;">
    <h3 style="text-align: center;">Profit Invoice</h3>
    <p style="text-align: center;">Date Range: {{ request('start_date') }} to {{ request('end_date') }}</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;" border="1" cellpadding="8">
        <tr>
            <th>Total Fuel Profit</th>
            <td>৳{{ number_format($totalFuelProfit, 2) }}</td>
        </tr>
        <tr>
            <th>Total Mobil Profit</th>
            <td>৳{{ number_format($mobilProfit, 2) }}</td>
        </tr>
        <tr>
            <th>Total Product Profit</th>
            <td>৳{{ number_format($totalProductProfit, 2) }}</td>
        </tr>
        <tr>
            <th>Total Expense</th>
            <td>৳{{ number_format($totalExpense, 2) }}</td>
        </tr>
        <tr style="font-weight: bold; background-color: #e6ffe6;">
            <th>Net Profit</th>
            <td>৳{{ number_format($totalNetProfit, 2) }}</td>
        </tr>
    </table>

    <br>
    <h4>Fuel Profit Breakdown</h4>
    <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Fuel</th>
                <th>Total Out</th>
                <th>Buy Total</th>
                <th>Sell Total</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profits as $data)
                <tr>
                    <td>{{ $data['fuel'] }}</td>
                    <td>{{ $data['total_out'] }}</td>
                    <td>৳{{ number_format($data['buy_total'], 2) }}</td>
                    <td>৳{{ number_format($data['purchase_total'], 2) }}</td>
                    <td>৳{{ number_format($data['profit'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <h4>Mobil Summary</h4>
    <p>Buy: ৳{{ number_format($mobilTotalBuy, 2) }}</p>
    <p>Sell: ৳{{ number_format($mobilTotalSell, 2) }}</p>
    <p><strong>Profit: ৳{{ number_format($mobilProfit, 2) }}</strong></p>

    <br>
    <h4>Product Profit Breakdown</h4>
    <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Product</th>
                <th>Buy Total</th>
                <th>Sell Total</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productProfits as $data)
                <tr>
                    <td>{{ $data['product'] }}</td>
                    <td>৳{{ number_format($data['buy_total'], 2) }}</td>
                    <td>৳{{ number_format($data['purchase_total'], 2) }}</td>
                    <td>৳{{ number_format($data['profit'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
