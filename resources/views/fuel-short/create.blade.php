@extends('master')

@section('title')
   Fuel Short Create
@endsection

@push('style')
<style>
    .card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .form-control:focus {
        border-color: #129990;
        box-shadow: none;
    }

    .btn-submit {
        background: linear-gradient(45deg, #0f9b8e, #129990);
        color: white;
        padding: 10px 24px;
        font-weight: 500;
        border: none;
        border-radius: 6px;
        transition: 0.3s ease;
    }

    .btn-submit:hover {
        opacity: 0.9;
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

        .btn-submit {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h5 class="mb-0">
                            <i class="bi bi-droplet-half me-2"></i> Add New Fuel Short
                        </h5>
                        <a href="{{ route('fuel.short.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Fuel Short List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('fuel.short.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <!-- Fuel Type -->
                                <div class="col-md-6">
                                    <label for="fuel_type_id" class="form-label">Fuel Type</label>
                                    <select name="fuel_type_id" class="form-control" required>
                                        <option value="" disabled selected>Select Fuel Type</option>
                                        @foreach($fuelTypes as $fuel)
                                            <option value="{{ $fuel->id }}" {{ old('fuel_type_id') == $fuel->id ? 'selected' : '' }}>
                                                {{ $fuel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Short Amount -->
                                {{-- <div class="col-md-6">
                                    <label for="short_amount" class="form-label">Short Amount / Deposit (L)</label>
                                    <input type="number" name="short_amount" step="any" class="form-control" placeholder="e.g. 50" required>
                                </div> --}}

                                <!-- Short Type -->
                                <div class="col-md-6">
                                    <label for="short_type" class="form-label">Short Type</label>
                                    <select name="short_type" class="form-control" required>
                                        <option value="" disabled selected>Select Short Type</option>
                                        <option value="-">− Shortage (ঘাটতি)</option>
                                        <option value="+">+ Excess (অতিরিক্ত)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="price" class="form-label">Total Price</label>
                                    <input type="number" name="price" step="any" class="form-control" placeholder="e.g. 50" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>

                                <!-- Note -->
                                <div class="col-md-12 pt-4">
                                    <label for="note" class="form-label">Note (Optional)</label>
                                    <textarea name="note" class="form-control" rows="3" placeholder="Add any comment..."></textarea>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-start">
                                <button type="submit" class="btn text-white" style="background-color: #129990;">
                                    + Create Short Fuel
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
