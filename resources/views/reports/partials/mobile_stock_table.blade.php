<table class="table table-bordered" id="mobileStockTable">
    <thead class="table-dark">
        <tr>
            <th class="text-center" style="color: white !important">#</th>
            <th style="color: white !important">Name</th>
            <th style="color: white !important">Quantity</th>
            <th style="color: white !important">Buying Price</th>
            <th style="color: white !important">Selling Price</th>
            <th style="color: white !important">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mobileStocks as $stock)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $stock->name }}</td>
                <td>{{ number_format($stock->quantity, 3) }}</td>
                <td>{{ $stock->buying_price ? number_format($stock->buying_price, 3) : '-' }} ৳</td>
                <td>{{ $stock->selling_price ? number_format($stock->selling_price, 3) : '-' }} ৳</td>
                <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M, Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
