@extends('master')

@section('title', 'Edit Stock In')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between" style="background-color: #0d6efd; color: #fff;">
                        <div class="header-title">
                            <h4 class="mb-0">Edit Product Stock In</h4>
                        </div>
                        <a href="{{ route('product.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stockin.update', $stock->id) }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Product Dropdown -->
                                <div class="col-md-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select name="product_id" id="product_id" class="form-control" required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $stock->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" step="any" name="quantity" id="quantity" class="form-control" value="{{ $stock->quantity }}" required>
                                </div>

                                <!-- Buying Price -->
                                <div class="col-md-3">
                                    <label for="buying_price" class="form-label">Buying Price</label>
                                    <input type="number" step="any" name="buying_price" id="buying_price" class="form-control" value="{{ $stock->buying_price }}" required>
                                </div>

                                <!-- Selling Price -->
                                <div class="col-md-3">
                                    <label for="selling_price" class="form-label">Selling Price</label>
                                    <input type="number" step="any" name="selling_price" id="selling_price" class="form-control" value="{{ $stock->selling_price }}" required>
                                </div>

                                <!-- Date -->
                                <div class="col-md-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ $stock->date }}" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save me-1"></i> Update Stock
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
