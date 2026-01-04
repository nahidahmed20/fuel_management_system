@extends('master')

@section('title')
Account Transactions
@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .card-header-custom {
        background-color: #0d6efd;
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 500;
    }

    #account_table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-edit {
        background-color: #20c997;
        color: white;
        padding: 4px 10px;
        border-radius: 5px;
    }

    .btn-delete {
        background-color: #e63946;
        color: white;
        padding: 4px 10px;
        border-radius: 5px;
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

        .table-responsive {
            overflow-x: auto;
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
                    {{-- Header --}}
                    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h4 class="card-title mb-0" style="font-weight: bold;">
                            <i class="fas fa-wallet me-2"></i> Account Transactions
                        </h4>
                        <a href="{{ route('account.create') }}" class="btn btn-sm d-flex align-items-center"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Add Transaction
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0" id="account_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="color: white !important;">#</th>
                                        <th style="color: white !important;">Name</th>
                                        <th style="color: white !important;" class="text-center">Type</th>
                                        <th style="color: white !important;" class="text-center">Amount (৳)</th>
                                        <th style="color: white !important;">Deposited By</th>
                                        <th style="color: white !important;">Note</th>
                                        <th class="text-center" style="color: white !important;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($accounts as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td >{{ $item->name }}</td>
                                            <td class="text-center">
                                                @if ($item->type == 'deposit')
                                                    <span class="badge bg-success">Deposit</span>
                                                @elseif ($item->type == 'withdraw')
                                                    <span class="badge bg-danger">Withdraw</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ number_format($item->amount, 3) }}</td>
                                            <td >{{ $item->deposited_by }}</td>
                                            <td>{{ $item->note }}</td>
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex justify-content-center">
                                                    <div class="me-2">
                                                        <a href="{{ route('account.edit', $item->id) }}" class="btn btn-sm text-white btn-edit mx-1">
                                                            <i class="fa fa-edit me-1"></i> Edit
                                                        </a>
                                                    </div>
                                                    <form action="{{ route('account.destroy', $item->id) }}" method="GET" class="delete-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm text-white btn-delete">
                                                            <i class="fa fa-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-3">No account records found.</td>
                                            </tr>
                                        </tfoot>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

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
        $('#account_table').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            language: {
                paginate: {
                    previous: "<i class='fas fa-angle-left'></i>",
                    next: "<i class='fas fa-angle-right'></i>"
                }
            }
        });

        $('.btn-delete').on('click', function (e) {
            e.preventDefault();
            var form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
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
