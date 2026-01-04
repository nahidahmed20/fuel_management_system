@extends('master')

@section('title', 'Add Customer Due')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
    }

    .select2-container--default .select2-selection--single {
        height: 46px;
        padding: 8px 6px;
        border: 1px solid #ced4da;
        border-radius: 8px;
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
                                <i class="fas fa-hand-holding-usd me-2"></i> Add New Customer Due
                            </h4>
                        </div>
                        <a href="{{ route('customer_due.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Due List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('customer_due.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Select Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-control select2" required>
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }} ({{ $customer->mobile }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total Due</label>
                                <div id="total_due" style="font-weight: bold; font-size: 1.2rem; color: #d9534f;">
                                    ৳ 0.00
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="amount_due" class="form-label">Amount Due (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="amount_due" class="form-control" step="any" required>
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date (Optional)</label>
                                <input type="date" name="due_date" id="due_date" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="2" placeholder="Optional note..."></textarea>
                            </div>

                            <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                Save Due
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<script>
    $(document).ready(function() {
        $('#customer_id').on('change', function() {
            var customerId = $(this).val();
            if (customerId) {
                $.ajax({
                    url: "{{ route('customer_due.getTotalDue') }}", 
                    method: 'GET',
                    data: { customer_id: customerId },
                    success: function(response) {
                        $('#total_due').text('৳ ' + parseFloat(response.total_due).toFixed(2));
                    },
                    error: function() {
                        $('#total_due').text('Error fetching due');
                    }
                });
            } else {
                $('#total_due').text('৳ 0.00');
            }
        });
    });
</script>

@endpush
