@extends('master')

@section('title')
Fuel Report
@endsection

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                {{-- Fuel Report Title --}}
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center mb-5"
                    style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem; color:white;">
                    <h4 class="mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start w-100 w-md-auto">
                        <i class="fas fa-chart-line me-2"></i>
                        Fuel — {{ ucfirst($type) }} Report
                    </h4>
                

                    <a href="{{ route('fuel.report.download', $type) }}" 
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
                    <div class="card-header d-flex align-items-center justify-content-between" style="background: linear-gradient(to right, #17a2b8, #138496); ">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-chart-bar me-2"></i> Reports Overview
                        </h5>
                    </div>

                    <div class="card-body bg-light">
                        <div class="d-flex flex-wrap justify-content-center gap-3">

                            <a href="{{ route('fuel.report.view', 'today') }}" 
                            class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm" 
                            style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                <i class="fas fa-calendar-day me-2"></i> Today
                            </a>

                            <a href="{{ route('fuel.report.view', 'month') }}" 
                            class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm" 
                            style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                <i class="fas fa-calendar-alt me-2"></i> This Month
                            </a>

                            <a href="{{ route('fuel.report.view', 'year') }}" 
                            class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm" 
                            style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                <i class="fas fa-calendar me-2"></i> This Year
                            </a>

                        </div>
                    </div>
                </div>

                <hr style="border: none; height: 2px; background-color: #007bff;">
                {{-- Fuel Summary: In, Out, Available --}}
                <div class="row mb-4 g-3">

                    <!-- Total Fuel In -->
                    <div class="col-md-4">
                        <div class="card shadow-sm text-white" style="background: linear-gradient(45deg, #36D1DC, #5B86E5);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                                <div class="mb-2">
                                    <i class="fas fa-fill-drip fa-2x"></i> {{-- New Icon for Fuel In --}}
                                </div>
                                <div class="text-center">
                                    <h6 class="card-title mb-1 fw-semibold">Total Fuel In</h6>
                                    <h5 class="mb-0">{{ number_format($fuelStocks->sum('quantity'), 3) }} L</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Fuel Out -->
                    <div class="col-md-4">
                        <div class="card shadow-sm text-white" style="background: linear-gradient(45deg, #FF512F, #DD2476);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                                <div class="mb-2">
                                    <i class="fas fa-oil-can fa-2x"></i> {{-- New Icon for Fuel Out --}}
                                </div>
                                <div class="text-center">
                                    <h6 class="card-title mb-1 fw-semibold">Total Fuel Out</h6>
                                    <h5 class="mb-0">{{ number_format($fuelOuts->sum('quantity'), 3) }} L</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Fuel Stock -->
                    <div class="col-md-4">
                        <div class="card shadow-sm text-white" style="background: linear-gradient(45deg, #11998e, #38ef7d);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                                <div class="mb-2">
                                    <i class="fas fa-gas-pump fa-2x"></i>
                                </div>
                                <div class="text-center">
                                    <h6 class="card-title mb-1 fw-semibold">Available Stock</h6>
                                    <h5 class="mb-0">
                                        {{ number_format($fuelStocks->sum('quantity') - $fuelOuts->sum('quantity'), 3) }} L
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Profit Summary --}}
                <div class="card mb-4">
                    <div class="card-header text-white" style="background-color: #00809D; font-size: 1.25rem; font-weight: bold">
                        <i class="fas fa-coins me-1"></i> Profit Summary
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="profitTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="color:white !important;">Fuel Type</th>
                                    <th style="color:white !important;">Total In (L)</th>
                                    <th style="color:white !important;">Total Out (L)</th>
                                    <th style="color:white !important;">Buy Price</th>
                                    <th style="color:white !important;">Sell Price</th>
                                    <th style="color:white !important;">Total Purchase</th>
                                    <th style="color:white !important;">Total Sell</th>
                                    <th style="color:white !important;">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($profitSummary as $fuel => $data)
                                <tr>
                                    <td>{{ ucfirst($fuel) }}</td>
                                    <td>{{ number_format($data['total_in'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($data['total_out'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($data['buying_price'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($data['selling_price'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($data['total_purchase'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($data['total_sell'] ?? 0, 3) }}</td>
                                    <td class="text-success fw-bold">{{ number_format($data['profit'] ?? 0, 3) }}</td>
                                </tr>
                                @endforeach
                                <tfoot>
                                    <tr class="table-success fw-bold">
                                    <td colspan="5" class="text-end">Total</td>
                                    <td>{{ number_format($totalPurchaseSum ?? 0, 3) }}</td>
                                    <td>{{ number_format($totalSellSum ?? 0, 3) }}</td>
                                    <td class="text-success">{{ number_format($totalProfitSum ?? 0, 3) }}</td>
                                </tr>
                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Stock Entries --}}
                <div class="card mb-4">
                    <div class="card-header  text-white" style="background-color: #00809D; font-size: 1.25rem; font-weight: bold"><i class="fas fa-oil-can me-1"></i> Fuel Stock Entries</div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered"  id="stockTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="color:white !important;">Date</th>
                                    <th style="color:white !important;">Fuel</th>
                                    <th style="color:white !important;">Quantity</th>
                                    <th style="color:white !important;">Buy Price</th>
                                    <th style="color:white !important;">Sell Price</th>
                                    <th style="color:white !important;">Truck</th>
                                    <th style="color:white !important;">Company</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fuelStocks as $s)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}</td>
                                    <td>{{ ucfirst($s->fuelType->name ?? 'N/A') }}</td>
                                    <td>{{ number_format($s->quantity, 3) }} L</td>
                                    <td>{{ number_format($s->buying_price, 3) }}</td>
                                    <td>{{ number_format($s->selling_price, 3) }}</td>
                                    <td>{{ $s->truck_number }}</td>
                                    <td>{{ $s->company_name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Nozzle Meter Entries --}}
                <div class="card mb-4">
                    <div class="card-header  text-white" style="background-color: #00809D; font-size: 1.25rem; font-weight: bold"><i class="fas fa-tachometer-alt me-1"></i> Nozzle Meter Entries</div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered"  id="stockTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="color:white !important;">Date</th>
                                    <th style="color:white !important;">Fuel Type</th>
                                    <th style="color:white !important;">Nozzle</th>
                                    <th style="color:white !important;">Previous Meter</th>
                                    <th style="color:white !important;">Current Meter</th>
                                    <th style="color:white !important;">Difference</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($nozzleMeters as $meter)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($meter->date)->format('d M Y') }}</td>
                                <td>{{ ucfirst($meter->nozzle->fuelType->name ?? 'N/A') }}</td>
                                <td>{{ $meter->nozzle->name ?? 'N/A' }}</td>
                                <td>{{ number_format($meter->prev_meter, 3) }}</td>
                                <td>{{ number_format($meter->curr_meter, 3) }}</td>
                                <td>{{ number_format($meter->curr_meter - $meter->prev_meter, 3) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>

                {{-- Fuel Out Entries --}}
                <div class="card mb-4">
                    <div class="card-header text-white" style="background-color: #00809D; font-size: 1.25rem; font-weight: bold"><i class="fas fa-gas-pump me-1"></i> Fuel Out Entries</div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="outTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="color:white !important;">Date</th>
                                    <th style="color:white !important;">Fuel</th>
                                    <th style="color:white !important;">Nozzle</th>
                                    <th style="color:white !important;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fuelOuts as $o)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($o->date)->format('d M Y') }}</td>
                                    <td>{{ ucfirst($o->fuelType->name ?? 'N/A') }}</td>
                                    <td>{{ $o->nozzle->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($o->quantity, 3) }} L</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                


            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<!-- Bootstrap 5 + DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Initialize DataTables for All Tables -->
<script>
    $(document).ready(function() {
        ['#profitTable', '#stockTable', '#nozzleMeterTable', '#outTable'].forEach(function(id) {
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



<script>
    $(document).ready(function () {
        $('.report-btn').on('click', function () {
            let type = $(this).data('type');
            $('#reportResult').html('<div class="text-center py-5"><div class="spinner-border text-info"></div><br><small>Loading...</small></div>');

            $.ajax({
                url: `/fuel/report/data/${type}`,
                method: 'GET',
                success: function (res) {
                    let html = '';

                    html += `<div class="card mb-3">
                        <div class="card-header bg-dark text-white">
                            <i class="fas fa-coins me-1"></i> ${type.toUpperCase()} Profit Summary
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fuel Type</th>
                                        <th>Total In (L)</th>
                                        <th>Total Out (L)</th>
                                        <th>Buy Price</th>
                                        <th>Sell Price</th>
                                        <th>Total Purchase</th>
                                        <th>Total Sell</th>
                                        <th>Profit</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    $.each(res.profitSummary, function (fuel, data) {
                        html += `<tr>
                            <td>${fuel}</td>
                            <td>${parseFloat(data.total_in).toFixed(3)}</td>
                            <td>${parseFloat(data.total_out).toFixed(3)}</td>
                            <td>${parseFloat(data.buying_price).toFixed(3)}</td>
                            <td>${parseFloat(data.selling_price).toFixed(3)}</td>
                            <td>${parseFloat(data.total_purchase).toFixed(3)}</td>
                            <td>${parseFloat(data.total_sell).toFixed(3)}</td>
                            <td class="text-success fw-bold">${parseFloat(data.profit).toFixed(3)}</td>
                        </tr>`;
                    });

                    html += `</tbody></table></div></div>`;

                    $('#reportResult').html(html);
                },
                error: function () {
                    $('#reportResult').html('<div class="alert alert-danger">Failed to load report data.</div>');
                }
            });
        });
    });
</script>


@endpush
