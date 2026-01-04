@extends('master')

@section('title', 'Expense Report')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .nav-btn {
        background-color: #0967d3;
        color: #f8f8f8;
        transition: 0.3s;
    }
    .nav-btn.active {
        background-color: #0d6efd;
        color: white;
    }
    @media (max-width: 768px) {
        form.row.g-3 .btn {
            margin-top: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                {{-- Header Section --}}
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center mb-5"
                    style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem; color:white;">
                    <h4 class="mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start w-100 w-md-auto">
                        <i class="fas fa-receipt me-2"></i>
                        Expense — {{ ucfirst($type) }} Report
                    </h4>
                    @php
                        $query = http_build_query([
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date'),
                        ]);
                    @endphp

                    <a href="{{ route('expense.report.pdf', $type) . '?' . $query }}" 
                    class="btn btn-outline-light btn-sm px-4 py-2 rounded-pill d-flex align-items-center shadow-sm border-0"
                    style="background: linear-gradient(45deg, #ff6a00, #ee0979); color: white; transition: 0.3s; min-width: 150px;"
                    target="_blank">
                        <i class="fas fa-file-pdf me-2 text-white fs-5"></i>
                        Download PDF
                    </a>

                </div>

                {{-- Report Navigation Buttons --}}
                <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header d-flex align-items-center justify-content-between"
                        style="background: linear-gradient(to right, #17a2b8, #138496);">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-calendar-alt me-2"></i> Expense Report Filters
                        </h5>
                    </div>

                    {{-- Date Filter --}}
                    <form method="GET" action="{{ route('expense.report', ['type' => $type]) }}" class="row g-3 justify-content-center align-items-end mt-3">

                        <div class="col-md-4">
                            <label for="start_date" class="form-label fw-semibold">
                                <i class="fas fa-calendar-day me-1"></i> Start Date
                            </label>
                            <input 
                                type="date" 
                                name="start_date" 
                                id="start_date" 
                                class="form-control shadow-sm" 
                                value="{{ request('start_date') }}" 
                                style="height: 36px !important;">
                        </div>

                        <div class="col-md-4">
                            <label for="end_date" class="form-label fw-semibold">
                                <i class="fas fa-calendar-day me-1"></i> End Date
                            </label>
                            <input 
                                type="date" 
                                name="end_date" 
                                id="end_date" 
                                class="form-control shadow-sm" 
                                value="{{ request('end_date') }}" 
                                style="height: 36px !important;">
                        </div>

                        <div class="col-12 col-md-4 d-grid mt-2 mt-md-0">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>

                    </form>


                </div>

                {{-- Filter Summary --}}
                @if(request('start_date') && request('end_date'))
                    <div class="alert alert-info d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 px-4 py-3 rounded-4 shadow-sm border border-primary mb-4">
                        <div class="text-center text-md-start">
                            <div class="fw-semibold mb-1">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                Showing expenses from 
                                <span class="text-dark">{{ \Carbon\Carbon::parse(request('start_date'))->format('d M, Y') }}</span>
                                to 
                                <span class="text-dark">{{ \Carbon\Carbon::parse(request('end_date'))->format('d M, Y') }}</span>
                            </div>
                        </div>
                        <div class="fw-bold text-dark fs-3 text-center text-md-end">
                            Total Amount: <span class="text-dark">{{ number_format($expenses->sum('amount'), 2) }} ৳</span>
                        </div>
                    </div>
                @endif


                {{-- Expense Table --}}
                <div class="card mb-4">
                    <div class="card-header fw-bold"
                        style="background: linear-gradient(to right, #17a2b8, #138496); color: white;">
                        <i class="fas fa-money-bill-wave me-2"></i> Expense Report Details
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="expenseReportTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="color: white !important;">#</th>
                                    <th style="color: white !important;">Category</th>
                                    <th style="color: white !important;">Amount (৳)</th>
                                    <th style="color: white !important;">Date</th>
                                    <th style="color: white !important;">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expense->category }}</td>
                                        <td>{{ number_format($expense->amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</td>
                                        <td>{{ $expense->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No expenses found.</td>
                                        </tr>
                                    </tfoot>
                                @endforelse
                            </tbody>
                            @if($expenses->count() > 0)
                                <tfoot>
                                    <tr class="table-success fw-bold">
                                        <td colspan="2" class="text-end">Total:</td>
                                        <td>{{ number_format($expenses->sum('amount'), 2) }} ৳</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
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
        $('#expenseReportTable').DataTable({
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
