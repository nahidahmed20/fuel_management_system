@extends('master')

@section('title')
   Product Sell List
@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0,0,0,0.1),
                    4px 8px 20px rgba(0,0,0,0.05);
    }
    .btn-edit {
        background: #20c997;
        color: white;
        padding: 4px 10px;
    }

    .btn-delete {
        background: #e63946;
        color: white;
        padding: 4px 10px;
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

    @media (max-width: 576px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .card-header .btn {
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }

        .card-title {
            font-size: 1.1rem;
        }

        button[type="submit"] {
            width: 100%;
        }
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
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus me-1"></i> Add New Sell
                        </a>
                    </div>
                    <div class="mt-3">
                        <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i> Summary</h5>
                        <div class="mt-3">
                            <form method="GET" action="{{ route('product.summary') }}" class="row g-2 mb-4">
                                <div class="col-md-3">
                                    <input type="date" name="start_date" class="form-control form-control-sm" 
                                        value="{{ request('start_date') }}" placeholder="Start Date">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="end_date" class="form-control form-control-sm" 
                                        value="{{ request('end_date') }}" placeholder="End Date">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                </div>
                            </form>
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
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('product.sales.destroy', $sale->id) }}" method="GET" class="d-inline delete-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-delete">
                                                        <i class="fas fa-trash-alt"></i> Delete
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
