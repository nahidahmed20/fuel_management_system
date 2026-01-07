@extends('master')

@section('title')
    Fuel Sell List
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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">

                    {{-- Header --}}
                    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h4 style="font-weight: bold;">
                            <i class="bi bi-list-ul me-2"></i> Fuel Sell List
                        </h4>

                        <a href="{{ route('fuel.sell.create') }}" 
                           class="btn btn-info btn-sm" 
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                            <i class="fa fa-plus-circle me-1"></i> Add New Sell
                        </a>
                    </div>

                    

                    <div class="card shadow-sm border-0 mt-3">
    <!-- Card Header -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-chart-line me-2 text-primary"></i> Summary
        </h5>
    </div>

    <!-- Card Body -->
    <div class="card-body">

        <!-- Filter -->
        <form method="GET" action="{{ route('fuel.sell.summary') }}" class="row g-2 mb-4">
            <div class="col-md-3">
                <input type="date" name="start_date"
                       class="form-control form-control-sm"
                       value="{{ request('start_date') }}">
            </div>

            <div class="col-md-3">
                <input type="date" name="end_date"
                       class="form-control form-control-sm"
                       value="{{ request('end_date') }}">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row g-3">
            @forelse($fuelSummary as $summary)
                <div class="col-md-4">
                    <div class="card text-white shadow-sm h-100 border-0"
                        style="background: linear-gradient(135deg, #36D1DC, #5B86E5); border-radius: 12px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-gas-pump me-2"></i>
                                {{ $summary->fuelType->name ?? 'N/A' }}
                            </h5>

                            <p class="mb-2">
                                <i class="fas fa-balance-scale me-2"></i>
                                <span class="fw-semibold">Total Quantity</span><br>
                                <span class="fs-5">
                                    {{ number_format($summary->total_quantity, 3) }} L
                                </span>
                            </p>

                            <p class="mb-0">
                                <i class="fas fa-coins me-2"></i>
                                <span class="fw-semibold">Total Sell</span><br>
                                <span class="fs-5">
                                    ৳{{ number_format($summary->total_sell, 3) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-4">
                    No summary data found.
                </div>
            @endforelse
        </div>

    </div>
</div>

                    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                    {{-- Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive rounded my-3">
                            <table class="table mb-0" id="fuel_sell_list">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important;">#</th>
                                        <th style="color:white !important;">Fuel Type</th>
                                        <th style="color:white !important;">Nozzle</th>
                                        <th style="color:white !important;">Quantity (L)</th>
                                        <th style="color:white !important;">Total Sell Price(৳)</th>
                                        <th style="color:white !important;">Date</th>
                                        <th class="text-center" style="color:white !important; width: 20%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fuelSells as $index => $sell)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ucfirst($sell->fuelType->name ?? 'N/A') }}</td>
                                            <td>{{ $sell->nozzle->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($sell->quantity, 3) }}</td>
                                            <td>{{ number_format($sell->total_sell, 3) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($sell->date)->format('Y-m-d') }}</td>
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex flex-row justify-content-center" style="gap: 5px;">
                                                    <div>
                                                        <a href="{{ route('fuel.sell.edit', $sell->id) }}" 
                                                           class="btn btn-sm btn-edit">
                                                           <i class="fa fa-edit me-1"></i> 
                                                        </a>
                                                    </div>

                                                    <div>
                                                        <form action="{{ route('fuel.sell.destroy', $sell->id) }}" method="GET" style="display:inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-delete delete-btn">
                                                                <i class="fa fa-trash me-1"></i> 
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-3">
                                                    No fuel sells found.
                                                </td>
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
        $('#fuel_sell_list').DataTable({
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
    $(document).ready(function() {
        $(".delete-btn").on("click", function (event) {
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
