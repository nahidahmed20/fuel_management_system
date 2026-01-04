@extends('master')

@section('title', 'Edit Loan Payment')

@push('style')
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
        border-radius: 0px !important;
    }

    .card-header {
        background-color: #27548A;
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .card-title {
        margin: 0;
        font-weight: bold;
        font-size: 1.25rem;
    }

    .btn-back {
        background: linear-gradient(45deg, #36D1DC, #5B86E5);
        color: white;
        border: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 5px;
        display: flex;
        align-items: center;
    }

    .btn-back:hover {
        background: linear-gradient(45deg, #2bb3c9, #4d72d8);
        color: white;
    }

    button[type="submit"] {
        background: linear-gradient(45deg, #0f9b8e, #129990);
        color: white;
        padding: 8px 16px;
        border-radius: 5px;
        font-weight: 600;
        border: none;
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
                    {{-- Header --}}
                    
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                           <h4 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i> Edit Loan Payment
                            </h4>
                        </div>
                        <a href="{{ route('customers.index') }}" 
                        class="btn btn-sm d-flex align-items-center justify-content-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Customers
                        </a>

                    </div>

                    {{-- Form --}}
                    <div class="card-body">
                        <form action="{{ route('loan-payments.update', $loanPayment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="borrower_id" value="{{ $loanPayment->borrower_id }}">

                            <div class="mb-3">
                                <label class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" step="any" class="form-control" required
                                       value="{{ old('amount', $loanPayment->amount) }}" placeholder="e.g. 5000">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control" required
                                       value="{{ old('payment_date', $loanPayment->payment_date) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="Optional note...">{{ old('note', $loanPayment->note) }}</textarea>
                            </div>

                            {{-- <button type="submit">
                                 Update Payment
                            </button> --}}
                            <button type="submit" 
                                    class="btn text-white fw-semibold d-flex align-items-center justify-content-center"
                                    style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                 Update Type
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end -->
    </div>
</div>
@endsection
