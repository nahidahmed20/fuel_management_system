@extends('master')

@section('title', 'Edit User')

@push('style')
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
    }

    .image-upload .form-control {
        line-height: 18px !important;
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

        button[type="submit"] {
            width: 100%;
        }
    }

    #preview-container img {
        object-fit: cover;
        height: 150px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-12 mx-auto">
                <div class="card shadow-sm">
                    {{-- Card Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h4 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i> Edit User</h4>
                        <a href="{{ route('user.list') }}" 
                            class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                            style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 0px;">
                             User List
                        </a>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                {{-- Name --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>

                                {{-- Phone --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone) }}" required>
                                </div>

                                {{-- Email --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>

                                {{-- Gender --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">-- Select Gender --</option>
                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                {{-- Role --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-control">
                                        <option value="">-- Select Role --</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Password (optional) --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters">
                                </div>

                                {{-- Address as Input Field --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                                </div>

                                {{-- User Image --}}
                                <div class="mb-3 col-md-4 image-upload">
                                    <label for="user_image" class="form-label fw-semibold">User Image</label>
                                    <input class="form-control" type="file" id="user_image" name="user_image" accept="image/*" onchange="previewImage(event)">
                                </div>

                                {{-- Image Preview --}}
                                <div class="mb-3 col-md-4" id="preview-container" style="display: block;">
                                    <div class="card shadow-sm">
                                        <img id="image_preview" src="{{ asset($user->image) }}" alt="Image Preview" class="card-img-top rounded">
                                        <div class="card-body p-2 text-center">
                                            <small class="text-muted">Current Image Preview</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                <button type="submit" class="btn text-white" 
                                    style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 6px 16px; border-radius: 0px; border: none;">
                                     Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('preview-container');
    const preview = document.getElementById('image_preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "{{ asset($user->image) }}";
        previewContainer.style.display = 'block';
    }
}
</script>
@endpush
