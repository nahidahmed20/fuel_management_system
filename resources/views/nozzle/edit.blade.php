@extends('master')

@section('title')
    Nozzle Edit
@endsection

@push('style')
<style>
    .card {
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                    4px 8px 20px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card shadow-lg rounded-0">
                    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center" style="background-color: #27548A; color: #fff;">
                        <div class="header-title mb-2 mb-sm-0">
                            <h4 class="card-title m-0">
                            <i class="bi bi-pencil-square me-2"></i> Edit Nozzle
                            </h4>

                        </div>
                        <a href="{{ route('nozzle.index') }}" class="btn btn-sm text-white rounded-1" style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 0px;">
                            <i class="fas fa-arrow-left me-1"></i> Nozzle List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('nozzle.update', $nozzle->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="name">Nozzle Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $nozzle->name }}" required>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="fuel_type_id">Fuel Type</label>
                                    <select name="fuel_type_id" class="form-control" required>
                                        @foreach($fuelTypes as $fuel)
                                            <option value="{{ $fuel->id }}" {{ $fuel->id == $nozzle->fuel_type_id ? 'selected' : '' }}>
                                                {{ $fuel->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
