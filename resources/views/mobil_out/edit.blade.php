@extends('master')

@section('title')
    Edit Mobil Sell
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
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: white;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i> Edit Mobil Sell
                            </h4>
                        </div>
                        <a href="{{ route('mobilOut.index') }}"
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Sell List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mobilOut.update', $out->id) }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="name" class="form-label">Product</label>
                                    <input type="text" name="name" class="form-control" value="{{ $out->name }}" readonly required>
                                </div>
                                <div class="col-md-3">
                                    <label for="quantity" class="form-label">Quantity (L)</label>
                                    <input type="number" name="quantity" id="quantity" step="any" class="form-control" value="{{ $out->quantity }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="total_sell_display" class="form-label">Total Sell (৳)</label>
                                    <input type="text" id="total_sell_display" class="form-control" value="{{ $out->total_sell }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ $out->date }}" required>
                                </div>
                            </div>

                            {{-- Hidden Fields --}}
                            <input type="hidden" name="total_sell" id="total_sell" value="{{ $out->total_sell }}">
                            <input type="hidden" name="total_buy" id="total_buy" value="{{ $out->total_buy }}">

                            <button type="submit" class="btn mt-4 text-white"
                                style="background: linear-gradient(45deg, #ff6a00, #ee0979); padding: 8px 16px; border-radius: 5px; border: none;">
                                Update Sell
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@push('script')
<script>
    $(document).ready(function () {
        const oldQuantity = parseFloat({{ $out->quantity }});
        const totalBuy = parseFloat({{ $out->total_buy }});

        // যদি quantity বা total_buy কোনোটাই 0 হয়, fallback 0
        const buyingPrice = oldQuantity > 0 ? (totalBuy / oldQuantity) : 0;

        const oldSellingPrice = parseFloat({{ $out->total_sell / $out->quantity }});

        function updateTotals() {
            const qty = parseFloat($('#quantity').val()) || 0;
            const totalSell = (qty * oldSellingPrice).toFixed(2);
            const totalBuyNew = (qty * buyingPrice).toFixed(2);

            $('#total_sell_display').val(totalSell);
            $('#total_sell').val(totalSell);
            $('#total_buy').val(totalBuyNew);
        }

        // প্রথমবার লোডেও কল করো
        updateTotals();

        // যেইমাত্র quantity পরিবর্তন করবে
        $('#quantity').on('input', function () {
            updateTotals();
        });
    });
</script>
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


@endpush
