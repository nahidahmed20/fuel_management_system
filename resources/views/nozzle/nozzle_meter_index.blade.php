@extends('master')

@section('title')
    Nozzle Meter List
@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
    }

    .btn-edit {
        background-color: #20c997;
        color: white;
        padding: 7px 10px;
    }

    .btn-delete {
        background-color: #e63946;
        color: white;
        padding: 7px 10px;
    }
    .btn-back,
    .btn-edit,
    .btn-delete {
        text-align: center;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 3px;
        font-size: 16px;
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
                <div class="card shadow-lg rounded-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-speedometer2 me-2"></i> Nozzle Meter List
                            </h4>
                        </div>
                        <a href="{{ route('nozzle.meter.create') }}" 
                            class="btn btn-sm d-flex align-items-center justify-content-center mt-2 mt-sm-0"
                            style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Add 
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="meterTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important">#</th>
                                        <th style="color:white !important">Nozzle</th>
                                        <th style="color:white !important">Fuel Type</th>
                                        <th style="color:white !important">Previous</th>
                                        <th style="color:white !important">Current</th>
                                        <th style="color:white !important">Sold Quantity</th>
                                        <th style="color:white !important">Date</th>
                                        <th class="text-center" style="color:white !important">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($meters as $key => $meter)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $meter->nozzle->name ?? 'N/A' }}</td>
                                            <td>{{ $meter->nozzle->fuelType->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($meter->prev_meter, 3) }}</td>
                                            <td>{{ number_format($meter->curr_meter, 3) }}</td>
                                            <td>{{ number_format($meter->sold_quantity, 3) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($meter->date)->format('d M, Y') }}</td>
                                            
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex flex-row justify-content-center" style="gap: 5px;">
                                                    <div>
                                                        <a href="{{ route('nozzle.meter.edit', $meter->id) }}" 
                                                           class="btn btn-sm btn-edit">
                                                           <i class="fa fa-edit me-1"></i> 
                                                        </a>
                                                    </div>

                                                    <div>
                                                        <form action="{{ route('nozzle.meter.destroy', $meter->id) }}" method="GET">
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
                                                <td colspan="8" class="text-center text-muted py-3">No meter data found.</td>
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
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#meterTable').DataTable({
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
    
    $(document).ready(function() {
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
    
            Swal.fire({
                title: 'Are you sure?',
                text: "This record will be permanently deleted!",
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
