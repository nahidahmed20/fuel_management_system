@extends('master')

@section('title', 'Add Borrower')

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
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg rounded-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-user-plus me-2"></i> Add Loan By
                            </h4>
                        </div>
                        <a href="{{ route('borrowers.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                             List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('borrowers.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="name" class="form-label"> Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Nahid" required>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input type="text" name="mobile" class="form-control" placeholder="e.g. 017XXXXXXXX">
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="address" class="form-label">Address</label>
                                    <input name="address" class="form-control" rows="3" placeholder="e.g. Chittagong, Bangladesh">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 6px 16px; border-radius: 2px; border: none;">
                                    Save 
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end -->
    </div>
</div>
@endsection

@push('script')
<!-- Optional script section -->
@endpush
