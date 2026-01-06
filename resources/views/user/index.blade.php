@extends('master')

@section('title')
User List
@endsection

@push('style')
    <style>
    /* Table header */
    #roleTable thead {
        background-color: #002c7c;
        color: #fff;
        font-weight: 600;
    }

    /* Alternate row colors */
    #roleTable tbody tr:nth-child(even) {
        background-color: #f2f7ff;
    }

    #roleTable tbody tr:hover {
        background-color: #d0e1ff;
    }

    /* Role name text */
    .role-name {
        font-weight: 500;
        color: #1e3d7b;
    }

    /* Permission badges */
    .badge-permission {
        background-color: #17a2b8; /* info color */
        color: white;
        margin-bottom: 3px;
        padding: 3px 7px;
        font-size: 12px;
        border-radius: 4px;
    }

    /* Action buttons */
    .action-btn-group .edit-btn {
        background-color: #0d6efd;
        color: #fff;
        border-radius: 0;
        padding: 4px 8px;
    }

    .action-btn-group .edit-btn:hover {
        background-color: #0b5ed7;
        color: #fff;
    }

    .action-btn-group .delete-btn {
        background-color: #dc3545;
        color: #fff;
        border-radius: 0;
        padding: 4px 8px;
    }

    .action-btn-group .delete-btn:hover {
        background-color: #bb2d3b;
        color: #fff;
    }

    /* Center align icons */
    .action-btn-group .btn i {
        font-size: 14px;
    }
    /* Status badges */
    .badge {
        display: inline-block;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        color: #fff;
        text-align: center;
        min-width: 70px;
    }

    /* Active status */
    .badge-active {
        background-color: #28a745; /* green */
        color:white !important;
    }

    /* Inactive status */
    .badge-inactive {
        background-color: #dc3545; /* red */
    }

    /* Optional: hover effect */
    .badge:hover {
        opacity: 0.85;
        transition: 0.3s;
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
                    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title mb-0 fw-bold">
                            <i class="fas fa-users me-2"></i> User List
                        </h4>
                        <a href="{{ route('user.create') }}" 
                        class="btn btn-sm d-flex align-items-center"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-plus-circle me-1"></i> Add User
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0" id="user_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 5%; color: white; !important">#</th>
                                        <th style="color: white !important;">Image</th>
                                        <th style="color: white !important;">Name</th>
                                        <th style="color: white !important;">Email</th>
                                        <th style="color: white !important;">Phone</th>
                                        <th style="color: white !important;">Role</th>
                                        <th style="color: white !important;">Status</th>
                                        <th class="text-center" style="width: 20%; color: white !important;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset($user->image) }}" width="40" height="40" class="rounded-circle" alt="User Image">
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td class="text-center">
                                            @if($user->status == 1)
                                                <span class="badge badge-active">Active</span>
                                            @else
                                                <span class="badge badge-inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-btn-group d-flex justify-content-center gap-1">

                                                {{-- Edit --}}
                                                <a href="{{ route('user.edit', $user->slug) }}"
                                                class="btn btn-sm btn-edit text-white"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-phone="{{ $user->phone }}"
                                                data-role="{{ $user->role }}"
                                                data-status="{{ $user->status }}">
                                                    <i class="fa fa-edit"></i> 
                                                </a>

                                                {{-- Delete --}}
                                                <button type="button"
                                                        class="btn btn-sm btn-delete"
                                                        data-id="{{ $user->id }}">
                                                    <i class="fa fa-trash"></i> 
                                                </button>

                                                {{-- Hidden delete form --}}
                                                <form id="delete-form-{{ $user->id }}"
                                                    action="{{ route('user.delete', $user->id) }}"
                                                    method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                            </div>
                                        </td>

                                    </tr>
                                    @empty
                                    <tfoot>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-3">No users found.</td>
                                        </tr>
                                    </tfoot>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- End Card --}}
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
        $('#user_table').DataTable({
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
</script>
<script>
$(document).ready(function(){
    $('.btn-delete').on('click', function (e) {
        e.preventDefault();
        const userId = $(this).data('id');
        const form = $('#delete-form-' + userId);

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
