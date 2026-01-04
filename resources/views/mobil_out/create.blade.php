@extends('master')

@section('title')
    Sell Mobil
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
                                <i class="fas fa-arrow-up me-2"></i> Sell Mobil
                            </h4>
                        </div>
                        <a href="{{ route('mobilOut.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Sell List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mobil.out.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="name" class="form-label">Product</label>
                                    <input type="text" name="name" class="form-control" value="Mobil" readonly required>
                                </div>
                                <div class="col-md-3">
                                    <label for="quantity" class="form-label">Quantity (L)</label>
                                    <input type="number" name="quantity" id="quantity" step="any" class="form-control" placeholder="e.g. 10" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="total_sell_display" class="form-label">Total Sell (৳)</label>
                                    <input type="text" id="total_sell_display" class="form-control" placeholder="Auto calculated" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                            </div>

                            {{-- Hidden fields --}}
                            <input type="hidden" name="total_sell" id="total_sell">
                            <input type="hidden" name="total_buy" id="total_buy">

                            <button type="submit" class="btn mt-4 text-white" style="background: linear-gradient(45deg, #9D50BB, #6E48AA); padding: 8px 16px; border-radius: 5px; border: none;">
                                Add Sell
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        var sellingPrice = {{ $latestSellingPrice ?? 0 }};
        var buyingPrice = {{ $latestBuyingPrice ?? 0 }};

        $('#quantity').on('input', function () {
            var qty = parseFloat($(this).val()) || 0;

            var totalSell = (qty * sellingPrice).toFixed(2);
            var totalBuy = (qty * buyingPrice).toFixed(2);

            $('#total_sell_display').val(totalSell);
            $('#total_sell').val(totalSell);
            $('#total_buy').val(totalBuy); 
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
