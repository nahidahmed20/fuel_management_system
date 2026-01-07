@extends('master')
@section('title', 'Customer Dues')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
    /* Card & Header */
    .card-custom {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
    }
    .card-header-custom {
        background-color: #343a40;
        color: #fff;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-add {
        background-color: #0d6efd;
        color: #fff;
        font-weight: 500;
        border-radius: 0.375rem;
        padding: 5px 12px;
        display: flex;
        align-items: center;
    }

    /* Summary cards */
    .summary-card {
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        color: #fff;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .summary-card h6 {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    .summary-card h3 {
        margin: 0;
        font-weight: 600;
    }
    .bg-due { background-color: #4988C4; } /* Total Due */
    .bg-payment { background-color: #198754; } /* Total Payment */
    .bg-current { background-color: #0d6efd; } /* Current Due */

    /* Table */
    table.dataTable tbody tr:hover {
        background-color: #f1f3f5;
    }
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

    /* Responsive */
    @media (max-width: 576px) {
        .summary-card { margin-bottom: 1rem; }
        .card-header-custom { flex-direction: column; gap: 0.5rem; }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="row report-section">
            <div class="col-lg-12">
                <div class="card card-custom shadow-sm">

                    {{-- Header --}}
                    <div class="card-header-custom">
                        <h4><i class="fas fa-users me-2"></i> All Customer Dues</h4>
                        <a href="{{ route('customer_due.create') }}" class="btn btn-sm btn-add">
                            <i class="fa fa-plus-circle me-1"></i> Add Due
                        </a>
                    </div>

                    {{-- Filter Form --}}
                    <div class="card-body">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header py-2">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-1"></i> Filter Customer Due
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('customer_due.filter') }}" method="GET" class="row g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label for="start_date" class="form-label small">Start Date</label>
                                        <input type="date" name="start_date" id="start_date"
                                            class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="end_date" class="form-label small">End Date</label>
                                        <input type="date" name="end_date" id="end_date"
                                            class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-md-4 d-flex">
                                        <button type="submit" class="btn btn-primary btn-sm w-25" style="border-radius: 2px">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mb-3 shadow-sm">
                            <div class="card-header py-2">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-chart-bar me-1"></i> Due Summary
                                </h6>
                            </div>

                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="summary-card bg-due text-center">
                                            <h6>Total Due</h6>
                                            <h3>{{ number_format($totalDue, 2) }} ৳</h3>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="summary-card bg-payment text-center">
                                            <h6>Total Payment</h6>
                                            <h3>{{ number_format($totalPayment, 2) }} ৳</h3>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="summary-card bg-current text-center">
                                            <h6>Current Due</h6>
                                            <h3>{{ number_format($currentDue, 2) }} ৳</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="customer_due_today">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important">#</th>
                                        <th style="color:white !important">Customer</th>
                                        <th style="color:white !important">Amount Due (৳)</th>
                                        <th style="color:white !important">Due Date</th>
                                        <th class="text-center" style="color:white !important">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $due)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $due->customer->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($due->amount_due, 3) }}</td>
                                        <td>{{ $due->due_date ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('customer_due.edit', $due) }}" class="btn btn-edit"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('customer_due.destroy', $due) }}" method="GET" class="d-inline-block delete-form">
                                                @csrf
                                                <button type="submit" class="btn btn-delete delete-btn"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> {{-- Table responsive --}}
                    </div> {{-- Card body --}}
                </div> {{-- Card --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')


<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    $('#customer_due_today').DataTable({
        responsive: true,
        pagingType: 'simple_numbers',
        dom: 'lBfrtip', 
        buttons: [
            { extend: 'excel', className: 'btn btn-sm btn-success mb-2', text: '<i class="fa fa-file-excel"></i> Excel' },
            { extend: 'pdf', className: 'btn btn-sm btn-danger mb-2', text: '<i class="fa fa-file-pdf"></i> PDF' },
            { extend: 'print', className: 'btn btn-sm btn-primary mb-2', text: '<i class="fa fa-print"></i> Print' }
        ],
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'></i>",
                next: "<i class='fas fa-angle-right'></i>"
            }
        }
    });
 

    // Delete confirmation
    $(".delete-btn").on("click", function(event) {
        event.preventDefault();
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
});
</script>
@endpush
