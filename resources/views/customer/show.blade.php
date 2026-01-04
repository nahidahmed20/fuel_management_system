@extends('master')

@section('title', 'Customer Details')

@push('style')
<style>
    .card-gradient {
        background: linear-gradient(to right, #2c3e50, #4ca1af);
        color: #ffffff;
        border: none;
    }

    .card-gradient .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .card-gradient h4, .card-gradient strong {
        color: #ffffff;
    }

    .info-box {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        padding: 1rem;
        color: #ffffff;
    }

    .info-box strong {
        font-size: 1rem;
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .btn-group a {
        margin-right: 10px;
    }

    .btn-danger {
        background: linear-gradient(to right, #ff416c, #ff4b2b);
        border: none;
        color: #fff;
    }

    .btn-success {
        background: linear-gradient(to right, #11998e, #38ef7d);
        border: none;
        color: #fff;
    }

    .btn-primary {
        background: linear-gradient(to right, #396afc, #2948ff);
        border: none;
        color: #fff;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .btn i {
        margin-right: 6px;
    }

    @media (max-width: 576px) {
        .btn-group a {
            margin-bottom: 8px;
        }

        .info-box {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card card-gradient shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>Customer Profile</h4>
                        <a href="{{ route('customers.index') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Customer List
                        </a>
                    </div>

                    <div class="card-body">
                        <h5 class="mb-3">Customer Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-4 info-box">
                                <strong>Name:</strong> {{ $customer->name }}
                            </div>
                            <div class="col-md-4 info-box">
                                <strong>Mobile:</strong> {{ $customer->mobile ?? '-' }}
                            </div>
                            <div class="col-md-4 info-box">
                                <strong>Address:</strong> {{ $customer->address ?? '-' }}
                            </div>
                        </div>

                        <h5 class="mb-3 text-white">Due Summary</h5>
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="p-4 rounded shadow" style="background: linear-gradient(135deg, #ff6a00, #ee0979); color: white;">
                                    <h6>Total Due</h6>
                                    <h4 class="fw-bold">{{ number_format($totalDue, 2) }} ৳</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded shadow" style="background: linear-gradient(135deg, #00b09b, #96c93d); color: white;">
                                    <h6>Total Paid</h6>
                                    <h4 class="fw-bold">{{ number_format($totalPaid, 2) }} ৳</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded shadow" style="background: linear-gradient(135deg, #2980b9, #6dd5fa); color: white;">
                                    <h6>Current Due</h6>
                                    <h4 class="fw-bold">{{ number_format($currentDue, 2) }} ৳</h4>
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
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </div>
</div>
@endsection
