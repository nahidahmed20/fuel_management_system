<!-- resources/views/cash_withdraw/create.blade.php -->
@extends('master')

@section('title')
   Cash Withdraw Create
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
                                <i class="bi bi-cash me-2"></i> Add New Cash Withdraw
                            </h4>
                        </div>
                        <a href="{{ route('cash.withdraw.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Withdraw List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('cash.withdraw.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" placeholder="e.g. Office Rent" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="withdraw" selected>Withdraw</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" step="any" placeholder="e.g. 1500.000" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="withdraw_by" class="form-label">Withdraw By</label>
                                    <input type="text" name="withdraw_by" placeholder="e.g. Nahidul Islam" class="form-control">
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea name="note" rows="3"  placeholder="e.g. Monthly office rent for July 2025"class="form-control"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                Create Withdraw
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
