@extends('master')

@section('title', 'Add Customer Due')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Card Style */
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

    /* Select2 */

    .select2-selection__rendered {
        line-height: 28px !important;
    }

    .select2-selection__arrow {
        height: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 0px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 22px;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }

    /* Total Due Box */
    .total-due-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 1.25rem;
        font-weight: 700;
        color: #dc3545;
        border-left: 4px solid #dc3545;
    }

    /* Buttons */
    .btn-gradient {
        background: linear-gradient(45deg, #0f9b8e, #129990);
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 6px;
        transition: all .3s ease;
    }

    .btn-gradient:hover {
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
                            <i class="fas fa-hand-holding-usd me-2"></i> Add Customer Due
                        </h4>
                        <a href="{{ route('customer_due.index') }}" class="btn btn-sm btn-list">
                            <i class="fas fa-list me-1"></i> Due List
                        </a>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <form action="{{ route('customer_due.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Customer -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Customer <span class="text-danger">*</span>
                                    </label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->name }} ({{ $customer->mobile }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Total Due -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Total Due</label>
                                    <div id="total_due" class="total-due-box">
                                        ৳ 0.00
                                    </div>
                                </div>

                                <!-- Amount Due -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Amount Due (৳) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="amount_due" step="any" class="form-control" required>
                                </div>

                                <!-- Due Date -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control">
                                </div>

                                <!-- Note -->
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Note</label>
                                    <textarea name="note" rows="2" class="form-control" placeholder="Optional note..."></textarea>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-md-end justify-content-center mt-3">
                                        <button type="submit" class="btn text-white px-4" style="background-color:#129990;border-radius: 2px; padding: 4px;">
                                            Save
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function () {
        $('.select2').select2({
            placeholder: "Select Customer",
            allowClear: true,
            width: '100%'
        });

        $('#due_date').val(new Date().toISOString().split('T')[0]);

        $('#customer_id').on('change', function () {
            let customerId = $(this).val();

            if (!customerId) {
                $('#total_due').text('৳ 0.00');
                return;
            }

            $.get("{{ route('customer_due.getTotalDue') }}", { customer_id: customerId })
                .done(function (res) {
                    $('#total_due').text('৳ ' + parseFloat(res.total_due).toFixed(2));
                })
                .fail(function () {
                    $('#total_due').text('Error fetching due');
                });
        });
    });
</script>
@endpush
