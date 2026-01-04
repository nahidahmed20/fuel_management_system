@extends('master')

@section('title')
User List
@endsection

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
        font-weight: 500;
    }

    #user_table tbody tr:hover {
        background-color: #f8f9fa;
    }

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
        color: white;
    }

    .btn-edit {
        background-color: #20c997;
    }

    .btn-delete {
        background-color: #e63946;
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
                                        <td>
                                            @if($user->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="action-btn-group d-flex justify-content-center">
                                                <div style="margin-right: 2px;">
                                                    <a href="{{ route('user.edit', $user->slug) }}" class="btn btn-sm btn-edit">
                                                        <i class="fa fa-edit me-1"></i> Edit
                                                    </a>
                                                </div>
                                                
                                                <div style="margin-left: 2px;">
                                                    <form action="{{ route('user.delete', $user->id) }}" method="GET" class="delete-form">
                                                        @csrf
                                                        <button class="btn btn-sm btn-delete">
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
            const form = $(this).closest('form');

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
