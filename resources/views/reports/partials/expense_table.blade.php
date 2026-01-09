<table class="table table-bordered" id="expenseTable">
    <thead class="table-dark">
        <tr>
            <th style="color: white !important">#</th>
            <th style="color: white !important">Category</th>
            <th style="color: white !important">Amount</th>
            <th style="color: white !important">Date</th>
            <th style="color: white !important">Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $expense)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $expense->category ?? 'N/A' }}</td>
            <td>{{ number_format($expense->amount,3) }} ৳</td>
            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</td>
            <td>{{ $expense->note ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
