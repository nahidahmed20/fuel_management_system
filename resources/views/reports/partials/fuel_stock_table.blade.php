<table class="table table-bordered mb-0" id="fuelStockTable">
    <thead class="table-dark">
        <tr>
            <th class="text-center" style="color: white !important">#</th>
            <th style="color: white !important">Fuel</th>
            <th style="color: white !important">Quantity</th>
            <th style="color: white !important">Buying Price</th>
            <th style="color: white !important">Selling Price</th>
            <th style="color: white !important">Truck No</th>
            <th style="color: white !important">Company</th>
            <th style="color: white !important">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fuelStocks as $key => $stock)
        <tr>
            <td class="text-center">{{ $key+1 }}</td>
            <td>{{ $stock->fuelType->name ?? '-' }}</td>
            <td>{{ number_format($stock->quantity, 3) }}</td>
            <td>{{ number_format($stock->buying_price, 3) }} ৳</td>
            <td>{{ number_format($stock->selling_price, 3) }} ৳</td>
            <td>{{ $stock->truck_number }}</td>
            <td>{{ $stock->company_name }}</td>
            <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M, Y') }}</td>
        </tr>
        
        @endforeach
    </tbody>
</table>
