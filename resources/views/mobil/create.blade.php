@extends('master')

@section('title')
    Add Mobil Stock
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
                                <i class="fas fa-oil-can me-2"></i> Add Mobil Stock
                            </h4>
                        </div>
                        <a href="{{ route('mobil.index') }}" 
                        class="btn btn-sm d-flex align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Mobil List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mobil.stock.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" name="name" value="Mobil" class="form-control" readonly required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantity (L)</label>
                                    <input type="number" name="quantity" step="any" class="form-control" placeholder="e.g. 100" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="buying_price" class="form-label">Buying Price (৳)</label>
                                    <input type="number" name="buying_price" step="any" class="form-control" placeholder="e.g. 85" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="selling_price" class="form-label">Selling Price (৳)</label>
                                    <input type="number" name="selling_price" step="any" class="form-control" placeholder="e.g. 95" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn mt-4 text-white" style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                    + Add Stock
                                </button>
                            </div>
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
    <script>
    $(document).ready(function () {
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0'); 
        const year = today.getFullYear();

        const localDate = `${year}-${month}-${day}`;
        $('input[type="date"]').val(localDate);
    });
</script>
@endpush