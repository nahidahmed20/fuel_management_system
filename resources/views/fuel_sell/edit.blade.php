@extends('master')

@section('title')
    Edit Fuel Sell
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
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card shadow-lg mb-4 rounded-0">
                    
                    {{-- Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap card-header-center-sm"
                        style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-pencil-square me-2"></i> Edit Fuel Sell
                            </h4>
                        </div>

                        <a href="{{ route('fuel.sell.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 0px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>

                    {{-- Form --}}
                    <div class="card-body">
                        <form action="{{ route('fuel.sell.update', $fuelSell->id) }}" method="POST">
                            @csrf

                            <div class="row g-3 mb-3">
                                <!-- Fuel Type -->
                                <div class="col-md-4">
                                    <label for="fuel_type_id" class="form-label">Fuel Type</label>
                                    <select name="fuel_type_id" id="fuel_type_id" class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach($fuelTypes as $type)
                                            <option value="{{ $type->id }}" {{ $fuelSell->fuel_type_id == $type->id ? 'selected' : '' }}>
                                                {{ ucfirst($type->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nozzle -->
                                <div class="col-md-4">
                                    <label for="nozzle_id" class="form-label">Nozzle</label>
                                    <select name="nozzle_id" id="nozzle_id" class="form-control" required>
                                        <option value="{{ $fuelSell->nozzle_id }}">
                                            {{ $fuelSell->nozzle->name ?? 'Selected Nozzle' }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantity (L)</label>
                                    <input type="number" step="any" name="quantity" id="quantity" class="form-control" value="{{ $fuelSell->quantity }}" required>
                                </div>

                                <!-- Total Sell -->
                                <div class="col-md-4">
                                    <label for="total_sell" class="form-label">Total Price (৳)</label>
                                    <input type="text" name="total_sell" id="total_sell" class="form-control" value="{{ $fuelSell->total_sell }}" readonly>
                                </div>

                                <!-- Date -->
                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ \Carbon\Carbon::parse($fuelSell->date)->format('Y-m-d') }}" required>
                                </div>

                                <input type="hidden" name="total_buy" id="total_buy" value="{{ $fuelSell->total_buy }}">
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

@push('script')
<script>
    $(document).ready(function () {
        let sellingPrice = 0;
        let buyingPrice = 0;

        $('#fuel_type_id').on('change', function () {
            let fuelTypeId = $(this).val();
            $('#nozzle_id').html('<option value="">Loading...</option>');

            if (fuelTypeId) {
                // Load nozzles
                $.ajax({
                    url: '{{ route("get.nozzles.by.fueltype") }}',
                    type: 'GET',
                    data: { fuel_type_id: fuelTypeId },
                    success: function (response) {
                        let options = '<option value="">Select Nozzle</option>';
                        response.forEach(function (nozzle) {
                            options += '<option value="' + nozzle.id + '">' + nozzle.name + '</option>';
                        });
                        $('#nozzle_id').html(options);
                    },
                    error: function () {
                        alert('Failed to load nozzles.');
                        $('#nozzle_id').html('<option value="">Select Nozzle</option>');
                    }
                });

                $.ajax({
                    url: '/get-latest-selling-price/' + fuelTypeId,
                    type: 'GET',
                    success: function (data) {

                        sellingPrice = parseFloat(data.selling_price || 0);
                        buyingPrice = parseFloat(data.buying_price || 0);
                        calculateTotalSell();
                    },
                    error: function () {
                        alert('Failed to fetch the selling price.');
                        sellingPrice = 0;
                        buyingPrice = 0;
                        $('#total_sell').val('');
                        $('#total_buy').val('');
                    }
                });
            } else {
                $('#nozzle_id').html('<option value="">Select Nozzle</option>');
                sellingPrice = 0;
                buyingPrice = 0;
                $('#total_sell').val('');
                $('#total_buy').val('');
                $('#quantity2').val('');
            }
        });

        $('#nozzle_id').on('change', function () {
            let nozzleId = $(this).val();
            if (nozzleId) {
                $.ajax({
                    url: '/get-nozzle-meter/' + nozzleId,
                    type: 'GET',
                    success: function (data) {
                        let quantity = parseFloat(data.curr_meter) - parseFloat(data.prev_meter);
                        quantity = quantity > 0 ? quantity.toFixed(2) : '0.00';

                        $('#quantity').val(quantity);
                        calculateTotalSell(quantity);
                    },
                    error: function () {
                        alert('Failed to fetch nozzle meter data.');
                        $('#quantity').val('');
                        $('#total_sell').val('');
                        $('#total_buy').val('');
                    }
                });
            } else {
                $('#quantity').val('');
                $('#total_sell').val('');
                $('#total_buy').val('');
            }
        });

        $('#quantity').on('input', function () {
            let quantity = parseFloat($(this).val()) || 0;
            calculateTotalSell(quantity);
        });

        function calculateTotalSell(quantity = null) {
            if (quantity === null) {
                quantity = parseFloat($('#quantity2').val()) || 0;
            }
            let totalSell = (quantity * sellingPrice).toFixed(2);
            let totalBuy = (quantity * buyingPrice).toFixed(2);

            $('#total_sell').val(totalSell);
            $('#total_buy').val(totalBuy); 
        }
    });
</script>
@endpush
