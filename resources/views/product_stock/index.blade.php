@extends('master')

@section('title', 'Product Stock List')

@push('style')
<style>
    /* ===== DataTable Top Layout ===== */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dt-buttons,
    .dataTables_wrapper .dataTables_filter {
        display: inline-flex;
        align-items: center;
    }
    .dataTables_wrapper .dataTables_length { float: left; }
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dt-buttons {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    .dataTables_wrapper .dt-buttons .dt-button { margin: 0 4px; }

    /* ===== Mini Stock Cards ===== */
    .mini-stock-card { transition: all 0.3s ease; cursor: pointer; }
    .mini-stock-card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.15); }
    .stock-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 1.2rem;
    }
    .stock-qty-badge {
        display: inline-block;
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff;
        padding: 6px 14px;
        font-size: 0.85rem;
        border-radius: 50px;
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                {{-- ================= SUMMARY CARDS ================= --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse($availableSummary as $item)
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                    <div class="card mini-stock-card border-0 rounded-4 h-100">
                                        <div class="card-body text-center p-3">
                                            <div class="stock-icon mb-2"><i class="fas fa-cube"></i></div>
                                            <h6 class="fw-semibold text-dark mb-1 text-truncate">{{ $item['product_name'] }}</h6>
                                            <span class="stock-qty-badge mt-2">{{ number_format($item['available_stock'], 3) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                    No products found
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer text-end bg-light">
                        <small class="text-muted">Updated at: {{ now()->format('d M, Y h:i A') }}</small>
                    </div>
                </div>

                {{-- ================= STOCK LIST TABLE ================= --}}
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
                         style="background-color:#27548A;color:#fff;">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-warehouse me-2"></i> Product Stock List
                        </h4>
                        <a href="{{ route('product.stock.create') }}" class="btn btn-sm mt-2 mt-sm-0"
                           style="background:linear-gradient(45deg,#36D1DC,#5B86E5); color:#fff;border:none;border-radius:2px;">
                           Add Stock
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="productStockTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important">SL</th>
                                        <th style="color:white !important">Products</th>
                                        <th style="color:white !important">Total Quantity</th>
                                        <th style="color:white !important">Buying Price</th>
                                        <th style="color:white !important">Selling Price</th>
                                        <th style="color:white !important">Date</th>
                                        <th class="text-center" style="color:white !important">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($purchases as $purchase)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                @foreach ($purchase->stocks as $item)
                                                    {{ $item->product->name }} ({{ $item->product->sku }})<br>
                                                @endforeach
                                            </td>
                                            <td>{{ number_format($purchase->stocks->sum('quantity'), 3) }}</td>
                                            <td>{{ number_format($purchase->stocks->sum('buying_price'), 3) }}</td>
                                            <td>{{ number_format($purchase->stocks->sum('selling_price'), 3) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('product.stock.edit', $purchase->id) }}" class="btn btn-sm btn-edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('product.stock.destroy', $purchase->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-delete">
                                                        <i class="fas fa-trash-alt"></i> 
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        {{-- Modal for View --}}
                                        <div class="modal fade" id="viewModal{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Purchase Details - {{ \Carbon\Carbon::parse($purchase->date)->format('d M, Y') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>SL</th>
                                                                    <th>Product</th>
                                                                    <th>Quantity</th>
                                                                    <th>Buying Price</th>
                                                                    <th>Total Buying</th>
                                                                    <th>Selling Price</th>
                                                                    <th>Total Selling</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($purchase->stocks as $key => $stock)
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>{{ $stock->product->name }} ({{ $stock->product->sku }})</td>
                                                                        <td>{{ number_format($stock->quantity, 3) }}</td>
                                                                        <td>{{ number_format($stock->buying_price, 3) }}</td>
                                                                        <td>{{ number_format($stock->quantity * $stock->buying_price, 3) }}</td>
                                                                        <td>{{ number_format($stock->selling_price, 3) }}</td>
                                                                        <td>{{ number_format($stock->quantity * $stock->selling_price, 3) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No stock-in records found.</td>
                                        </tr>
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
    $(document).ready(function() {

        // Delete confirmation
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: "This purchase record will be deleted along with all items!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53935',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) { form.submit(); }
            });
        });

        // DataTable
        $('#productStockTable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            dom: 'lBfrtip',
            buttons: [
                { extend: 'excelHtml5', className: 'btn btn-sm btn-success', text: '<i class="fa fa-file-excel"></i> Excel' },
                { extend: 'pdfHtml5', className: 'btn btn-sm btn-danger', text: '<i class="fa fa-file-pdf"></i> PDF' },
                { extend: 'print', className: 'btn btn-sm btn-primary', text: '<i class="fa fa-print"></i> Print' }
            ],
            language: { paginate: { previous: "<i class='fas fa-angle-left'></i>", next: "<i class='fas fa-angle-right'></i>" } }
        });

    });
</script>
@endpush
