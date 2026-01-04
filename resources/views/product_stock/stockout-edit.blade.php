@extends('master')

@section('title', 'Edit Product Sell')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between" style="background-color: #0d6efd; color: #fff;">
                        <div class="header-title">
                            <h4 class="mb-0">Edit Product Sell</h4>
                        </div>
                        <a href="{{ route('product.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stockout.update', $out->id) }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Product Select -->
                                <div class="col-md-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select name="product_id" id="product_id" class="form-control" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $out->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" step="any" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $out->quantity) }}" required>
                                </div>

                               <!-- Total Buy (Hidden) -->
                                <input type="hidden" name="total_buy" id="total_buy" value="{{ old('total_buy', $out->total_buy ?? 0) }}">



                                <!-- Total Price -->
                                <div class="col-md-3">
                                    <label for="total_price_display" class="form-label">Total Price</label>
                                    <input type="text" id="total_price_display" class="form-control" value="{{ number_format($out->total_price, 2) }}" readonly>
                                    <input type="hidden" name="total_price" id="total_price" value="{{ $out->total_price }}">
                                </div>

                                <!-- Date -->
                                <div class="col-md-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', $out->date) }}" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success"> Update Sell
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
<script>
    $(document).ready(function () {
        const unitPrice = {{ $out->total_price / $out->quantity }};
        const buyingPrice = {{ $buyingPrice ?? 0 }};

        $('#quantity').on('input', function () {
            const quantity = parseFloat($(this).val()) || 0;
            const totalSell = (quantity * unitPrice).toFixed(2);
            const totalBuy = (quantity * buyingPrice).toFixed(2);

            $('#total_price_display').val(totalSell);
            $('#total_price').val(totalSell);
            $('#total_buy').val(totalBuy);
        });
    });
</script>
@endpush
