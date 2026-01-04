@extends('master')

@section('title', 'Edit Customer Payment')

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
            <div class="col-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-money-check-alt me-2"></i> Edit Customer Payment - {{ $customer->name }}
                            </h4>
                        </div>
                        <a href="{{ route('customer-due-payments.index', ['customer_id' => $customer->id]) }}" 
                            class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                            style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Payments
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('customer-due-payments.update', $customerDuePayment->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date" 
                                    class="form-control @error('payment_date') is-invalid @enderror"
                                    value="{{ old('payment_date', \Carbon\Carbon::parse($customerDuePayment->payment_date)->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="amount" id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount', $customerDuePayment->amount) }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note (optional)</label>
                                <textarea name="note" id="note" rows="3" class="form-control">{{ old('note', $customerDuePayment->note) }}</textarea>
                            </div>

                            <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #ff7300, #f85108); padding: 8px 16px; border-radius: 5px; border: none;">
                                Update Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<!-- Optional: Add any specific scripts here -->
@endpush
