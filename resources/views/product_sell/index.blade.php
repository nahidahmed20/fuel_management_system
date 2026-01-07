@extends('master')

@section('title')
   Product Sell List
@endsection
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-shopping-cart me-2"></i> Product Sell List
                        </h4>
                        <a href="{{ route('product.sales.create') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                             Add Sell
                        </a>
                    </div>
                    <div class="mt-3">
                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <h6 class="mb-3 fw-semibold text-primary">
                                    <i class="bi bi-funnel"></i> Filter Product Summary
                                </h6>

                                <form method="GET" action="{{ route('product.summary') }}" class="row align-items-end g-3">
                                    
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Start Date</label>
                                        <input type="date"
                                            name="start_date"
                                            class="form-control form-control-sm"
                                            value="{{ request('start_date') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">End Date</label>
                                        <input type="date"
                                            name="end_date"
                                            class="form-control form-control-sm"
                                            value="{{ request('end_date') }}">
                                    </div>

                                    <div class="col-md-4 d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i class="bi bi-search"></i> Filter
                                        </button>

                                        <a href="{{ route('product.summary') }}" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </a>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div class="row g-3">
                            @forelse($summary as $su)
                                <div class="col-md-4 d-flex">
                                    <div class="card text-white shadow-sm w-100 border-0 mb-3" 
                                        style="background: linear-gradient(135deg, #36D1DC, #5B86E5); border-radius: 12px;">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-3">
                                                <i class="fas fa-gas-pump me-2"></i> 
                                                {{ $su->product->name ?? 'N/A' }}
                                            </h5>
                                            <p class="mb-2">
                                                <i class="fas fa-balance-scale me-2"></i>
                                                <span class="fw-semibold">Total Quantity:</span><br>
                                                <span class="fs-5">{{ number_format($su->total_quantity, 3) }} L</span>
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-coins me-2"></i>
                                                <span class="fw-semibold">Total Sell:</span><br>
                                                <span class="fs-5">৳{{ number_format($su->total_sell, 3) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted py-3">
                                    No summary data found.
                                </div>
                            @endforelse
                        </div>

                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="productSellTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important;">Product</th>
                                        <th style="color:white !important;">Quantity</th>
                                        <th style="color:white !important;">Total Sell (৳)</th>
                                        <th style="color:white !important;">Date</th>
                                        <th style="color:white !important;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sales as $sale)
                                        <tr>
                                            <td>{{ $sale->product->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($sale->quantity, 3) }}</td>
                                            <td>{{ number_format($sale->total_price, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('product.sales.edit', $sale->id) }}" class="btn btn-sm btn-edit me-1">
                                                    <i class="fas fa-edit"></i> 
                                                </a>
                                                <form action="{{ route('product.sales.destroy', $sale->id) }}" method="GET" class="d-inline delete-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-delete">
                                                        <i class="fas fa-trash-alt"></i> 
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No sell records found.</td>
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
<script>
    $(document).ready(function () {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "This sale record will be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53935',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $('#productSellTable').DataTable({
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
