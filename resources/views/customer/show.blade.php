@extends('master')

@section('title', 'Customer Details')

@push('style')
<style>
    .card-custom {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
    }

    .card-custom .card-header {
        background-color: #042b52;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem 0.5rem 0 0;
        padding: 1rem 1.5rem;
    }

    .info-box {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    .info-box strong {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        color: #495057;
    }

    .info-box h4 {
        margin: 0;
        font-weight: 600;
        color: #212529;
    }

    .btn-group a {
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
        color: #fff;
    }

    .btn-success {
        background-color: #198754;
        border: none;
        color: #fff;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: #fff;
    }

    .btn i {
        margin-right: 6px;
    }

    @media (max-width: 576px) {
        .btn-group a {
            width: 100%;
        }

        .info-box {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card card-custom shadow-sm rounded-0">
                    <div class="card-header d-flex justify-content-between align-items-center rounded-0">
                        <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i> Customer Profile</h4>
                        <a href="{{ route('customers.index') }}" 
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0 rounded-1"
                           style="background: #04c5ff; color:white; border-radius: 2px;">
                             Customer List
                        </a>
                    </div>

                    <div class="card-body">
                        <h5 class="mb-3">Customer Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-4 info-box">
                                <strong>Name:</strong>
                                <h4>{{ $customer->name }}</h4>
                            </div>
                            <div class="col-md-4 info-box">
                                <strong>Mobile:</strong>
                                <h4>{{ $customer->mobile ?? '-' }}</h4>
                            </div>
                            <div class="col-md-4 info-box">
                                <strong>Address:</strong>
                                <h4>{{ $customer->address ?? '-' }}</h4>
                            </div>
                        </div>

                        <h5 class="mb-3">Due Summary</h5>
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <strong>Total Due</strong>
                                    <h4>{{ number_format($totalDue, 2) }} ৳</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <strong>Total Paid</strong>
                                    <h4>{{ number_format($totalPaid, 2) }} ৳</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <strong>Current Due</strong>
                                    <h4>{{ number_format($currentDue, 2) }} ৳</h4>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3">Actions</h5>
                            <div class="btn-group flex-wrap" role="group">
                                <a href="{{ route('customer_due.create', ['customer_id' => $customer->id]) }}" class="btn btn-danger">
                                    <i class="bi bi-plus-circle"></i> Add Due
                                </a>
                                <a href="{{ route('customer-due-payments.create', ['customer_id' => $customer->id]) }}" class="btn btn-success">
                                    <i class="bi bi-cash-coin"></i> Receive Payment
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil-square"></i> Edit Customer
                                </a>
                            </div>
                        </div>
                    </div>
                </div> <!-- card -->
            </div>
        </div>
    </div>
</div>
@endsection
