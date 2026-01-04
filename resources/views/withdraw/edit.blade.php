@extends('master')

@section('title')
Edit Cash Withdraw
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
        .card-header .btn, button[type="submit"] {
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }
        .card-title {
            font-size: 1.1rem;
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
                                <i class="bi bi-pencil-square me-2"></i> Edit Cash Withdraw
                            </h4>
                        </div>
                        <a href="{{ route('cash.withdraw.index') }}" class="btn btn-sm" style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white;">
                            <i class="fas fa-arrow-left me-1"></i> Withdraw List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cash.withdraw.update', $withdraw->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Withdraw From</label>
                                    <input type="text" name="name" value="{{ old('name', $withdraw->name) }}" class="form-control" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" name="type" value="{{ old('type', $withdraw->type) }}" class="form-control">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" step="0.001" name="amount" value="{{ old('amount', $withdraw->amount) }}" class="form-control" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="withdraw_by" class="form-label">Withdraw By</label>
                                    <input type="text" name="withdraw_by" value="{{ old('withdraw_by', $withdraw->withdraw_by) }}" class="form-control">
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea name="note" class="form-control">{{ old('note', $withdraw->note) }}</textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px;">Update Withdraw</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
