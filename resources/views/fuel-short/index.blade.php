@extends('master')

@section('title')
Fuel Short List
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
                        <h4 class="card-title mb-0 fw-bold">
                            <i class="bi bi-droplet-half me-2"></i> Fuel Short List
                        </h4>

                        <a href="{{ route('fuel.short.create') }}" 
                        class="btn btn-sm d-flex align-items-center" 
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Add Fuel Short
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0" id="fuel_short_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 5%; color:white !important">#</th>
                                        <th style="color:white !important">Fuel Name</th>
                                        <th style="color:white !important">Short Type</th>
                                        {{-- <th style="color:white !important">Short Amount</th> --}}
                                        <th style="color:white !important">Short Price</th>
                                        <th style="color:white !important">Short Date</th>
                                        <th class="text-center" style="width: 20%; color:white !important">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fuelShorts as $fuel)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $fuel->fuelType->name }}</td>
                                            <td>
                                                @if ($fuel->short_type == '+')
                                                   (+) Excess (অতিরিক্ত)
                                                @elseif ($fuel->short_type == '-')
                                                   (-) Shortage (ঘাটতি)
                                                @else
                                                    Unknown
                                                @endif
                                            </td>

                                            {{-- <td>{{ $fuel->short_amount }}</td> --}}
                                            <td>{{ $fuel->price }}</td>
                                            <td>{{ $fuel->date }}</td>
                                            <td class="text-center">
                                                <div class="action-btn-group d-flex flex-row justify-content-center">
                                                    <div style="margin-right: 2px">
                                                        <a href="{{ route('fuel.short.edit', $fuel->id) }}"
                                                           class="btn btn-sm btn-edit">
                                                            <i class="fas fa-edit me-1"></i> 
                                                        </a>
                                                    </div>
                                                    <div style="margin-left: 2px">
                                                        <form action="{{ route('fuel.short.destroy', $fuel->id) }}" method="GET" class="delete-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-delete">
                                                                <i class="fas fa-trash me-1"></i> 
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    No fuel shorts found.
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
       
        $('#fuel_short_table').DataTable({
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

        $('.btn-delete').on('click', function (e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
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
