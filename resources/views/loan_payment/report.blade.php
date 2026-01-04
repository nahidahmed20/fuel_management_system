@extends('master')

@section('title', 'Loan Payment Report')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                {{-- Header --}}
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center mb-5"
                    style="background: linear-gradient(90deg, #1e3c72, #2a5298); font-size: 1.25rem; color:white;">
                    <h4 class="mb-3 mb-md-0 d-flex align-items-center">
                        <i class="fas fa-receipt me-2"></i> Loan Payment — {{ ucfirst($type) }} Report
                    </h4>
                    <a href="{{ route('loan.payment.report.pdf', $type) }}" 
                        class="btn btn-outline-light btn-sm px-4 py-2 rounded-pill d-flex align-items-center shadow-sm border-0"
                        style="background: linear-gradient(45deg, #ff6a00, #ee0979); color: white;"
                        target="_blank">
                        <i class="fas fa-file-pdf me-2 text-white fs-5"></i>
                        Download PDF
                    </a>
                </div>

                {{-- Filter Buttons --}}
                <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header d-flex align-items-center justify-content-between"
                        style="background: linear-gradient(to right, #17a2b8, #138496);">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-filter me-2"></i> Filter Report
                        </h5>
                    </div>
                    <div class="card-body bg-light">
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('loan.payment.report', 'today') }}"
                               class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'today' ? 'btn-primary text-white' : '' }}"
                               style="background-color: #0967d3; color: #f8f8f8;">
                                <i class="fas fa-calendar-day me-2"></i> Today
                            </a>

                            <a href="{{ route('loan.payment.report', 'monthly') }}"
                               class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'monthly' ? 'btn-primary text-white' : '' }}"
                               style="background-color: #0967d3; color: #f8f8f8;">
                                <i class="fas fa-calendar-alt me-2"></i> This Month
                            </a>

                            <a href="{{ route('loan.payment.report', 'yearly') }}"
                               class="btn d-flex align-items-center px-4 py-2 rounded-pill shadow-sm {{ $type == 'yearly' ? 'btn-primary text-white' : '' }}"
                               style="background-color: #0967d3; color: #f8f8f8;">
                                <i class="fas fa-calendar me-2"></i> This Year
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Payment Table --}}
                <div class="card mb-4">
                    <div class="card-header fw-bold" style="background: linear-gradient(to right, #17a2b8, #138496); color: white;">
                        <i class="fas fa-money-check-alt me-2"></i> Loan Payment Records
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="paymentTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="color: white !important;">Payment Date</th>
                                    <th style="color: white !important;">Borrower Name</th>
                                    <th style="color: white !important;">Mobile</th>
                                    <th style="color: white !important;">Address</th>
                                    <th style="color: white !important;">Amount T</th>
                                    <th style="color: white !important;">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                        <td>{{ $payment->borrower->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->borrower->mobile ?? 'N/A' }}</td>
                                        <td>{{ $payment->borrower->address ?? 'N/A' }}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->note ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">No loan payments found for this period.</td>
                                        </tr>
                                    </tfoot>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-success fw-bold">
                                    <td colspan="4" class="text-end">Total Paid:</td>
                                    <td colspan="2">{{ number_format($totalPaid, 2) }}</td>
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
        $('#paymentTable').DataTable({
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
