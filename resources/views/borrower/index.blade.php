@extends('master')

@section('title', 'All Borrowers')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

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
        font-weight: bold;
    }
    #borrower tbody tr:hover {
        background-color: #f8f9fa;
    }
    .btn-sm {
        padding: 4px 10px;
        font-size: 0.85rem;
    }

    /* Dropdown */
    .action-dropdown {
        position: absolute;
        top: 80%;
        left: 56px;
        background: #fff;
        border-radius: 2px;
        border: 1px solid #e5e7eb;
        min-width: 126px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 999;
        display: none;
    }
    .action-dropdown a,
    .action-dropdown button {
        display: block;
        padding: 8px 12px;
        font-size: 0.85rem;
        text-decoration: none;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
    }
    .action-dropdown a:hover,
    .action-dropdown button:hover {
        background-color: #f1f5f9;
    }

    /* Top control row flex */
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
                    <div class="card-header-custom d-flex justify-content-between align-items-center mb-2 flex-wrap">
                        <h4><i class="fas fa-user-friends me-2"></i> All Borrowers</h4>
                        <a href="{{ route('borrowers.create') }}"
                           class="btn btn-sm d-flex align-items-center"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                            <i class="fas fa-plus-circle me-1"></i> Add 
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="borrower">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color:white !important">#</th>
                                        <th style="color:white !important">Name</th>
                                        <th style="color:white !important">Mobile</th>
                                        <th style="color:white !important">Address</th>
                                        <th class="text-center" style="color:white !important">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @forelse($borrowers as $borrower)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $borrower->name }}</td>
                                        <td>{{ $borrower->mobile ?? '-' }}</td>
                                        <td>{{ $borrower->address ?? '-' }}</td>
                                        <td class="text-center position-relative">

                                            <button class="btn btn-sm action-toggle"
                                                style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border-radius: 2px;">
                                                <i class="fas fa-ellipsis-h"></i> More Options
                                            </button>

                                            {{-- Dropdown --}}
                                            <div class="action-dropdown shadow-sm">
                                                <a href="{{ route('borrowers.edit',$borrower->id) }}">
                                                    <i class="fa fa-edit text-success me-1"></i> Edit
                                                </a>
                                                <a href="{{ route('loan-payments.index',$borrower->id) }}">
                                                    <i class="fa fa-eye text-primary me-1"></i> Details
                                                </a>
                                                <a href="{{ route('loan-payments.create',['borrower_id'=>$borrower->id]) }}">
                                                    <i class="fa fa-money-bill-wave text-info me-1"></i> Payment
                                                </a>
                                                <form action="{{ route('borrowers.destroy',$borrower->id) }}"
                                                      method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger">
                                                        <i class="fa fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            No borrowers found.
                                        </td>
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
    $(document).ready(function () {

        $('#borrower').DataTable({
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

        // Toggle dropdown
        $(document).on('click', '.action-toggle', function(e){
            e.stopPropagation();
            $('.action-dropdown').hide();
            $(this).next('.action-dropdown').toggle();
        });

        // Click outside
        $(document).on('click', function(){
            $('.action-dropdown').hide();
        });

        // Delete confirm
        $(document).on('submit', '.delete-form', function(e){
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33'
            }).then((result)=>{
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });

    });
</script>
@endpush
