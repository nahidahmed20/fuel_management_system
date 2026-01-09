<div class="table-responsive">
    <table class="table table-bordered mb-0" id="productSalesTable">
        <thead class="table-dark">
            <tr>
                <th class="text-center" style="color: white !important">#</th>
                <th style="color: white !important">Product</th>
                <th style="color: white !important">Quantity</th>
                <th style="color: white !important">Total Price</th>
                <th style="color: white !important">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productSales as $sale)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $sale->product->name }}</td>
                    <td>{{ number_format($sale->quantity, 3) }}</td>
                    <td>{{ number_format($sale->total_price, 3) }} ৳</td>
                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
        </tfoot>
    </table>
</div>
