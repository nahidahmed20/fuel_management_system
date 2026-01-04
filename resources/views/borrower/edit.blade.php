@extends('master')

@section('title', 'Edit Borrower')

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
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-user-edit me-2"></i> Edit Borrower
                            </h4>
                        </div>
                        <a href="{{ route('borrowers.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Borrower List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('borrowers.update', $borrower) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Borrower Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ $borrower->name }}" class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input type="text" name="mobile" value="{{ $borrower->mobile }}" class="form-control">
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3">{{ $borrower->address }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-lg-start">
                                <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #ff7300, #f85108); padding: 8px 16px; border-radius: 5px; border: none;">
                                    Update Borrower
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
