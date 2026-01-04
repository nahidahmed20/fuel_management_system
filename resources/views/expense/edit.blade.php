@extends('master')

@section('title')
   Edit Expense
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
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-pencil-square me-2"></i> Edit Expense
                            </h4>
                        </div>
                        <a href="{{ route('expense.index') }}"
                        class="btn btn-sm d-flex align-items-center justify-content-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Expense List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('expense.update', $expense->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" name="category" class="form-control" value="{{ $expense->category }}">
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="amount" class="form-control" value="{{ $expense->amount }}" required>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" class="form-control" value="{{ $expense->date }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="2">{{ $expense->note }}</textarea>
                            </div>

                            <div class="d-flex justify-content-start">
                                <button type="submit"
                                    class="btn text-white fw-semibold d-flex align-items-center justify-content-center"
                                        style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                    Update Expense
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
@endpush
