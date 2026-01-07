@extends('master')

@section('title')
Loan Due Payments
@endsection
@push('style')
    <style>
        /* Top control row flex */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dt-buttons,
        .dataTables_wrapper .dataTables_filter {
            display: inline-flex;
            align-items: center;
        }

        /* Wrapper top alignment */
        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        /* Buttons in the middle */
        .dataTables_wrapper .dt-buttons {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Button spacing */
        .dataTables_wrapper .dt-buttons .dt-button {
            margin: 0 5px;
        }
    </style>
@endpush


@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow-sm border-0">
                    {{-- Header --}}

                    <div class="card-header-custom d-flex justify-content-between align-items-center mb-2 flex-wrap">
                         <h4 class="card-title mb-0">
                            <i class="fa fa-money-bill-wave me-2"></i> Loan Due Payments
                        </h4>
                        <a href="{{ route('borrowers.index') }}" 
                           class="btn btn-sm d-flex align-items-center" 
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Back to Borrowers
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="loan_due_payment">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 5%; color:white !important;">#</th>
                                        <th style="color:white !important;">Borrower</th>
                                        <th class="text-end" style="width: 15%; color:white !important;">Amount (৳)</th>
                                        <th style="width: 20%; color:white !important;">Payment Date</th>
                                        <th style="color:white !important;">Note</th>
                                        <th style="color:white !important;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loanPayments as $loanPayment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $loanPayment->borrower->name ?? 'N/A' }}</td>
                                            <td class="text-end">{{ number_format($loanPayment->amount, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($loanPayment->payment_date)->format('d M, Y') }}</td>
                                            <td>{{ $loanPayment->note ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('loan-payments.edit', $loanPayment->id) }}" class="btn btn-sm btn-edit">
                                                    <i class="fa fa-edit"></i> 
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">No due payments found.</td>
                                            </tr>
                                        </tfoot>
                                    @endforelse
                                </tbody>
                                @if($loanPayments->count())
                                <tfoot>
                                    <tr class="table-success">
                                        <td colspan="2" class="text-end fw-bold">Total</td>
                                        <td class="text-end fw-bold">{{ number_format($loanPayment->sum('amount'), 2) }} ৳</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Borrower-wise Due Summary --}}
        <div class="">
            <h5 class="fw-bold mb-3 text-white px-4 py-2 rounded" 
                style="background: linear-gradient(90deg, #1d3557, #457b9d); display: inline-block;">
                <i class="fas fa-users me-2"></i> Borrower Loan Due Summary
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="text-white" style="background: linear-gradient(45deg, #1d3557, #457b9d);">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="text-align:left">Borrower</th>
                            <th>Total Loan</th>
                            <th>Paid</th>
                            <th>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowerLoans as $index => $item)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary rounded-circle" style="width: 30px; height: 30px; line-height: 22px;">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="text-start fw-semibold text-dark">
                                    {{ $item['name'] }}
                                </td>
                                <td>
                                    <span class="badge text-white px-3 py-2" style="background: linear-gradient(45deg, #2193b0, #6dd5ed); font-weight: bold;">
                                        {{ number_format($item['totalLoan'], 2) }} ৳
                                    </span>
                                </td>
                                <td>
                                    <span class="badge text-white px-3 py-2" style="background: linear-gradient(45deg, #11998e, #38ef7d); font-weight: bold;">
                                        {{ number_format($item['totalPaid'], 2) }} ৳
                                    </span>
                                </td>
                                <td>
                                    <span class="badge text-white px-3 py-2" style="background: linear-gradient(45deg, #ff416c, #ff4b2b); font-weight: bold;">
                                        {{ number_format($item['due'], 2) }} ৳
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-3">
                                    <em>No borrower summary found.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>    
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        
        $('#loan_due_payment').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            dom: 'lBfrtip',
            buttons: [
                { extend: 'excelHtml5', className: 'btn btn-sm btn-success', text: '<i class="fa fa-file-excel"></i> Excel' },
                { extend: 'pdfHtml5', className: 'btn btn-sm btn-danger', text: '<i class="fa fa-file-pdf"></i> PDF' },
                { extend: 'print', className: 'btn btn-sm btn-primary', text: '<i class="fa fa-print"></i> Print' }
            ],
            language: {
                paginate: {
                    previous: "<i class='fas fa-angle-left'></i>",
                    next: "<i class='fas fa-angle-right'></i>"
                }
            }
        });
    });
</script>
@endpush
