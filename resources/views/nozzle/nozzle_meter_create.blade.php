@extends('master')

@section('title')
    Create Nozzle Meter
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
<style>
    .nozzle-select {
        height: calc(36px + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border: 1px solid #ced4da;
        background-color: #fff;
        color: #495057;
    }

</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg rounded-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-pencil-square me-2"></i> Create Nozzle Meter
                            </h4>
                        </div>
                        <a href="{{ route('nozzle.meter.index') }}" 
                            class="btn btn-sm d-flex align-items-center justify-content-center mt-2 mt-sm-0"
                            style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                            Nozzle list
                        </a>
                    </div>
                    

                    <div class="card-body">
                        <form action="{{ route('nozzle.meter.store') }}" method="POST">
                            @csrf

                            {{-- Hidden sold_quantity field --}}
                            <input type="hidden" name="sold_quantity" id="sold_quantity" readonly>

                            <div class="row">
                                {{-- Nozzle --}}
                                <div class="col-md-4">
                                    <label for="nozzle_id" class="form-label">Select Nozzle</label>
                                    <select name="nozzle_id" id="nozzle_id" class="form-control nozzle-select" required>
                                        <option value="">-- Select Nozzle --</option>
                                        @foreach($nozzles as $nozzle)
                                            <option value="{{ $nozzle->id }}">{{ $nozzle->name }} ({{ $nozzle->fuelType->name ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Previous Meter --}}
                                <div class=" col-md-4">
                                    <label for="prev_meter" class="form-label">Previous Meter Reading</label>
                                    <input type="number" step="any" name="prev_meter" id="prev_meter" class="form-control" placeholder="Previous reading"  required>
                                </div>

                                {{-- Current Meter --}}
                                <div class=" col-md-4">
                                    <label for="curr_meter" class="form-label">Current Meter Reading</label>
                                    <input type="number" step="any" name="curr_meter" id="curr_meter" class="form-control" placeholder="Current reading" required>
                                </div>

                                {{-- Date --}}
                                <div class=" col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-md-end justify-content-center mt-3">
                                        <button type="submit" class="btn text-white px-4" style="background-color:#129990;border-radius: 2px; padding:5px 13px">
                                            Save
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
    // Auto set today's date
    $(document).ready(function () {
        let today = new Date().toISOString().split('T')[0];
        $('#date').val(today);
    });
</script>

<script>
    $(document).ready(function () {
        let today = new Date().toISOString().split('T')[0];
        $('#date').val(today);

        $('#nozzle_id').on('change', function () {
            let nozzleId = $(this).val();
            console.log(nozzleId);
            if (nozzleId) {
                $.ajax({
                    url: '/get-latest-meter/' + nozzleId,
                    method: 'GET',
                    success: function (response) {
                        let lastCurr = parseFloat(response.curr_meter ?? 0);
                        console.log(lastCurr);
                        $('#prev_meter').val(lastCurr.toFixed(3));

                        $('#curr_meter').val('');

                        $('#sold_quantity').val('');
                    },
                    error: function () {
                        $('#prev_meter').val('0.00');
                        $('#curr_meter').val('');
                        $('#sold_quantity').val('');
                    }
                });
            } else {
                $('#prev_meter').val('');
                $('#curr_meter').val('');
                $('#sold_quantity').val('');
            }
        });

        $('#curr_meter').on('input', function () {
            calculateSoldQuantity();
        });

        function calculateSoldQuantity() {
            let prev = parseFloat($('#prev_meter').val()) || 0;
            let curr = parseFloat($('#curr_meter').val()) || 0;
            let sold = curr - prev;
            $('#sold_quantity').val(sold > 0 ? sold.toFixed(2) : 0);
        }
    });
</script>


@endpush
