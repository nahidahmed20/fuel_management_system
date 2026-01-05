@extends('master')

@section('title', 'All Borrowers')

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
        font-weight: bold;
    }
    #borrower tbody tr:hover {
        background-color: #f8f9fa;
    }
    .btn-sm {
        padding: 4px 10px;
        font-size: 0.85rem;
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
        .table th, .table td {
            font-size: 0.9rem;
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
                    <div class="card-header-custom d-flex justify-content-between align-items-center mb-2 flex-wrap">
                        <h4><i class="fas fa-user-friends me-2"></i> All Borrowers</h4>
                        <a href="{{ route('borrowers.create') }}" 
                           class="btn btn-sm d-flex align-items-center" 
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Add 
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="borrower">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color: white !important;">#</th>
                                        <th style="color: white !important;">Name</th>
                                        <th style="color: white !important;">Mobile</th>
                                        <th style="color: white !important;">Address</th>
                                        <th class="text-center" style="color: white !important;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($borrowers as $borrower)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $borrower->name }}</td>
                                        <td>{{ $borrower->mobile ?? '-' }}</td>
                                        <td>{{ $borrower->address ?? '-' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm open-action-modal"
                                            id="open-action-modal"
                                                    data-id="{{ $borrower->id }}"
                                                    data-name="{{ $borrower->name }}"
                                                    data-mobile="{{ $borrower->mobile }}"
                                                    style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none;">
                                                <i class="fas fa-ellipsis-h me-1"></i> More Options
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">No borrowers found.</td>
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

{{-- Modal --}}
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header justify-content-between align-items-start text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990);">
                <div>
                    <h5 class="modal-title mb-1" id="actionModalLabel">Borrower Options</h5>
                    <small>
                        <i class="fas fa-user me-1"></i> <span id="modalBorrowerName" class="fw-bold"></span> |
                        <i class="fas fa-phone-alt ms-1 me-1"></i> <span id="modalBorrowerMobile"></span>
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-2 px-2">
                    <div class="col-md-6 col-sm-3 mb-3">
                        <a id="modalEdit" class="btn w-100 text-white" style="background: linear-gradient(45deg, #20c997, #2ecc71);">
                            <i class="fa fa-edit me-1"></i> Edit
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-3">
                        <form id="modalDeleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-100 text-white" style="background: linear-gradient(45deg, #e63946, #c62828);">
                                <i class="fa fa-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 col-sm-3">
                        <a id="modalDetails" class="btn w-100 text-white" style="background: linear-gradient(45deg, #3498db, #2980b9);">
                            <i class="fa fa-eye me-1"></i> Details
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-3">
                        <a id="modalPayment" class="btn w-100 text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990);">
                            <i class="fa fa-money-bill-wave me-1"></i> Payment
                        </a>
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
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#borrower').DataTable({
        responsive: true,
        pagingType: 'simple_numbers',
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'></i>",
                next: "<i class='fas fa-angle-right'></i>"
            }
        }
    });

    $(document).on('click', '#open-action-modal', function () {

        const id = $(this).data('id');
        const name = $(this).data('name');
        const mobile = $(this).data('mobile');

        $('#modalBorrowerName').text(name);
        $('#modalBorrowerMobile').text(mobile);
        $('#modalEdit').attr('href', `/borrowers/${id}/edit`);
        $('#modalDetails').attr('href', `/loan-payments/${id}`);
        $('#modalPayment').attr('href', `/loan-payments/create?borrower_id=${id}`);
        $('#modalDeleteForm').attr('action', `/borrowers/${id}`);

        $('#modalDeleteForm').off('submit').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.currentTarget.submit();
                }
            });
        });

        const modal = new bootstrap.Modal(document.getElementById('actionModal'));
        modal.show();
    });
});
</script>
@endpush
