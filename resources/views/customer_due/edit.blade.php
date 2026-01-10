@extends('master')

@section('title', 'Edit Customer Due')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: none;
    }

    .card-header {
        background: linear-gradient(45deg, #27548A, #1e3c72);
        color: #fff;
        border-radius: 10px 10px 0 0;
    }
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 0px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 22px !important;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }

    .select2-selection__rendered {
        line-height: 28px !important;
    }

    .select2-selection__arrow {
        height: 100% !important;
    }

    .btn-gradient-update {
        background: linear-gradient(45deg, #ff7300, #f85108);
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 6px;
        transition: all .3s ease;
    }

    .btn-gradient-update:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .btn-list {
        background: linear-gradient(45deg, #36D1DC, #5B86E5);
        color: #fff;
        border-radius: 6px;
        font-size: 13px;
    }

    @media (max-width: 576px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .card-header a {
            width: 100%;
            margin-top: 10px;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-lg-12 mx-auto">

                <div class="card">
                    <!-- Header -->
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: #046064">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Customer Due
                        </h4>
                        <a href="{{ route('customer_due.index') }}" class="btn btn-sm btn-list" style="border-radius: 2px">
                            <i class="fas fa-list me-1"></i> Due List
                        </a>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <form action="{{ route('customer_due.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $due->id }}">

                            <div class="row g-3">
                                <!-- Customer -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Customer <span class="text-danger">*</span>
                                    </label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $due->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->mobile }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Amount -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Amount Due (৳) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="amount_due" step="any"
                                           class="form-control"
                                           value="{{ $due->amount_due }}" required>
                                </div>

                                <!-- Due Date -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Due Date</label>
                                    <input type="date" name="due_date" id="due_date"
                                           class="form-control"
                                           value="{{ $due->due_date }}">
                                </div>

                                <!-- Note -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Note</label>
                                    <textarea name="note" rows="2"
                                              class="form-control"
                                              placeholder="Optional note...">{{ $due->note }}</textarea>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-md-end justify-content-center mt-3">
                                        <button type="submit" class="btn text-white px-4" style="background-color:#129990;border-radius: 2px; padding: 4px;">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function () {
        $('.select2').select2({
            placeholder: "Select Customer",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
