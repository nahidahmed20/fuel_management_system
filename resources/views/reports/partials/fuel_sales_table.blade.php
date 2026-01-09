<table class="table table-bordered mb-0" id="fuelSalesTable">
    <thead class="table-dark">
        <tr>
            <th class="text-center" style="color: white !important">#</th>
            <th style="color: white !important">Fuel</th>
            <th style="color: white !important">Nozzle</th>
            <th style="color: white !important">Quantity</th>
            <th style="color: white !important">Total Sell</th>
            <th style="color: white !important">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fuelSales as $key => $sale)
        <tr>
            <td class="text-center">{{ $key+1 }}</td>
            <td>{{ $sale->fuelType->name ?? '-' }}</td>
            <td>{{ $sale->nozzle->name ?? '-' }}</td>
            <td>{{ number_format($sale->quantity, 3) }}</td>
            <td>{{ number_format($sale->total_sell, 3) }} ৳</td>
            <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
