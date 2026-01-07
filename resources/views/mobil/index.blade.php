@extends('master')

@section('title')
    Mobil Stock List
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
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-oil-can me-2"></i> Mobil Stock List
                            </h4>
                        </div>
                        <a href="{{ route('mobil.create') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 0px;">
                             Add 
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive ">
                            <table id="stocksTable" class="table table-bordered align-middle ">
                                <thead class="bg-dark ">
                                    <tr>
                                        <th style="color: white!important;">SL</th>
                                        <th style="color: white!important;">Date</th>
                                        <th style="color: white!important;">Name</th>
                                        <th style="color: white!important;">Buying Price (৳)</th>
                                        <th style="color: white!important;">Selling Price (৳)</th>
                                        <th style="color: white!important;">Quantity (L)</th>
                                        <th class="text-center" style="color: white!important;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stocks as $stock)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M Y') }}</td>
                                            <td>{{ $stock->name }}</td>
                                            <td>{{ number_format($stock->buying_price, 3) }}</td>
                                            <td>{{ number_format($stock->selling_price, 3) }}</td>
                                            <td>{{ number_format($stock->quantity, 3) }}</td>
                                            <td class="action-btns text-center">
                                                <a href="{{ route('mobil.stock.edit', $stock->id) }}" class="btn btn-sm btn-edit">
                                                    <i class="fas fa-edit"></i> 
                                                </a>
                                                <form action="{{ route('mobil.stock.delete', $stock->id) }}" method="GET" class="d-inline delete-form">
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
                                                <td colspan="7" class="text-center text-muted">No stock records found.</td>
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
</script>
<script>
    $(document).ready(function() {
        $('#stocksTable').DataTable({
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
