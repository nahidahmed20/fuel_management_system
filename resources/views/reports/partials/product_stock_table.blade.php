<div class="table-responsive">
    <table class="table table-bordered mb-0" id="productStockTable">
        <thead class="table-dark">
            <tr>
                <th class="text-center" style="color: white !important">#</th>
                <th style="color: white !important">Product</th>
                <th style="color: white !important">Quantity</th>
                <th style="color: white !important">Buying Price</th>
                <th style="color: white !important">Selling Price</th>
                <th style="color: white !important">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productStocks as $stock)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $stock->product->name }}</td>
                    <td>{{ number_format($stock->quantity, 3) }}</td>
                    <td>{{ number_format($stock->buying_price, 3) }} ৳</td>
                    <td>{{ number_format($stock->selling_price, 3) }} ৳</td>
                    <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Buying Price</th>
                <th>Selling Price</th>
                <th>Date</th>
            </tr>
        </tfoot>
    </table>
</div>
