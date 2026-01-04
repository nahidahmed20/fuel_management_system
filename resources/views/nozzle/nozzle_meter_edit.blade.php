@extends('master')

@section('title')
    Edit Nozzle Meter
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
                                <i class="bi bi-pencil-square me-2"></i> Edit Nozzle Meter
                            </h4>
                        </div>
                        <a href="{{ route('nozzle.meter.index') }}" 
                            class="btn btn-sm d-flex align-items-center justify-content-center mt-2 mt-sm-0"
                            style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Nozzle Meters
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('nozzle.meter.update', $meter->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                {{-- Fuel Type --}}
                                <div class="mb-3 col-md-4">
                                    <label for="fuel_type" class="form-label">Fuel Type</label>
                                    <input type="text" id="fuel_type" class="form-control" value="{{ ucfirst($meter->nozzle->fuelType->name ?? 'N/A') }}" disabled>
                                </div>

                                {{-- Nozzle Select --}}
                                <div class="mb-3 col-md-4">
                                    <label for="nozzle_id" class="form-label">Select Nozzle</label>
                                    <select name="nozzle_id" id="nozzle_id" class="form-control" required>
                                        <option value="">-- Select Nozzle --</option>
                                        @foreach($nozzles as $nozzle)
                                            <option value="{{ $nozzle->id }}" {{ $nozzle->id == $meter->nozzle_id ? 'selected' : '' }}>
                                                {{ $nozzle->name }} ({{ $nozzle->fuelType->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Previous Meter --}}
                                <div class="mb-3 col-md-4">
                                    <label for="prev_meter" class="form-label">Previous Meter</label>
                                    <input type="text" name="prev_meter" id="prev_meter" class="form-control" value="{{ number_format($meter->prev_meter, 2) }}" >
                                </div>

                                {{-- Current Meter --}}
                                <div class="mb-3 col-md-4">
                                    <label for="curr_meter" class="form-label">Current Meter</label>
                                    <input type="number" step="any" name="curr_meter" id="curr_meter" class="form-control" value="{{ $meter->curr_meter }}" required>
                                </div>

                                {{-- Date --}}
                                <div class="mb-3 col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" value="{{ $meter->date }}" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" 
                                class="btn text-white fw-semibold d-flex align-items-center justify-content-center"
                                style="background: linear-gradient(45deg, #0f9b8e, #129990); padding: 8px 16px; border-radius: 5px; border: none;">
                                 <i class="fas fa-save me-1"></i> Update Meter
                            </button>
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
    $('#nozzle_id').on('change', function () {
        var nozzleId = $(this).val();

        if(nozzleId) {
            $.ajax({
                url: '/get-latest-meter/' + nozzleId,
                method: 'GET',
                success: function (response) {

                    var prevMeter = parseFloat(response.curr_meter ?? 0);
                    $('#prev_meter').val(prevMeter.toFixed(2));

                    $('#curr_meter').val('');
                },
                error: function () {
                    $('#prev_meter').val('0.00');
                    $('#curr_meter').val('');
                }
            });
        } else {
            $('#prev_meter').val('');
            $('#curr_meter').val('');
        }
    });
});
</script>

@endpush
