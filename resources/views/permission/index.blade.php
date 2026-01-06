@extends('master')

@section('title', 'Permission List')

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
    </style>
@endpush
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm rounded-0">

                    {{-- Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="background:#002c7c;color:#fff">
                        <h5 class="mb-0">Permission List</h5>
                        <button class="btn btn-primary btn-sm rounded-0"
                                data-bs-toggle="modal"
                                data-bs-target="#permissionModal">
                            + Add Permission
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle"
                                   id="permissionTable">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th width="50">#</th>
                                        <th>Permission Name</th>
                                        <th>Guard</th>
                                        <th>Created At</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td class="text-center">{{ $permission->guard_name }}</td>
                                            <td class="text-center">
                                                {{ $permission->created_at->format('d M Y') }}
                                            </td>
                                            <td>
                                                <div class="action-btn-group d-flex justify-content-center gap-1">

                                                    {{-- Edit --}}
                                                    <button type="button"
                                                            class="btn btn-sm  text-white edit-btn"
                                                            data-id="{{ $permission->id }}"
                                                            data-name="{{ $permission->name }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    {{-- Delete --}}
                                                    <button type="button"
                                                            class="btn btn-sm  delete-btn"
                                                            data-id="{{ $permission->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                    {{-- Hidden delete form --}}
                                                    <form id="delete-form-{{ $permission->id }}"
                                                          action="{{ route('permissions.destroy', $permission->id) }}"
                                                          method="POST" style="display:none;">
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
        </div>
    </div>
</div>

{{-- Add Permission Modal --}}
@include('permission.create')

{{-- Edit Permission Modal --}}
@include('permission.edit')

@endsection

@push('style')
<style>
    .action-btn-group .btn{
        border-radius:0;
        padding:4px 8px;
        line-height:1;
    }
    .action-btn-group .btn i{
        font-size:13px;
    }
</style>
@endpush

@push('script')
{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#permissionTable').DataTable({
        order: [[0, 'desc']]
    });

    $(document).on('click', '.edit-btn', function () {
        let id   = $(this).data('id');
        let name = $(this).data('name');

        $('#editPermissionForm').attr(
            'action',
            "{{ route('permissions.update', ':id') }}".replace(':id', id)
        );

        $('#editPermissionName').val(name);

        $('#editPermissionModal').modal('show');
    });

    $(document).on('click', '.delete-btn', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This permission will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form-' + id).submit();
            }
        });
    });

});
</script>

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

@if($errors->any())
<script>
    $(document).ready(function(){
        $('#permissionModal').modal('show');
    });
</script>
@endif
@endpush
