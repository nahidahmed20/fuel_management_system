<table class="table table-bordered" id="mobileSalesTable">
    <thead class="table-dark">
        <tr>
            <th class="text-center" style="color: white !important">#</th>
            <th style="color: white !important">Name</th>
            <th style="color: white !important">Quantity</th>
            <th style="color: white !important">Total Sell</th>
            <th style="color: white !important">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mobileSales as $sale)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $sale->name }}</td>
                <td>{{ number_format($sale->quantity, 3) }}</td>
                <td>{{ number_format($sale->total_sell, 3) }} ৳</td>
                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
