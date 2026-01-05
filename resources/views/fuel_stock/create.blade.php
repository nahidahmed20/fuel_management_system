@extends('master')

@section('title')
    Add Fuel Stock
@endsection

@push('style')
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
    }
    @media (max-width: 576px) {
        .card-header-center-sm {
            justify-content: center !important;
            text-align: center;
        }

        .card-header-center-sm .header-title,
        .card-header-center-sm a {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 0.5rem;
        }
    }
</style>
</style>

@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card shadow-lg mb-4 rounded-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap card-header-center-sm"
                        style="background-color: #27548A; color: #fff;">
                        
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-fuel-pump-fill me-2"></i> Fuel Stock (Liters)
                            </h4>
                        </div>

                        <a href="{{ route('fuel.stock.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 0px;">
                            <i class="fas fa-arrow-left me-1"></i> Fuel Stock List
                        </a>
                    </div>


                    <div class="card-body">
                        <form action="{{ route('fuel.stock.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Fuel Type -->
                                <div class="col-md-4">
                                    <label for="fuel_type_id" class="form-label">Fuel Type</label>
                                    <select name="fuel_type_id" id="fuel_type_id" class="form-control" required>
                                        <option value="" disabled selected>Select Fuel Type</option>
                                        @foreach($fuelTypes as $type)
                                            <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantity (L)</label>
                                    <input type="number" step="any" name="quantity" id="quantity" class="form-control" placeholder="Enter quantity in liters" required>
                                </div>

                                <!-- Buying Price -->
                                <div class="col-md-4">
                                    <label for="buying_price" class="form-label">Buying Price</label>
                                    <input type="number" step="any" name="buying_price" id="buying_price" class="form-control" placeholder="Enter buying price per liter" required>
                                </div>

                                <!-- Selling Price -->
                                <div class="col-md-4">
                                    <label for="selling_price" class="form-label">Selling Price</label>
                                    <input type="number" step="any" name="selling_price" id="selling_price" class="form-control" placeholder="Enter selling price per liter" required>
                                </div>

                                <!-- Truck Number -->
                                <div class="col-md-4">
                                    <label for="truck_number" class="form-label">Truck Number</label>
                                    <input type="text" name="truck_number" id="truck_number" class="form-control" placeholder="Enter truck number" required>
                                </div>

                                <!-- Company Name -->
                                <div class="col-md-4">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter company name" required>
                                </div>

                                <!-- Date -->
                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                            </div>

                            <!-- Submit Button Row -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-md-end justify-content-center mt-3">
                                        <button type="submit" class="btn text-white px-4" style="background-color:#129990;border-radius: 0px;">
                                            Add Stock
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
    <script>
        $(document).ready(function () {
            let today = new Date();
            let year = today.getFullYear();
            let month = String(today.getMonth() + 1).padStart(2, '0');
            let day = String(today.getDate()).padStart(2, '0');
            let formattedDate = `${year}-${month}-${day}`;

            $('#date').val(formattedDate);
        });
    </script>
@endpush
