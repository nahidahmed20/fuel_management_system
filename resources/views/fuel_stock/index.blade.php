@extends('master')

@section('title')
Fuel Stock List
@endsection

@push('style')
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
        font-weight: 500;
    }

    #fuel_stock tbody tr:hover {
        background-color: #f8f9fa;
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
        color:white;
        padding: 4px 10px;
    }

    @media (max-width: 576px) {
        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
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

        .action-btn-group {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        .action-btn-group > div {
            width: 100%;
        }

        .btn-edit, .btn-delete {
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

                    {{-- Main Header --}}
                    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <h4 class="card-title mb-0 fw-bold">
                            <i class="bi bi-fuel-pump-fill me-2"></i> Fuel Management
                        </h4>
                        <a href="{{ route('fuel.stock.create') }}" 
                           class="btn btn-sm d-flex align-items-center"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Add Fuel Stock
                        </a>
                    </div>

                    {{-- Current Fuel Stock (Summary Card) --}}
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                        <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem;">
                            <strong class="mb-0">
                                <i class="fas fa-gas-pump me-2"></i> Current Fuel Stock (Liters)
                            </strong>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($fuelTypes as $type)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-semibold text-dark">{{ ucfirst($type->name) }}</span>
                                        <span class="badge rounded-pill px-3 py-2" style="background-color: #0d6efd; font-size: 0.9rem; color:white">
                                            {{ number_format($currentStock[$type->id] ?? 0, 3) }} L
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No fuel types found.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="card-footer text-end bg-light px-3 py-2">
                            <small class="text-muted">Updated at: {{ now()->format('d M, Y h:i A') }}</small>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <hr class="my-4">

                    {{-- Fuel Stocks Table --}}
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-3 py-2 rounded" style="background: linear-gradient(90deg, #2193b0, #6dd5ed); color: white;">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-table me-2"></i> Fuel Stocks Table
                            </h5>
                        </div>


                        <div class="table-responsive">
                            <table class="table mb-0" id="fuelStockTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="color:white !important;" style="width: 5%;">#</th>
                                        <th style="color:white !important;">Fuel Type</th>
                                        <th style="color:white !important;">Quantity (L)</th>
                                        <th style="color:white !important;">Buying Price</th>
                                        <th style="color:white !important;">Selling Price</th>
                                        <th style="color:white !important;">Truck No</th>
                                        <th style="color:white !important;">Company</th>
                                        <th style="color:white !important;">Date</th>
                                        <th class="text-center"  style="color:white !important;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fuelStocks as $stock)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $stock->fuelType->name ?? '-' }}</td>
                                            <td>{{ number_format($stock->quantity, 3) }}</td>
                                            <td>{{ number_format($stock->buying_price, 3) }} ৳</td>
                                            <td>{{ number_format($stock->selling_price, 3) }} ৳</td>
                                            <td>{{ $stock->truck_number }}</td>
                                            <td>{{ $stock->company_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex justify-content-center">
                                                    <div style="margin-right: 2px">
                                                        <a href="{{ route('fuel.stock.edit', $stock->id) }}"
                                                           class="btn btn-sm text-white btn-edit">
                                                            <i class="fa fa-edit me-1"></i> Edit
                                                        </a>
                                                    </div>

                                                    <div style="margin-left: 2px">
                                                        <form action="{{ route('fuel.stock.destroy', $stock->id) }}" method="GET" class="delete-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm text-white btn-delete">
                                                                <i class="fa fa-trash me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-3">
                                                    No fuel stock data found.
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- End table body -->
                    
                </div> <!-- End card -->
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
        $('#fuelStockTable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            language: {
                paginate: {
                    previous: "<i class='fas fa-angle-left'></i>",
                    next: "<i class='fas fa-angle-right'></i>"
                }
            },
        });
    });

    
</script>
<script>
    $(document).ready(function () {
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
