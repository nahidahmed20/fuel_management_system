@extends('master')

@section('title', 'Edit Role')

@section('content')
<style>
    .custom-checkbox .form-check-input {
        transform: scale(1.5);
        cursor: pointer;
    }

    .custom-checkbox .form-check-label {
        cursor: pointer;
        font-size: 17px;
    }
</style>
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="card shadow-sm rounded-0">

            <div class="card-header" style="background:#002c7c;color:white">
                <h5 class="mb-0">Edit Role</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 col-md-4">
                        <label class="form-label">Role Name</label>
                        <input type="text"
                               name="name"
                               class="form-control rounded-0"
                               value="{{ old('name', $role->name) }}"
                               required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3 col-12">
                        <label class="form-label fw-semibold">Permissions</label>

                        <div class="row ms-2" style="margin-left: -10px;">
                            @foreach($permissions as $permission)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check d-flex align-items-center gap-2 custom-checkbox">
                                        <input class="form-check-input mt-0"
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            id="permission_{{ $permission->id }}"
                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>

                                        <label class="form-check-label mb-0"
                                            for="permission_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    
                    
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.index') }}"
                        class="btn btn-secondary rounded-0"
                        style="font-size: 13px">
                            Back
                        </a>
                        <button class="btn btn-primary rounded-0" style="font-size: 13px">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
