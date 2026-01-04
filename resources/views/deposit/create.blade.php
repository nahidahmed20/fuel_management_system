@extends('master')

@section('title')
   Add New Account Transaction
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
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-wallet me-2"></i> Add New Account Transaction
                            </h4>
                        </div>
                        <a href="{{ route('account.index') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Account List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('account.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Transaction Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Office Rent" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="deposit" selected>Deposit</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount (৳)</label>
                                    <input type="number" step="any" name="amount" class="form-control" placeholder="e.g. 1500.000" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="deposited_by" class="form-label">Deposited </label>
                                    <input type="text" name="deposited_by" class="form-control" placeholder="e.g. Nahidul Islam" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="note" class="form-label">Note (Optional)</label>
                                    <textarea name="note" class="form-control" rows="3" placeholder="e.g. Monthly office rent"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn text-white mt-2" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                        Save Transaction
                                    </button>
                                </div>
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
