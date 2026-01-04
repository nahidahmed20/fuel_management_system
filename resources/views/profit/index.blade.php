@extends('master')

@section('title')
Profit Report
@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .fuel-card {
        background: linear-gradient(135deg, #f0f4ff, #ffffff);
        border-left: 4px solid #2a5298;
        border-radius: 0.5rem;
        transition: all 0.3s ease-in-out;
    }

    .fuel-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
    }

    .fuel-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: #1e3c72;
        background-color: #e9f1ff;
        padding: 4px 10px;
        border-radius: 0.375rem;
        display: inline-block;
    }

    .fuel-meta p {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .fuel-meta .buy {
        color: #dc3545;
    }

    .fuel-meta .sell {
        color: #0d6efd;
    }

    .fuel-meta .profit {
        color: #28a745;
        font-weight: bold;
    }

    @media (max-width: 576px) {
        .fuel-card {
            padding: 0.75rem;
        }

        .fuel-meta p {
            font-size: 0.88rem;
        }

        .fuel-name {
            font-size: 1.05rem;
        }
    }
</style>

@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">

        {{-- Title Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center py-3 px-3 rounded" style="background-color: #27548A;">
                    <h3 class="text-white fw-bold mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Profit Report 
                    </h3>
                </div>
            </div>
        </div>

        {{-- Filter Form --}}
        <div class="row justify-content-center mb-4">
            <div class="col-md-12 ">
                <form action="{{ route('profit.custom') }}" method="GET" class="card p-3 shadow-sm border-0">
                 
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="start_date" class="form-label mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ request('start_date') }}"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="end_date" class="form-label mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ request('end_date') }}"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-md-4 d-grid mt-2 mt-md-0">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Overall Profit Summary --}}
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-10">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header text-white text-center py-3 rounded-top-4" style="background-color: #27548A;">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-chart-line me-2"></i>
                            Overall Net Profit Summary
                        </h4>
                    </div>
                    <ul class="list-group list-group-flush fs-5">
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #F3F9FF;">
                            <div class="fw-bold"><i class="fas fa-gas-pump text-primary me-2"></i> Total Fuel Profit</div>
                            <span class="fw-bold text-success">৳{{ number_format($totalFuelProfit, 3) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #FEF9F1;">
                            <div class="fw-bold"><i class="fas fa-oil-can text-warning me-2"></i> Total Mobil Profit</div>
                            <span class="fw-bold text-success">৳{{ number_format($mobilProfit, 3) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #F5F5FF;">
                            <div class="fw-bold"><i class="fas fa-box-open text-info me-2"></i> Total Product Profit</div>
                            <span class="fw-bold text-success">৳{{ number_format($totalProductProfit, 3) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-danger" style="background-color: #FFF5F5;">
                            <div class="fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i> Total Expense</div>
                            <span class="fw-bold">৳{{ number_format($totalExpense, 3) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #FFF9E6;">
                            <div class="fw-bold"><i class="fas fa-adjust text-dark me-2"></i> Fuel Short Impact</div>
                            <span class="fw-bold text-success">৳{{ number_format($totalShortProfit, 3) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center rounded-bottom-4 text-white" style="background-color: #28a745;">
                            <div class="fw-bold"><i class="fas fa-dollar-sign me-2"></i> Net Profit</div>
                            <span class="fw-bold">৳{{ number_format($totalNetProfit, 3) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <hr>
        {{-- Total Expense --}}
        <div class="alert text-center fw-semibold fs-5 rounded-pill shadow-sm" style="background-color: #27548A; color: #fff;">
            <i class="fas fa-wallet me-2"></i>
            Total Expense: <span style="color: #FFD700;">৳{{ number_format($totalExpense, 3) }}</span>
        </div>
        <hr>
        {{-- Profit Cards --}}
        <div class="row">
            @forelse ($profits as $data)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="fuel-card p-3 shadow-sm h-100">
                        <div class="fuel-name mb-2">{{ $data['fuel'] }}</div>
                        <div class="fuel-meta">
                            <p><strong>Total Sell:</strong> {{ $data['total_out'] }} L</p>
                            <p class="buy"><strong>Buying Total:</strong> ৳{{ number_format((float) $data['buy_total'], 2) }}</p>
                            <p class="sell"><strong>Selling Total:</strong> ৳{{ number_format((float) $data['purchase_total'], 2) }}</p>
                            <p class="profit"><strong>Profit:</strong> ৳{{ number_format((float) $data['profit'], 2) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-3">
                    No profit data found.
                </div>
            @endforelse
        </div>

        {{-- Mobil Profit Cards --}}
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="fuel-card p-3 shadow-sm h-100">
                    <div class="fuel-name mb-2">Mobil Summary</div>
                    <div class="fuel-meta">
                        <p class="buy">Total Buy: ৳{{ number_format($mobilTotalBuy, 2) }}</p>
                        <p class="sell">Total Sell: ৳{{ number_format($mobilTotalSell, 2) }}</p>
                        <p class="profit"><strong>Profit: ৳{{ number_format($mobilProfit, 2) }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Profit Cards --}}
        <div class="row">
            @forelse ($productProfits as $data)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="fuel-card p-3 shadow-sm h-100">
                        <div class="fuel-name mb-2">{{ $data['product'] }}</div>
                        <div class="fuel-meta">
                            <p class="buy">Total Buy: ৳{{ number_format($data['buy_total'], 2) }}</p>
                            <p class="sell">Total Sell: ৳{{ number_format($data['purchase_total'], 2) }}</p>
                            <p class="profit"><strong>Profit: ৳{{ number_format($data['profit'], 2) }}</strong></p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-3">
                    No product profit data found.
                </div>
            @endforelse
        </div>


        

    </div>
</div>

@endsection

@push('script')
<script>
    document.getElementById('previewInvoiceBtn').addEventListener('click', function (e) {
        e.preventDefault();

        const url = new URL(window.location.href);
        url.searchParams.set('open_modal', '1'); // set or replace open_modal param

        // redirect to same page with open_modal=1
        window.location.href = url.toString();
    });
</script>
@endpush

