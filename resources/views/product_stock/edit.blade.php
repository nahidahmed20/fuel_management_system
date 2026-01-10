@extends('master')

@section('title', 'Edit Product Stock')

@push('style')
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 0px !important;
    }
    .select2-selection__rendered {
        line-height: 22px !important;
    }
    .select2-container .select2-selection--single{
        height: 38px !important;
    }
    .remove-row {
        padding: 2px 8px;
        font-size: 16px;
        line-height: 1;
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card shadow-lg">
                    {{-- Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
                         style="background-color:#27548A;color:#fff;">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Product Stock
                        </h4>

                        <a href="{{ route('product.stock.index') }}"
                           class="btn btn-sm mt-2 mt-sm-0"
                           style="background:linear-gradient(45deg,#36D1DC,#5B86E5);
                           color:#fff;border:none;border-radius:2px;">
                            Stock List
                        </a>
                    </div>

                    {{-- Body --}}
                    <div class="card-body">
                        <form action="{{ route('product.stock.update', $purchase->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Top Controls --}}
                            <div class="row align-items-end">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Search Product (Name / SKU)</label>
                                    <select id="product_selector" class="form-select select2">
                                        <option value="">-- Search Product --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="fw-bold">Date</label>
                                    <input type="date" name="date" class="form-control"
                                           value="{{ old('date', $purchase->date) }}" required>
                                </div>
                            </div>

                            {{-- Products Table --}}
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered align-middle" id="stockTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Buying Price</th>
                                            <th width="15%">Selling Price</th>
                                            <th width="5%" class="text-center">×</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {{-- Existing Rows --}}
                                        @foreach($stockItems as $key => $item)
                                            <tr id="row-{{ $item->product_id }}">
                                                <td>
                                                    {{ $item->product->name }} ({{ $item->product->sku }})
                                                    <input type="hidden"
                                                           name="products[{{ $key }}][stock_id]"
                                                           value="{{ $item->id }}">
                                                    <input type="hidden"
                                                           name="products[{{ $key }}][product_id]"
                                                           value="{{ $item->product_id }}">
                                                </td>

                                                <td>
                                                    <input type="number" step="any"
                                                           name="products[{{ $key }}][quantity]"
                                                           class="form-control"
                                                           value="{{ $item->quantity }}"
                                                           required>
                                                </td>

                                                <td>
                                                    <input type="number" step="any"
                                                           name="products[{{ $key }}][buying_price]"
                                                           class="form-control"
                                                           value="{{ $item->buying_price }}"
                                                           required>
                                                </td>

                                                <td>
                                                    <input type="number" step="any"
                                                           name="products[{{ $key }}][selling_price]"
                                                           class="form-control"
                                                           value="{{ $item->selling_price }}"
                                                           required>
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                                        ×
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{-- Submit --}}
                            <div class="text-end mt-3">
                                <button type="submit" class="btn text-white"
                                        style="background:linear-gradient(45deg,#0f9b8e,#129990);
                                        padding:6px 16px;border-radius:2px;border:none;">
                                    Update Stock
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

    $('#product_selector').select2({
        placeholder: "Search product by name or SKU",
        width: '100%'
    });

    let index = {{ $stockItems->count() }};

    $('#product_selector').on('change', function () {
        let productId   = $(this).val();
        let productText = $(this).find('option:selected').text();

        if (!productId) return;

        if ($('#row-' + productId).length) {
            alert('This product already added!');
            $(this).val(null).trigger('change');
            return;
        }

        let row = `
            <tr id="row-${productId}">
                <td>
                    ${productText}
                    <input type="hidden" name="products[${index}][product_id]" value="${productId}">
                </td>

                <td>
                    <input type="number" step="any" name="products[${index}][quantity]" class="form-control" required>
                </td>

                <td>
                    <input type="number" step="any" name="products[${index}][buying_price]" class="form-control" required>
                </td>

                <td>
                    <input type="number" step="any" name="products[${index}][selling_price]" class="form-control" required>
                </td>

                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row">×</button>
                </td>
            </tr>
        `;

        $('#stockTable tbody').append(row);
        index++;
        $(this).val(null).trigger('change');
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

});
</script>
@endpush
