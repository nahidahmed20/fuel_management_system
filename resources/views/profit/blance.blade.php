@extends('master')

@section('title')
    Current Balance
@endsection

@push('style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .balance-card {
        background: linear-gradient(135deg, #f8f9ff, #ffffff);
        border-left: 5px solid #27548A;
        border-radius: 0.5rem;
        padding: 1.25rem;
        box-shadow: 2px 6px 18px rgba(0, 0, 0, 0.08);
        transition: 0.3s ease-in-out;
    }

    .balance-card:hover {
        transform: translateY(-3px);
        box-shadow: 2px 8px 22px rgba(0, 0, 0, 0.12);
    }

    .balance-title {
        font-weight: bold;
        color: #1e3c72;
        background-color: #e0ebff;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 1.15rem;
        display: inline-block;
    }

    .balance-meta p {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .in {
        color: #0d6efd;
    }

    .out {
        color: #dc3545;
    }

    .net {
        color: #198754;
        font-weight: bold;
        font-size: 1.1rem !important;
    }

    @media (max-width: 576px) {
        .balance-card {
            padding: 1rem;
        }

        .balance-meta p {
            font-size: 0.9rem;
        }

        .balance-title {
            font-size: 1.05rem;
        }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">

        <div class="d-flex align-items-center mb-4 p-3 bg-gradient-primary text-white rounded shadow-sm">
            <i class="bi bi-wallet2 me-2 fs-4 text-warning"></i>
            <h4 class="m-0 fw-bold">Current Balance Report</h4>
        </div>


        <div class="row g-4">
            {{-- Final Balance --}}
            <div class="col-12 d-flex justify-content-center gap-4 flex-wrap mb-4">
                <div class="balance-card text-center" style="flex: 1 1 300px; ">
                    <div class="balance-title"><i class="bi bi-cash-coin"></i> Current Total Balance</div>
                    <div class="balance-meta mt-3">
                        <p class="net"><i class="fas fa-coins"></i> ৳{{ number_format($currentBalance, 3) }}</p>
                    </div>
                </div>

                <div class="balance-card text-center" style="flex: 1 1 300px; ">
                    <div class="balance-title"><i class="bi bi-cash-stack"></i> Net Profit</div>
                    <div class="balance-meta mt-3">
                        <p class="net"><i class="fas fa-dollar-sign"></i> ৳{{ number_format($netProfit, 3) }}</p>
                    </div>
                </div>
            </div>

            {{-- Inventory Value --}}
            <div class="col-md-6">
                <div class="balance-card">
                    <div class="balance-title"><i class="bi bi-box"></i> Inventory Value</div>
                    <div class="balance-meta mt-3">
                        <p class="in"><i class="bi bi-box"></i> Product Stock: ৳{{ number_format($totalProductValue, 3) }}</p>
                        <p class="in"><i class="bi bi-fuel-pump"></i> Fuel Stock: ৳{{ number_format($totalFuelValue, 3) }}</p>
                        <p class="in"><i class="bi bi-droplet-half"></i> Mobil Stock: ৳{{ number_format($totalMobilValue, 3) }}</p>
                    </div>
                </div>
            </div>

            {{-- Sales Revenue --}}
            <div class="col-md-6">
                <div class="balance-card">
                    <div class="balance-title"><i class="bi bi-bar-chart-line"></i> Sales Revenue</div>
                    <div class="balance-meta mt-3">
                        <p class="in"><i class="bi bi-basket"></i> Product Sale: ৳{{ number_format($productOutRevenue, 3) }}</p>
                        <p class="in"><i class="bi bi-fuel-pump-diesel"></i> Fuel Sale: ৳{{ number_format($fuelOutRevenue, 3) }}</p>
                        <p class="in"><i class="bi bi-droplet"></i> Mobil Sale: ৳{{ number_format($mobilOutRevenue, 3) }}</p>
                    </div>
                </div>
            </div>

            {{-- Bank & Cash --}}
            <div class="col-md-6">
                <div class="balance-card">
                    <div class="balance-title"><i class="bi bi-bank"></i> Bank & Cash</div>
                    <div class="balance-meta mt-3">
                        <p class="in"><i class="bi bi-piggy-bank"></i> Bank Deposit: ৳{{ number_format($totalDeposit, 3) }}</p>
                        <p class="out"><i class="bi bi-cash-stack"></i> Cash Withdraw: ৳{{ number_format($totalWithdraw, 3) }}</p>
                    </div>
                </div>
            </div>

            {{-- Customer & Loan --}}
            <div class="col-md-6">
                <div class="balance-card">
                    <div class="balance-title"><i class="fas fa-users"></i> Customer & Loan</div>
                    <div class="balance-meta mt-3">
                        <p class="in"><i class="bi bi-people"></i> Customer Net Due: ৳{{ number_format($netCustomerDue, 3) }}</p>
                        <p class="out"><i class="bi bi-person-bounding-box"></i> Loan Net Due: ৳{{ number_format($netLoanDue, 2) }}</p>
                    </div>
                </div>
            </div>

            

        </div>

    </div>
</div>
@endsection
