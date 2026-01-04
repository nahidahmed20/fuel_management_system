@extends('master')

@section('title')
Customer Due Payments
@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .card-header-custom {
        background-color: #27548A;
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    #customer_due_payment tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-back,
    .btn-edit,
    .btn-delete {
        min-width: 60px;
        text-align: center;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        color: white;
    }

    .btn-back {
        background: linear-gradient(45deg, #36D1DC, #5B86E5);
    }
    .btn-back:hover {
        background: linear-gradient(45deg, #2bb3c9, #4d72d8);
        color: #fff;
        text-decoration: none;
    }

    .btn-edit {
        background-color: #20c997;
        color:white;
        padding: 4px 10px;
    }

    .btn-delete {
        background-color: #e63946;
        color: white;
        padding: 4px 10px;
    }

    @media (max-width: 576px) {
        .card-header-custom {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-back,
        .btn-edit,
        .btn-delete {
            width: 100% !important;
            justify-content: center;
        }

        #customer_due_payment th,
        #customer_due_payment td {
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow-sm border-0">
                    {{-- Header --}}
                    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <h4 class="card-title mb-0" style="font-weight: bold;">
                            <i class="fa fa-credit-card me-2"></i> Customer Due Payments
                        </h4>

                        <a href="{{ route('customers.index') }}" 
                        class="btn btn-sm d-flex align-items-center" 
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Customers
                        </a>

                    </div>

                    {{-- Table --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="customer_due_payment">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 5%; color:white !important;">#</th>
                                        <th style="color:white !important;">Customer</th>
                                        <th class="text-end" style="width: 15%; color:white !important;">Amount (৳)</th>
                                        <th style="width: 20%; color:white !important;">Payment Date</th>
                                        <th style="color:white !important;">Note</th>
                                        <th style="color:white !important;" class="text-center">Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $payment->customer->name ?? 'N/A' }}</td>
                                            <td class="text-end">{{ number_format($payment->amount, 3) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                                            <td>{{ $payment->note ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('customer-due-payments.edit', $payment->id) }}" class="btn btn-sm btn-edit">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">No payments found.</td>
                                            </tr>
                                        </tfoot>
                                    @endforelse
                                </tbody>
                                {{-- <tfoot>
                                    <tr class="table-success">
                                        <td colspan="2" class="text-end fw-bold">Total</td>
                                        <td class="text-end fw-bold">{{ number_format($payments->sum('amount'), 2) }} ৳</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#customer_due_payment').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            language: {
                paginate: {
                    previous: "<i class='fas fa-angle-left'></i>",
                    next: "<i class='fas fa-angle-right'></i>"
                }
            }
        });
    });
</script>
@endpush
