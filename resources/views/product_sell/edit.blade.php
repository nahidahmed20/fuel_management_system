@extends('master')

@section('title')
    Edit Product Sell
@endsection

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 4px 12px;
        border: 1px solid #ced4da;
        border-radius: 2px;
    }

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
                <div class="card shadow-lg border-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i> Edit Product Sell
                            </h4>
                        </div>
                        <a href="{{ route('product.sales.index') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                           Sell List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.sales.update', $sale->id) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="stockOutProduct" class="form-label fw-bold text-dark">Select Product</label>
                                    <select name="product_id" id="stockOutProduct" class="form-select select2" required>
                                        <option value="" disabled>-- Select Product --</option>
                                        @foreach($products as $product)
                                            @php
                                                $sellingPrice = $latestSellingPrices[$product->id] ?? 0;
                                                $buyingPrice = $latestBuyingPrices[$product->id] ?? 0;
                                            @endphp
                                            <option 
                                                value="{{ $product->id }}" 
                                                data-selling="{{ $sellingPrice }}" 
                                                data-buying="{{ $buyingPrice }}"
                                                {{ $product->id == $sale->product_id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="stockOutQty" class="form-label fw-bold text-dark">Quantity</label>
                                    <input type="number" step="any" name="quantity" id="stockOutQty" class="form-control" value="{{ $sale->quantity }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="stockOutTotal" class="form-label fw-bold text-dark">Total Sell (৳)</label>
                                    <input type="text" id="stockOutTotal" class="form-control" value="{{ $sale->total_price }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="stockOutDate" class="form-label fw-bold text-dark">Date</label>
                                    <input type="date" name="date" id="stockOutDate" class="form-control" value="{{ $sale->date }}" required>
                                </div>
                            </div>

                            {{-- Hidden fields --}}
                            <input type="hidden" name="total_price" id="hiddenTotalSell" value="{{ $sale->total_price }}">
                            <input type="hidden" name="total_buy" id="stockOutTotalBuy" value="{{ $sale->total_buy }}">

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn mt-4 text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 6px 16px; border-radius: 2px; border: none;">
                                    Update
                                </button>
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
    $(document).ready(function () {
        $('#stockOutProduct').select2({
            placeholder: "-- Select Product --",
            allowClear: true,
            width: '100%'
        });

        function calculateTotals() {
            const selected = $('#stockOutProduct option:selected');
            const quantity = parseFloat($('#stockOutQty').val()) || 0;
            const selling = parseFloat(selected.data('selling')) || 0;
            const buying = parseFloat(selected.data('buying')) || 0;

            const totalSell = (quantity * selling).toFixed(2);
            const totalBuy = (quantity * buying).toFixed(2);

            $('#stockOutTotal').val(totalSell);
            $('#hiddenTotalSell').val(totalSell);
            $('#stockOutTotalBuy').val(totalBuy);
        }

        $('#stockOutProduct, #stockOutQty').on('change keyup', calculateTotals);

        // Trigger once to initialize
        calculateTotals();
    });
</script>
@endpush
