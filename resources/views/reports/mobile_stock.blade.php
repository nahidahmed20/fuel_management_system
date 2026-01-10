@extends('master')

@section('title', 'Mobile Stock Report')

@push('style')
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        padding: 5px;
    }

    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }

    .dataTables_wrapper .dt-buttons {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .dataTables_wrapper .dt-buttons .dt-button {
        margin: 0 5px;
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">

            {{-- Header --}}
            <div class="card-header-custom">
                <h4 class="mb-0"><i class="fas fa-mobile-alt me-2"></i> Mobile Stock Report</h4>
            </div>

            {{-- Filters --}}
            <div class="card-body border-bottom">
                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-4">
                        <label class="form-label">Mobile Name</label>
                        <input type="text" id="mobile_name" class="form-control" placeholder="Enter mobile name">
                    </div>

                    {{-- Date Range --}}
                    <div class="col-md-4">
                        <label class="form-label">Date Range</label>
                        <input type="text" id="dateRange" class="form-control" placeholder="Select date range">
                        <input type="hidden" id="from_date">
                        <input type="hidden" id="to_date">
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-body" id="reportTable">
                @include('reports.partials.mobile_stock_table', ['mobileStocks' => $mobileStocks])
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {

    let fromDate = '';
    let toDate = '';

    // Date Range Picker
    $('#dateRange').daterangepicker({
        autoUpdateInput: false,
        locale: { format: 'YYYY-MM-DD' },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1,'days'), moment().subtract(1,'days')],
            'Last Week': [moment().subtract(6,'days'), moment()],
            'Last Month': [moment().subtract(1,'month'), moment()],
            'Last 6 Months': [moment().subtract(6,'months'), moment()],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            'Last Year': [moment().subtract(1,'year').startOf('year'), moment().subtract(1,'year').endOf('year')]
        }
    });

    $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
        fromDate = picker.startDate.format('YYYY-MM-DD');
        toDate   = picker.endDate.format('YYYY-MM-DD');
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        loadData();
    });

    $('#mobile_name').on('keyup', function () { loadData(); });

    function loadData() {
        $.ajax({
            url: "{{ route('reports.mobile.stock') }}",
            data: {
                name: $('#mobile_name').val(),
                from_date: fromDate,
                to_date: toDate
            },
            success: function (data) {
                $('#reportTable').html(data);
                initializeDataTable();
            }
        });
    }

    function initializeDataTable() {
        $('#mobileStockTable').DataTable({
            responsive: true,
            destroy: true,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'excelHtml5', className: 'btn btn-sm btn-success', text: '<i class="fa fa-file-excel"></i> Excel' },
                { extend: 'pdfHtml5', className: 'btn btn-sm btn-danger', text: '<i class="fa fa-file-pdf"></i> PDF' },
                { extend: 'print', className: 'btn btn-sm btn-primary', text: '<i class="fa fa-print"></i> Print' }
            ],
            language: { paginate: { previous: "<i class='fas fa-angle-left'></i>", next: "<i class='fas fa-angle-right'></i>" } }
        });
    }

    // Initial load
    loadData();

});
</script>
@endpush
