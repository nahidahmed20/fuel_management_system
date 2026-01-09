@extends('master')

@section('title', 'Edit Product Stock')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-selection__arrow {
        height: 100%;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 23px !important;
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

    .card {
        box-shadow: 2px 4px 10px rgba(0,0,0,0.1),
                    4px 8px 20px rgba(0,0,0,0.05);
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
        <div class="row justify-content-center">
            <div class="col-md-12 col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Product Stock
                        </h4>
                        <a href="{{ route('product.stock.index') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                             Stock List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.stock.update', $stock->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="product_id" class="form-label fw-bold text-dark">Select Product</label>
                                    <select name="product_id" id="product_id" class="form-select" required>
                                        <option value="" disabled selected>-- Choose a Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $stock->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" step="any" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $stock->quantity) }}" required>
                                    @error('quantity')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="buying_price" class="form-label">Buying Price (৳)</label>
                                    <input type="number" step="any" name="buying_price" id="buying_price" class="form-control" value="{{ old('buying_price', $stock->buying_price) }}" required>
                                    @error('buying_price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="selling_price" class="form-label">Selling Price (৳)</label>
                                    <input type="number" step="any" name="selling_price" id="selling_price" class="form-control" value="{{ old('selling_price', $stock->selling_price) }}" required>
                                    @error('selling_price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $stock->date) }}" required>
                                    @error('date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 6px 16px; border-radius: 2px; border: none;">
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
        $(document).ready(function() {
            $('#product_id').select2({
                placeholder: "-- Choose a Product --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
