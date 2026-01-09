<table class="table table-bordered" id="loanTable">
    <thead class="table-dark">
        <tr>
            <th style="color: white !important">#</th>
            <th style="color: white !important">Borrower</th>
            <th style="color: white !important">Amount</th>
            <th style="color: white !important">Loan Date</th>
            <th style="color: white !important">Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loans as $loan)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $loan->borrower->name }}</td>
            <td>{{ number_format($loan->amount,3) }} ৳</td>
            <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M, Y') }}</td>
            <td>{{ $loan->note ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
