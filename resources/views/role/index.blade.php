@extends('master')

@section('title', 'Role List')

@section('content')
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
    .action-btn-group .btn-edit {
        background-color: #0d6efd;
        color: #fff;
        border-radius: 0;
        padding: 4px 8px;
    }

    .action-btn-group .btn-edit:hover {
        background-color: #0b5ed7;
        color: #fff;
    }

    .action-btn-group .btn-delete {
        background-color: #dc3545;
        color: #fff;
        border-radius: 0;
        padding: 4px 8px;
    }

    .action-btn-group .btn-delete:hover {
        background-color: #bb2d3b;
        color: #fff;
    }

    /* Center align icons */
    .action-btn-group .btn i {
        font-size: 14px;
    }
    </style>
@endpush
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="card shadow-sm rounded-0">

            {{-- Header --}}
            <div class="card-header d-flex justify-content-between align-items-center"
                 style="background:#002c7c;color:white">
                <h5 class="mb-0">Role List</h5>
                <a href="{{ route('roles.create') }}"
                   class="btn btn-primary btn-sm rounded-0">
                    + Add Role
                </a>
            </div>

            {{-- Body --}}
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle" id="roleTable">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="50" style="color: white !important">#</th>
                            <th style="color: white !important">Role Name</th>
                            <th style="color: white !important">Permissions</th>
                            <th width="120" style="color: white !important">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr class="role-row">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="role-name">{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge badge-permission" style="color: white !important">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">

                                    {{-- Edit --}}
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                    class="btn btn-sm btn-edit"
                                    title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <button class="btn btn-sm btn-delete"
                                            data-id="{{ $role->id }}"
                                            title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    {{-- Hidden Delete Form --}}
                                    <form id="delete-form-{{ $role->id }}"
                                        action="{{ route('roles.destroy', $role->id) }}"
                                        method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



@push('script')

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    // Initialize DataTable
    $('#roleTable').DataTable({
        order: [[0, 'desc']]
    });

    // DELETE BUTTON with SweetAlert
    $('#roleTable').on('click', '.btn-delete', function (e) {
        e.preventDefault(); // Prevent default button behavior
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This role will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the hidden form
                $('#delete-form-' + id).submit();
            }
        });
    });

});

</script>

{{-- Success Toast --}}
@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif
@endpush
