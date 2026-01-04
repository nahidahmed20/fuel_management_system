@extends('master')

@section('title')
    Product Sell
@endsection

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 46px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
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
                                <i class="fas fa-shopping-cart me-2"></i> Product Sell
                            </h4>
                        </div>
                        <a href="{{ route('product.stock.index') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Stock List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.sales.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="stockOutProduct" class="form-label fw-bold text-dark">Select Product</label>
                                    <select name="product_id" id="stockOutProduct" class="form-select select2" required>
                                        <option value="" disabled selected>-- Select Product --</option>
                                        @foreach($products as $product)
                                            @php
                                                $sellingPrice = $latestSellingPrices[$product->id] ?? 0;
                                                $buyingPrice = $latestBuyingPrices[$product->id] ?? 0;
                                            @endphp
                                            <option 
                                                value="{{ $product->id }}" 
                                                data-selling="{{ $sellingPrice }}" 
                                                data-buying="{{ $buyingPrice }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="stockOutQty" class="form-label fw-bold text-dark">Quantity</label>
                                    <input type="number" step="any" name="quantity" id="stockOutQty" class="form-control" placeholder="e.g. 10" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="stockOutTotal" class="form-label fw-bold text-dark">Total Sell (৳)</label>
                                    <input type="text" id="stockOutTotal" class="form-control" placeholder="Auto calculated" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="stockOutDate" class="form-label fw-bold text-dark">Date</label>
                                    <input type="date" name="date" id="stockOutDate" class="form-control" required>
                                </div>
                            </div>

                            {{-- Hidden fields --}}
                            <input type="hidden" name="total_price" id="hiddenTotalSell">
                            <input type="hidden" name="total_buy" id="stockOutTotalBuy">

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn text-white"
                                    style="background: linear-gradient(45deg, #9D50BB, #6E48AA); padding: 8px 16px; border-radius: 5px; border: none;">
                                    <i class="fas fa-check-circle me-1"></i> Stock Sell
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

        // Set today's date
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const formattedDate = `${yyyy}-${mm}-${dd}`;
        $('#stockOutDate').val(formattedDate);
    });
</script>
@endpush
