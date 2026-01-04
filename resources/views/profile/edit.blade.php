@extends('master')

@section('title')
    Edit Profile
@endsection


<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
    }
</style>


@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12 col-md-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between" style="background-color: #0d6efd; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title">Edit Profile</h4>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="male" {{ Auth::user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ Auth::user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ Auth::user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ Auth::user()->address }}">
                            </div>

                            <div class="mb-3">
                                <label for="password">New Password <small>(Optional)</small></label>
                                <input type="password" name="password" id="password" class="form-control" minlength="8" placeholder="Enter new password if you want to change">
                                <small id="passwordHelp" class="form-text text-muted"></small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password">
                                <small id="confirmHelp" class="form-text text-danger"></small>
                            </div>

                            <div class="mb-3">
                                <label for="user_image" class="form-label fw-bold">Profile Image</label>
                                
                                <div class="input-group">
                                    <input type="file" name="user_image" id="user_image" class="form-control">
                                </div>

                                @if(Auth::user()->image)
                                    <div class="mt-2">
                                        <img src="{{ asset(Auth::user()->image) }}" alt="Profile Image" class="rounded border" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>


                            <button type="submit" class="btn btn-success">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#password').on('input', function () {
            const password = $(this).val();
            if (password.length > 0 && password.length < 8) {
                $('#passwordHelp').text('Password must be at least 8 characters.').addClass('text-danger');
            } else {
                $('#passwordHelp').text('').removeClass('text-danger');
            }
        });

        $('#password_confirmation').on('input', function () {
            const confirm = $(this).val();
            const password = $('#password').val();
            if (confirm !== password) {
                $('#confirmHelp').text('Passwords do not match!').addClass('text-danger');
            } else {
                $('#confirmHelp').text('').removeClass('text-danger');
            }
        });
    });
</script>


