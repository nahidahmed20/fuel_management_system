@extends('master')

@section('title')
   Product Stock List
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
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem;">
                        <strong class="mb-0">
                            <i class="fas fa-boxes me-2"></i> Current Product Stock (Quantity)
                        </strong>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($availableSummary as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-semibold text-dark">{{ $item['product_name'] }}</span>
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #0d6efd; font-size: 0.9rem; color:white">
                                        {{ number_format($item['available_stock'], 3) }}
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">No products found.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-end bg-light px-3 py-2">
                        <small class="text-muted">Updated at: {{ now()->format('d M, Y h:i A') }}</small>
                    </div>
                </div>

                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-warehouse me-2"></i> Product Stock List
                        </h4>
                        <a href="{{ route('product.stock.create') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                             Add 
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table table-bordered align-middle" id="productStockTable">
                                <thead class="table-dark" >
                                    <tr>
                                        <th style="color:white !important;">Product</th>
                                        <th style="color:white !important;">Quantity</th>
                                        <th style="color:white !important;">Buying Price</th>
                                        <th style="color:white !important;">Selling Price</th>
                                        <th style="color:white !important;">Date</th>
                                        <th style="color:white !important;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productStocks as $stock)
                                        <tr>
                                            <td>{{ $stock->product->name }}</td>
                                            <td>{{ number_format($stock->quantity, 3) }}</td>
                                            <td>{{ number_format($stock->buying_price, 3) }}</td>
                                            <td>{{ number_format($stock->selling_price, 3) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('product.stock.edit', $stock->id) }}" class="btn btn-sm btn-edit me-1">
                                                    <i class="fas fa-edit"></i> 
                                                </a>
                                                <form action="{{ route('product.stock.destroy', $stock->id) }}" method="GET" class="d-inline delete-form ">
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
                                                <td colspan="6" class="text-center text-muted">No stock-in records found.</td>
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
                text: "This stock record will be deleted.",
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
    });

    $(document).ready(function() {
        $('#productStockTable').DataTable({
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




