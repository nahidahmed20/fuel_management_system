@extends('master')

@section('title')
   Edit Product
@endsection

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
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-box-open me-2"></i> Edit Product
                            </h4>
                        </div>
                        <a href="{{ route('product.index') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                            Product List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.stocks.update', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="products[{{ $i }}][stock_id]" value="{{ $stock->id }}">

                            @foreach($stocks as $index => $stock)
                                <div class="row mb-2">
                                    <input type="hidden" name="stocks[{{ $index }}][id]" value="{{ $stock->id }}">

                                    <div class="col-md-3">
                                        <input type="number" step="0.001" name="stocks[{{ $index }}][quantity]"
                                            value="{{ $stock->quantity }}" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" step="0.001" name="stocks[{{ $index }}][buying_price]"
                                            value="{{ $stock->buying_price }}" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" step="0.001" name="stocks[{{ $index }}][selling_price]"
                                            value="{{ $stock->selling_price }}" class="form-control">
                                    </div>
                                </div>
                            @endforeach

                            <button class="btn btn-success">Update All</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
</div>
@endsection

@push('script')
<script>
    const nameInput = document.getElementById('name');
    const skuInput  = document.getElementById('sku');
    const originalName = nameInput.value;
    const originalSku  = skuInput.value;

    nameInput.addEventListener('input', function () {
        let name = this.value.trim();
        if (name === originalName) {
            skuInput.value = originalSku;
            return;
        }

        if (name.length === 0) {
            skuInput.value = '';
            return;
        }


        let random = Math.floor(1000 + Math.random() * 9000);

        skuInput.value = `SK-${random}`;
    });
</script>
@endpush

