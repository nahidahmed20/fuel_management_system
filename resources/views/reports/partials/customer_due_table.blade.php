<div class="table-responsive">
    <table class="table table-bordered mb-0" id="customerDueTable">
        <thead class="table-dark">
            <tr>
                <th class="text-center" style="color: white !important">#</th>
                <th style="color: white !important">Customer</th>
                <th style="color: white !important">Amount Due</th>
                <th style="color: white !important">Due Date</th>
                <th style="color: white !important">Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customerDues as $due)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $due->customer->name?? '' }}</td>
                    <td>{{ number_format($due->amount_due, 3) }} ৳</td>
                    <td>{{ $due->due_date ? \Carbon\Carbon::parse($due->due_date)->format('d M, Y') : 'N/A' }}</td>
                    <td>{{ $due->note ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th>Customer</th>
                <th>Amount Due</th>
                <th>Due Date</th>
                <th>Note</th>
            </tr>
        </tfoot>
    </table>
</div>
