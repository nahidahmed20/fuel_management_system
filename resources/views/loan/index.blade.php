@extends('master')
@section('title', 'Loan Record')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .card-header-custom {
        background-color: #27548A;
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 700;
    }

    .btn-add {
        background: linear-gradient(45deg, #36D1DC, #5B86E5);
        color: #fff;
        font-weight: 500;
        border: none;
        padding: 4px 10px;
        border-radius: 5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.3s ease;
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

    .btn-add:hover {
        background: linear-gradient(45deg, #2bb4c8, #4a73d6);
    }

    .btn-edit {
        background-color: #20c997;
        color: white;
        padding: 4px 10px;
    }

    .btn-edit:hover {
        background-color: #17a673;
    }

    .btn-delete {
        background-color: #e63946;
        color: white;
        padding: 4px 10px;
    }

    .btn-delete:hover {
        background-color: #b32a37;
    }

    .btn-sm i {
        margin-right: 4px;
    }

    table tbody tr:hover {
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

        .table th, .table td {
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
        }

        .table-responsive {
            overflow-x: auto;
        }
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
                        <a href="{{ route('loans.create') }}" class="btn btn-sm btn-add">
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
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="d-inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-delete delete-btn">
                                                        <i class="fa fa-trash"></i> Delete
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#loanTable').DataTable({
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
