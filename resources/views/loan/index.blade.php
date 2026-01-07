@extends('master')
@section('title', 'Loan Record')
@push('style')
    <style>
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

        {{-- Loan Report Table --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header-custom mb-2 d-flex justify-content-between align-items-center">
                        <h4><i class="fas fa-hand-holding-usd me-2"></i> Loan Record</h4>
                        <a href="{{ route('loans.create') }}" class="btn btn-sm btn-add btn-primary" style="border-radius: 2px">
                            <i class="fa fa-plus-circle"></i> New Loan
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="loanTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important">#</th>
                                        <th style="color:white !important">Borrower</th>
                                        <th style="color:white !important">Amount (৳)</th>
                                        <th style="color:white !important">Date</th>
                                        <th style="color:white !important">Note</th>
                                        <th class="text-center" style="color:white !important">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $loan->borrower->name }}</td>
                                            <td>{{ number_format($loan->amount, 2) }}</td>
                                            <td>{{ $loan->loan_date }}</td>
                                            <td>{{ $loan->note ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-sm btn-edit">
                                                    <i class="fa fa-edit"></i> 
                                                </a>
                                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="d-inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-delete delete-btn">
                                                        <i class="fa fa-trash"></i> 
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold table-success">
                                        <td colspan="2">Total</td>
                                        <td colspan="4">{{ number_format($loans->sum('amount'), 2) }} ৳</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> {{-- End Card Body --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
         $('#loanTable').DataTable({
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
<script>
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
</script>
@endpush
