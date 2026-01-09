<table class="table table-bordered" id="loanPaymentTable">
    <thead class="table-dark">
        <tr>
            <th style="color: white !important">#</th>
            <th style="color: white !important">Borrower</th>
            <th style="color: white !important">Amount</th>
            <th style="color: white !important">Payment Date</th>
            <th style="color: white !important">Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $payment->borrower->name }}</td>
            <td>{{ number_format($payment->amount,3) }} ৳</td>
            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
            <td>{{ $payment->note ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
