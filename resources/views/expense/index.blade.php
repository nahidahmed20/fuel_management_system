@extends('master')

@section('title', 'Expense List')
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <!-- Header -->
                    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-0">
                            <i class="fas fa-list-alt me-2"></i> All Expenses
                        </h4>
                        <a href="{{ route('expense.create') }}" class="btn btn-sm btn-add bg-primary" style="border-radius: 2px">
                            <i class="fa fa-plus-circle me-1"></i> Add New
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="expenseTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="color:white !important">#</th>
                                        <th style="color:white !important">Category</th>
                                        <th style="color:white !important">Amount</th>
                                        <th style="color:white !important">Date</th>
                                        <th style="color:white !important">Note</th>
                                        <th class="text-center" style="color:white !important">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses as $expense)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $expense->category }}</td>
                                            <td>{{ number_format($expense->amount, 3) }} ৳</td>
                                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</td>
                                            <td>{{ $expense->note ?? 'N/A'}}</td>
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex justify-content-center">
                                                    <div class="me-1" style="margin-right: 2px">
                                                        <a href="{{ route('expense.edit', $expense->id) }}" class="btn btn-sm btn-edit">
                                                            <i class="fa fa-edit me-1"></i> 
                                                        </a>
                                                    </div>
                                                    <div class="ms-1" style="margin-left: 2px">
                                                        <form id="delete-form-{{ $expense->id }}" action="{{ route('expense.destroy', $expense->id) }}" method="GET">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-delete">
                                                                <i class="fa fa-trash"></i> 
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">No expenses found.</td>
                                            </tr>
                                        </tfoot>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

<script>
    $(document).ready(function () {
        $('#expenseTable').DataTable({
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
    $(document).ready(function () {
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e63946',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
