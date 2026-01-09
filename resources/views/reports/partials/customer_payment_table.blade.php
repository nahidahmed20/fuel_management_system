<div class="table-responsive">
    <table class="table table-bordered mb-0" id="customerPaymentTable">
        <thead class="table-dark">
            <tr>
                <th class="text-center" style="color: white !important">#</th>
                <th style="color: white !important">Customer</th>
                <th style="color: white !important">Amount Paid</th>
                <th style="color: white !important">Payment Date</th>
                <th style="color: white !important">Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customerPayments as $payment)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $payment->customer->name ?? '' }}</td>
                    <td>{{ number_format($payment->amount, 3) }} ৳</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                    <td>{{ $payment->note ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th>Customer</th>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Note</th>
            </tr>
        </tfoot>
    </table>
</div>
