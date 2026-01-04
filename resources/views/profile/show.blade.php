@extends('master')

@section('title')
    User Profile
@endsection

@push('style')
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1), 
                    4px 8px 20px rgba(0, 0, 0, 0.05);
    }
    .profile-image {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #0d6efd;
        margin-bottom: 15px;
    }
    .profile-label {
        font-weight: 600;
        color: #333;
    }
    .profile-value {
        color: #555;
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0d6efd; color: #fff;">
                        <h4 class="card-title mb-0">User Profile</h4>
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-warning">Edit Profile</a>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset(Auth::user()->image ?? 'default-user.png') }}" alt="User Image" class="profile-image">

                        <h3 class="mb-2">{{ Auth::user()->name }}</h3>
                        <p class="text-muted mb-4">{{ Auth::user()->email }}</p>

                        <div class="text-start px-4">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label profile-label">Name</label>
                                <div class="col-sm-8 profile-value">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label profile-label">Email</label>
                                <div class="col-sm-8 profile-value">{{ Auth::user()->email }}</div>
                            </div>
                            @if(Auth::user()->phone)
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label profile-label">Phone</label>
                                <div class="col-sm-8 profile-value">{{ Auth::user()->phone }}</div>
                            </div>
                            @endif
                            @if(Auth::user()->address)
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label profile-label">Address</label>
                                <div class="col-sm-8 profile-value">{{ Auth::user()->address }}</div>
                            </div>
                            @endif
                            @if(Auth::user()->role)
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label profile-label">Role</label>
                                <div class="col-sm-8 profile-value text-capitalize">{{ Auth::user()->role }}</div>
                            </div>
                            @endif
                            @if(Auth::user()->created_at)
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label profile-label">Joined At</label>
                                <div class="col-sm-8 profile-value">{{ Auth::user()->created_at->format('d M, Y') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end -->
    </div>
</div>
@endsection
