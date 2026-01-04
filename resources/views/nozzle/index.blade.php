@extends('master')

@section('title')
    Nozzle List
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

    /* Hover effect */
    #nozzle_list tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-edit {
        background-color: #20c997;
        color: white;
        padding: 4px 10px;
    }

    .btn-delete {
        background-color: #e63946;
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
        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .card-header-custom h4 {
            font-size: 1.1rem;
        }

        .table th,
        .table td {
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
                        <h4 style="font-weight: bold;"><i class="bi bi-funnel-fill me-2"></i>Nozzle List</h4>

                        <a href="{{ route('nozzle.create') }}" class="btn btn-info btn-sm" style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fa fa-plus-circle me-1"></i> Add Nozzle
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive rounded my-3">
                            <table class="table mb-0" id="nozzle_list">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important;">#</th>
                                        <th style="color:white !important;">Nozzle Name</th>
                                        <th style="color:white !important;">Fuel Type</th>
                                        <th class="text-center" style="color:white !important; width: 20%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nozzles as $nozzle)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $nozzle->name }}</td>
                                            <td>{{ $nozzle->fuelType->name?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex flex-row justify-content-center" style="gap: 5px;">
                                                    <div>
                                                        <a href="{{ route('nozzle.edit', $nozzle->id) }}" 
                                                           class="btn btn-sm btn-edit">
                                                           <i class="fa fa-edit me-1"></i> Edit
                                                        </a>
                                                    </div>

                                                    <div>
                                                        <form action="{{ route('nozzle.destroy', $nozzle->id) }}" method="GET">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-delete delete-btn">
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
                                                <td colspan="4" class="text-center text-muted py-3">
                                                    No nozzles found.
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#nozzle_list').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            ordering: true,
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
    $(document).ready(function(){
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
