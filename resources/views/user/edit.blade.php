@extends('master')

@section('title')
    Edit User
@endsection

@push('style')
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
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

        .card-title {
            font-size: 1.1rem;
        }

        button[type="submit"] {
            width: 100%;
        }

    }
    .image-upload .form-control {
        line-height: 25px !important;
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-user-edit me-2"></i> Edit User
                            </h4>
                        </div>
                        <a href="{{ route('user.list') }}"
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> User List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone_number" class="form-control" value="{{ $user->phone }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" required>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password (Optional)</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep old password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3">{{ $user->address }}</textarea>
                            </div>

                            <div class="mb-3 image-upload ">
                                <label for="user_image" class="form-label">Image</label>
                                @if(!empty($user->image))
                                    <div class="mb-2">
                                        <img src="{{ asset($user->image) }}" alt="User Image" id="current_image" width="70" height="70" class="rounded shadow-sm">
                                    </div>
                                @endif
                                <input type="file" id="user_image" name="user_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <div class="mt-2" style="display:none;" id="preview_container">
                                    <img id="image_preview" src="#" alt="Image Preview" width="70" height="70" class="rounded shadow-sm">
                                </div>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn text-white"
                                    style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                <i class="fas fa-save me-1"></i> Update User
                            </button>
                            <a href="{{ route('user.list') }}" class="btn btn-secondary ms-2">Cancel</a>
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
        const previewContainer = document.getElementById('preview_container');
        const preview = document.getElementById('image_preview');
        const currentImage = document.getElementById('current_image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
                if(currentImage) {
                    currentImage.style.display = 'none';  
                }
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            previewContainer.style.display = 'none';
            if(currentImage) {
                currentImage.style.display = 'block'; 
            }
        }
    }
</script>
@endpush
