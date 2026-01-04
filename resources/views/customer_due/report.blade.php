@extends('master')

@section('title', 'Customer Due Report')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                {{-- Header Section --}}
                
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center mb-5"
                    style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem; color:white;">
                    <h4 class="mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start w-100 w-md-auto">
                        <i class="fas fa-box-open me-2"></i>
                        Customer Due — {{ ucfirst($type) }} Report
                    </h4>
                    <a href="{{ route('customer.due.report.pdf', $type) }}" 
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
                        style="background: linear-gradient(to right, #17a2b8, #138496); ">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-chart-bar me-2"></i> Reports Overview
                        </h5>
                    </div>

                    <div class="card-body bg-light">
                        <div class="d-flex flex-wrap justify-content-center gap-3">

                            <a href="{{ route('customer.due.report', 'today') }}"
                                class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'today' ? 'btn-primary text-white' : '' }}"
                                style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                <i class="fas fa-calendar-day me-2"></i> Today
                            </a>

                            <a href="{{ route('customer.due.report', 'monthly') }}"
                                class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'monthly' ? 'btn-primary text-white' : '' }}"
                                style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                <i class="fas fa-calendar-alt me-2"></i> This Month
                            </a>

                            <a href="{{ route('customer.due.report', 'yearly') }}"
                                class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'yearly' ? 'btn-primary text-white' : '' }}"
                                style="background-color: #0967d3; color: #f8f8f8; transition: 0.3s;">
                                <i class="fas fa-calendar me-2"></i> This Year
                            </a>

                        </div>
                    </div>
                </div>

                {{-- Customer Due Table --}}
                <div class="card mb-4">
                    <div class="card-header  fw-bold" style="background: linear-gradient(to right, #17a2b8, #138496); color: white;">
                        <i class="fas fa-hand-holding-usd me-2"></i> Customer Dues
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="dueTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="color: white !important;">Date</th>
                                    <th style="color: white !important;">Customer Name</th>
                                    <th style="color: white !important;">Mobile</th>
                                    <th style="color: white !important;">Address</th>
                                    <th style="color: white !important;">Due Amount (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dues as $due)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($due->date)->format('d M Y') }}</td>
                                        <td>{{ $due->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $due->customer->mobile ?? 'N/A' }}</td>
                                        <td>{{ $due->customer->address ?? 'N/A' }}</td>
                                        <td>{{ number_format($due->amount_due, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-success fw-bold">
                                    <td colspan="4" class="text-end">Total Due:</td>
                                    <td>{{ number_format($totalDue, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#dueTable, #paymentTable').DataTable({
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
