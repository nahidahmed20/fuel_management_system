@extends('master')

@section('title', ucfirst($type).' Product Report')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    table.dataTable thead th {
        background-color: #020202 !important;
        color: white !important;
        font-weight: 600;
        text-align: center;
    }
    table.dataTable tbody td {
        text-align: center;
        vertical-align: middle;
    }
    .text-success {
        color: #198754 !important;
        font-weight: 600;
    }
    .text-danger {
        color: #dc3545 !important;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="card shadow-lg">
            {{-- Header --}}

            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center mb-5"
                style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem; color:white;">
                <h4 class="mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start w-100 w-md-auto">
                    <i class="fas fa-box-open me-2"></i>
                    Product — {{ ucfirst($type) }} Report
                </h4>
                <a href="{{ route('product.report.download', $type) }}" 
                class="btn btn-outline-light btn-sm px-4 py-2 rounded-pill d-flex align-items-center shadow-sm border-0"
                style="background: linear-gradient(45deg, #ff6a00, #ee0979); color: white; transition: 0.3s; min-width: 150px;"
                target="_blank">
                    <i class="fas fa-file-pdf me-2 text-white fs-5"></i>
                    Download PDF
                </a>
            </div>

            <hr style="border: none; height: 2px; background-color: #007bff;">

            <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between"
                    style="background: linear-gradient(to right, #17a2b8, #138496); ">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-chart-bar me-2"></i> Reports Overview
                    </h5>
                </div>

                <div class="card-body bg-light">
                    <div class="d-flex flex-wrap justify-content-center gap-3">

                        <a href="{{ route('product.report.view', 'today') }}"
                            class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'today' ? 'btn-primary text-white' : '' }}"
                            style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                            <i class="fas fa-calendar-day me-2"></i> Today
                        </a>

                        <a href="{{ route('product.report.view', 'monthly') }}"
                            class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'monthly' ? 'btn-primary text-white' : '' }}"
                            style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                            <i class="fas fa-calendar-alt me-2"></i> This Month
                        </a>

                        <a href="{{ route('product.report.view', 'yearly') }}"
                            class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'yearly' ? 'btn-primary text-white' : '' }}"
                            style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                            <i class="fas fa-calendar me-2"></i> This Year
                        </a>

                    </div>
                </div>
            </div>

            


            {{-- Filter Buttons --}}
            <div class="card-body">
                
                {{-- Product Summary Overview --}}
            <div class="row text-center mb-4 g-3">
                {{-- Total Stock In --}}
                <div class="col-md-4">
                    <div class="p-4 rounded shadow-sm" style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white;">
                        <h6 class="text-uppercase fw-bold">
                            <i class="fas fa-boxes me-1"></i> Total In
                        </h6>
                        <p class="fs-4 fw-semibold mb-0">
                            {{ number_format($products->sum(fn($product) => $product->stocks->sum('quantity')), 3) }} 
                        </p>
                    </div>
                </div>

                {{-- Total Stock Out --}}
                <div class="col-md-4">
                    <div class="p-4 rounded shadow-sm" style="background: linear-gradient(45deg, #FF512F, #DD2476); color: white;">
                        <h6 class="text-uppercase fw-bold">
                            <i class="fas fa-dolly me-1"></i> Total Out
                        </h6>
                        <p class="fs-4 fw-semibold mb-0">
                            {{ number_format($products->sum(fn($product) => $product->outs->sum('quantity')), 3) }} 
                        </p>
                    </div>
                </div>

                {{-- Available Stock --}}
                <div class="col-md-4">
                    <div class="p-4 rounded shadow-sm" style="background: linear-gradient(45deg, #11998e, #38ef7d); color: white;">
                        <h6 class="text-uppercase fw-bold">
                            <i class="fas fa-warehouse me-1"></i> Available
                        </h6>
                        <p class="fs-4 fw-semibold mb-0">
                            {{ number_format(
                                $products->sum(fn($p) => $p->stocks->sum('quantity')) - 
                                $products->sum(fn($p) => $p->outs->sum('quantity')), 3) }}
                        </p>
                    </div>
                </div>
            </div>
                

                {{-- Report Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered" id="reportTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Total In</th>
                                <th>Total Out</th>
                                <th>Available</th>
                                <th>Profit (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalProfit = 0; @endphp
                            @forelse($products as $product)
                                @php
                                    $in = $product->stocks->sum('quantity');
                                    $out = $product->outs->sum('quantity');

                                    $buyTotal = $product->stocks->sum(fn($s) => $s->quantity * $s->buying_price);
                                    $sellTotal = $product->stocks->sum(fn($s) => $s->quantity * $s->selling_price);

                                    $avgBuy = $in > 0 ? $buyTotal / $in : 0;
                                    $avgSell = $in > 0 ? $sellTotal / $in : 0;

                                    $profit = ($avgSell * $out) - ($avgBuy * $out);
                                    $totalProfit += $profit;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ number_format($in, 3) }}</td>
                                    <td>{{ number_format($out, 3) }}</td>
                                    <td class="{{ ($in - $out) <= 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($in - $out, 3) }}
                                    </td>
                                    <td class="{{ $profit < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($profit, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end">Total Profit:</td>
                                <td>{{ number_format($totalProfit, 2) }} ৳</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#reportTable').DataTable({
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
