@extends('master')

@section('title')
Current Cash
@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .fuel-card {
        background: linear-gradient(135deg, #f0f4ff, #ffffff);
        border-left: 4px solid #2a5298;
        border-radius: 0.5rem;
        padding: 1rem 1.25rem;
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

    .fuel-meta .inflow {
        color: #0d6efd;
    }

    .fuel-meta .outflow {
        color: #dc3545;
    }

    .fuel-meta .balance {
        color: #28a745;
        font-weight: bold;
        font-size: 1.1rem;
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
        <h4 class="mb-4"><i class="fas fa-money-bill-wave"></i> Current Cash Report</h4>
        
        <div class="row g-4">
            
            <!-- Final Balance -->
            <div class="col-12 mb-2">
                <div class="fuel-card text-center">
                    <div class="fuel-name"><i class="fas fa-wallet"></i> Current Cash Balance</div>
                    <div class="fuel-meta mt-3">
                        <p class="balance"><i class="fas fa-coins"></i> ৳{{ number_format($currentCash, 3) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Cash In -->
            <div class="col-md-6">
                <div class="fuel-card">
                    <div class="fuel-name"><i class="fas fa-arrow-down"></i> Total Cash In</div>
                    <div class="fuel-meta mt-3">
                        <p class="inflow"><i class="fas fa-university"></i> Account Deposit: ৳{{ number_format($totalDeposit, 3) }}</p>
                        <p class="inflow"><i class="fas fa-gas-pump"></i> Fuel Sales: ৳{{ number_format($fuelSell, 3) }}</p>
                        <p class="inflow"><i class="fas fa-oil-can"></i> Mobil Sales: ৳{{ number_format($mobilSell, 3) }}</p>
                        <p class="inflow"><i class="fas fa-box"></i> Product Sales: ৳{{ number_format($productSell, 3) }}</p>
                        <p class="inflow"><i class="fas fa-credit-card"></i> Current Loan: ৳{{ number_format($loan, 3) }}</p>
                        <p class="inflow"><i class="fas fa-hand-holding-usd"></i> Customer Payment: ৳{{ number_format($customerPayment, 3) }}</p>
                        <hr>
                        <p><strong><i class="fas fa-plus-circle"></i> Total Inflow:</strong> ৳{{ number_format($totalAddCash, 3) }}</p>
                    </div>
                </div>
            </div>

            <!-- Cash Out -->
            <div class="col-md-6">
                <div class="fuel-card">
                    <div class="fuel-name"><i class="fas fa-arrow-up"></i> Total Cash Out</div>
                    <div class="fuel-meta mt-3">
                        <p class="outflow"><i class="fas fa-money-check-alt"></i> Withdraw: ৳{{ number_format($totalWithdraw, 3) }}</p>
                        <p class="outflow"><i class="fas fa-gas-pump"></i> Fuel Purchase: ৳{{ number_format($fuelBuy, 3) }}</p>
                        <p class="outflow"><i class="fas fa-oil-can"></i> Mobil Purchase: ৳{{ number_format($mobilBuy, 3) }}</p>
                        <p class="outflow"><i class="fas fa-boxes"></i> Product Purchase: ৳{{ number_format($productBuy, 3) }}</p>
                        <p class="outflow"><i class="fas fa-credit-card"></i> Loan Payment: ৳{{ number_format($loanPayment, 3) }}</p>
                        <p class="outflow"><i class="fas fa-user-clock"></i> Customer Due: ৳{{ number_format($customerDue, 3) }}</p>
                        <hr>
                        <p><strong><i class="fas fa-minus-circle"></i> Total Outflow:</strong> ৳{{ number_format($totalSubCash, 3) }}</p>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
