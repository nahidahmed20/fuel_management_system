@extends('master')

@section('title')
Mobil Report — {{ ucfirst($type) }}
@endsection

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    {{-- Mobil Report Title --}}
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center mb-5"
                        style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem; color:white;">
                        <h4 class="mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start w-100 w-md-auto">
                            <i class="fas fa-oil-can me-2"></i>
                            Mobil — {{ ucfirst($type) }} Report
                        </h4>
                        <a href="{{ route('mobil.report.download', $type) }}" 
                        class="btn btn-outline-light btn-sm px-4 py-2 rounded-pill d-flex align-items-center shadow-sm border-0"
                        style="background: linear-gradient(45deg, #ff6a00, #ee0979); color: white; transition: 0.3s; min-width: 150px;"
                        target="_blank">
                            <i class="fas fa-file-pdf me-2 text-white fs-5"></i>
                            Download PDF
                        </a>
                    </div>


                    {{-- Navigation Buttons --}}
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

                                <a href="{{ route('mobil.report.view', 'today') }}"
                                    class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'today' ? 'btn-primary text-white' : '' }}"
                                    style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                    <i class="fas fa-calendar-day me-2"></i> Today
                                </a>

                                <a href="{{ route('mobil.report.view', 'monthly') }}"
                                    class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'monthly' ? 'btn-primary text-white' : '' }}"
                                    style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                    <i class="fas fa-calendar-alt me-2"></i> This Month
                                </a>

                                <a href="{{ route('mobil.report.view', 'yearly') }}"
                                    class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'yearly' ? 'btn-primary text-white' : '' }}"
                                    style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                    <i class="fas fa-calendar me-2"></i> This Year
                                </a>

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Summary Section --}}
                        <div class="row text-center mb-4 g-3">
                            <!-- Total Stock -->
                            <div class="col-md-4">
                                <div class="p-4 rounded shadow-sm" style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white;">
                                    <h6 class="text-uppercase fw-bold">
                                        <i class="fas fa-boxes me-1"></i> Total Stock
                                    </h6>
                                    <p class="fs-4 fw-semibold mb-0">{{ number_format($totalStock, 3) }} L</p>
                                </div>
                            </div>

                            <!-- Total Out -->
                            <div class="col-md-4">
                                <div class="p-4 rounded shadow-sm" style="background: linear-gradient(45deg, #FF512F, #DD2476); color: white;">
                                    <h6 class="text-uppercase fw-bold">
                                        <i class="fas fa-truck-loading me-1"></i> Total Out
                                    </h6>
                                    <p class="fs-4 fw-semibold mb-0">{{ number_format($totalOut, 3) }} L</p>
                                </div>
                            </div>

                            <!-- Available Stock -->
                            <div class="col-md-4">
                                <div class="p-4 rounded shadow-sm" style="background: linear-gradient(45deg, #11998e, #38ef7d); color: white;">
                                    <h6 class="text-uppercase fw-bold">
                                        <i class="fas fa-balance-scale me-1"></i> Available Stock
                                    </h6>
                                    <p class="fs-4 fw-semibold mb-0">{{ number_format($currentStock, 3) }} L</p>
                                </div>
                            </div>
                        </div>


                        {{-- Mobil Stock Summary --}}
                        <div class="card mb-4">
                            <div class="card-header text-white" style="background-color: #00809D; font-size: 1.25rem; font-weight: bold">
                                <i class="fas fa-warehouse me-1"></i> Mobil Stock Records
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped" id="stockTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="color: white !important;">#</th>
                                            <th style="color: white !important;"><i class="far fa-calendar-alt"></i> Date</th>
                                            <th style="color: white !important;"><i class="fas fa-oil-can"></i> Name</th>
                                            <th style="color: white !important;"><i class="fas fa-tint"></i> Quantity (L)</th>
                                            <th style="color: white !important;"><i class="fas fa-hand-holding-usd"></i> Buying Price</th>
                                            <th style="color: white !important;"><i class="fas fa-hand-holding-usd"></i> Selling Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stocks as $stock)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($stock->date)->format('d M Y') }}</td>
                                                <td>{{ $stock->name }}</td>
                                                <td>{{ number_format($stock->quantity, 3) }}</td>
                                                <td>{{ number_format($stock->buying_price, 2) }}</td>
                                                <td>{{ number_format($stock->selling_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        {{-- Mobil Out Summary --}}
                        <div class="card mb-4">
                            <div class="card-header text-white" style="background-color: #FF4F0F; font-size: 1.25rem; font-weight: bold">
                                <i class="fas fa-share-square me-1"></i> Mobil Out Records
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped" id="outTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="color: white !important;">#</th>
                                            <th style="color: white !important;"><i class="far fa-calendar-alt"></i> Date</th>
                                            <th style="color: white !important;"><i class="fas fa-oil-can"></i> Name</th>
                                            <th style="color: white !important;"><i class="fas fa-tint"></i> Quantity (L)</th>
                                            <th style="color: white !important;"><i class="fas fa-hand-holding-usd"></i> Total Buy</th>
                                            <th style="color: white !important;"><i class="fas fa-hand-holding-usd"></i> Total Sell</th>
                                            <th style="color: white !important;"><i class="fas fa-chart-line"></i> Profit</th> {{-- New Column --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($outs as $out)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($out->date)->format('d M Y') }}</td>
                                                <td>{{ $out->name }}</td>
                                                <td>{{ number_format($out->quantity, 3) }}</td>
                                                <td>{{ number_format($out->total_buy, 2) }}</td>
                                                <td>{{ number_format($out->total_sell, 2) }}</td>
                                                <td class="fw-semibold text-success">
                                                    {{ number_format($out->total_sell - $out->total_buy, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> {{-- card-body --}}
                </div> {{-- card --}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
    $(document).ready(function() {
        ['#stockTable', '#outTable'].forEach(function(id) {
            $(id).DataTable({
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
    });
</script>
@endpush
