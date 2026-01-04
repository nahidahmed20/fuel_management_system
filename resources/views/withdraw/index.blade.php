@extends('master')

@section('title')
Cash Withdraw List
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

    #cash_withdraw_table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-edit {
        background-color: #20c997;
        color: white;
        padding: 6px 10px;
        border-radius: 5px;
    }

    .btn-delete {
        background-color: #e63946;
        color: white;
        padding: 6px 10px;
        border-radius: 5px;
    }

    @media (max-width: 576px) {
        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .table th,
        .table td {
            font-size: 0.9rem;
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
                            <i class="fas fa-hand-holding-usd me-2"></i> Cash Withdraw List
                        </h4>
                        <a href="{{ route('cash.withdraw.create') }}" class="btn btn-sm d-flex align-items-center"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Add Withdraw
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0" id="cash_withdraw_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="color: white !important;">#</th>
                                        <th style="color: white !important;">Name</th>
                                        <th class="text-center" style="color: white !important;">Type</th>
                                        <th class="text-center" style="color: white !important;">Amount (৳)</th>
                                        <th style="color: white !important;">Withdraw By</th>
                                        <th style="color: white !important;">Note</th>
                                        <th class="text-center" style="color: white !important;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdraws as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center"><span class="badge bg-danger">{{ ucfirst($item->type) }}</span></td>
                                            <td class="text-center">{{ number_format($item->amount, 3) }}</td>
                                            <td>{{ $item->withdraw_by }}</td>
                                            <td>{{ $item->note }}</td>
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex justify-content-center">
                                                    <div class="me-2">
                                                        <a href="{{ route('cash.withdraw.edit', $item->id) }}" class="btn btn-sm text-white btn-edit mx-1">
                                                            <i class="fa fa-edit me-1"></i> Edit
                                                        </a>
                                                    </div>
                                                    <form action="{{ route('cash.withdraw.destroy', $item->id) }}" method="GET" class="d-inline delete-form">
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
                                                <td colspan="7" class="text-center text-muted py-3">No withdraw records found.</td>
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
        $('#cash_withdraw_table').DataTable({
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
