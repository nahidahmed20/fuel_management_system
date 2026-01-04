@extends('master')

@section('title', 'Expense List')

@push('style')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .card-header-custom {
        background-color: #27548A;
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 600;
    }

    .btn-add {
        background: linear-gradient(45deg, #36D1DC, #5B86E5);
        color: white;
        border: none;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 5px;
    }

    .btn-back,
    .btn-edit,
    .btn-delete {
        min-width: 60px;
        text-align: center;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        color: white;
    }

    .btn-edit {
        background-color: #20c997;
        color: white;
        padding: 4px 10px;
    }

    .btn-delete {
        background-color: #e63946;
        color: white;
        padding: 4px 10px;
    }

    #expenseTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    @media (max-width: 576px) {
        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .card-header-custom h4 {
            font-size: 1.1rem;
        }

        .table th,
        .table td {
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
        }

        .action-btn-group {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        .action-btn-group > div {
            width: 100%;
        }

        .btn-edit,
        .btn-delete {
            width: 100% !important;
        }
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
                        <a href="{{ route('expense.create') }}" class="btn btn-sm btn-add">
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
                                                            <i class="fa fa-edit me-1"></i> Edit
                                                        </a>
                                                    </div>
                                                    <div class="ms-1" style="margin-left: 2px">
                                                        <form id="delete-form-{{ $expense->id }}" action="{{ route('expense.destroy', $expense->id) }}" method="GET">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-delete">
                                                                <i class="fa fa-trash"></i> Delete
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#expenseTable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
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
