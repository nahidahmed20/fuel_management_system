@extends('master')

@section('title')
   Edit Fuel Short
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
                <div class="card shadow-lg mb-4 rounded-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i> Edit Fuel Short
                        </h5>
                        <a href="{{ route('fuel.short.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 0px;">
                            <i class="fas fa-arrow-left me-1"></i> Fuel Short List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('fuel.short.update', $fuelShort->id) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <!-- Fuel Type -->
                                <div class="col-md-4">
                                    <label for="fuel_type_id" class="form-label">Fuel Type</label>
                                    <select name="fuel_type_id" class="form-control" required>
                                        <option value="" disabled>Select Fuel Type</option>
                                        @foreach($fuelTypes as $fuel)
                                            <option value="{{ $fuel->id }}" {{ $fuelShort->fuel_type_id == $fuel->id ? 'selected' : '' }}>
                                                {{ $fuel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Short Amount -->
                                {{-- <div class="col-md-4">
                                    <label for="short_amount" class="form-label">Short Amount / Deposit (L)</label>
                                    <input type="number" name="short_amount" step="any" value="{{ old('short_amount', $fuelShort->short_amount) }}" class="form-control" required>
                                </div> --}}

                                <!-- Short Type -->
                                <div class="col-md-4">
                                    <label for="short_type" class="form-label">Short Type</label>
                                    <select name="short_type" class="form-control" required>
                                        <option value="" disabled>Select Short Type</option>
                                        <option value="-" {{ $fuelShort->short_type == '-' ? 'selected' : '' }}>− Shortage (ঘাটতি)</option>
                                        <option value="+" {{ $fuelShort->short_type == '+' ? 'selected' : '' }}>+ Excess (অতিরিক্ত)</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="price" class="form-label">Total Price</label>
                                    <input type="number" name="price" step="any" class="form-control" value="{{ old('price', $fuelShort->price) }}" placeholder="e.g. 50" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', $fuelShort->date) }}" required>
                                </div>

                                <!-- Note -->
                                <div class="col-md-6 pt-4">
                                    <label for="note" class="form-label">Note (Optional)</label>
                                    <textarea name="note" class="form-control" rows="3">{{ old('note', $fuelShort->note) }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-md-end justify-content-center mt-3">
                                        <button type="submit" class="btn text-white px-4" style="background-color:#129990;border-radius: 0px;">
                                            Update
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
