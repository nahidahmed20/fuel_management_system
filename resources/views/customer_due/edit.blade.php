@extends('master')

@section('title', 'Edit Customer Due')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
    }

    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
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
                                <i class="fas fa-edit me-2"></i> Edit Customer Due
                            </h4>
                        </div>
                        <a href="{{ route('customer_due.index') }}"
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Due List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('customer_due.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $due->id }}">

                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Select Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-control select2" required>
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $due->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->mobile }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="amount_due" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="amount_due" class="form-control" value="{{ $due->amount_due }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $due->due_date }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="2">{{ $due->note }}</textarea>
                            </div>

                            <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #ff7300, #f85108); padding: 8px 16px; border-radius: 5px; border: none;">
                                 Update Due
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

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "-- Select Customer --",
            allowClear: true
        });
    });
</script>
<script>
    $(document).ready(function () {
        let today = new Date().toISOString().split('T')[0];
        $('#due_date').val(today);
    });
</script>
@endpush
