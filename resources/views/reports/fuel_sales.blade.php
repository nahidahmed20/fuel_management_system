@extends('master')

@section('title', 'Fuel Sales Report')

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
    .select2-container--default .select2-selection--single {
        border-radius: 0px !important;
    }
    .select2-container .select2-selection--single {
        height: 36px !important;
    }
    .form-control {
        height: 36px !important;
        border: 1px solid #bfbfbf;
    }
    .dataTables_wrapper .dt-buttons { 
        position: absolute; 
        left: 50%; 
        transform: translateX(-50%);
    }
</style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">

            {{-- Header --}}
            <div class="card-header-custom">
                <h4 class="mb-0">
                    <i class="fas fa-gas-pump me-2"></i> Fuel Sales Report
                </h4>
            </div>

            {{-- Filters --}}
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Fuel Type</label>
                        <select id="fuel_type_id" class="form-select select2">
                            <option value="">All Fuel</option>
                            @foreach($fuelTypes as $fuel)
                                <option value="{{ $fuel->id }}">{{ $fuel->name }}</option>
                            @endforeach
                        </select>
                    </div>

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
                @include('reports.partials.fuel_sales_table', ['fuelSales' => $fuelSales])
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

    // Select2
    $('.select2').select2({ width: '100%' });

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

    $('#fuel_type_id').on('change', function () { loadData(); });

    function loadData() {
        $.ajax({
            url: "{{ route('reports.fuel.sales') }}",
            data: {
                fuel_type_id: $('#fuel_type_id').val(),
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
        $('#fuelSalesTable').DataTable({
            responsive: true,
            destroy: true,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'excelHtml5', className: 'btn btn-sm btn-success', text: '<i class="fa fa-file-excel"></i> Excel' },
                { extend: 'pdfHtml5', className: 'btn btn-sm btn-danger', text: '<i class="fa fa-file-pdf"></i> PDF' },
                { extend: 'print', className: 'btn btn-sm btn-primary', text: '<i class="fa fa-print"></i> Print' }
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                            .on('keyup change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                });
            },
            language: { paginate: { previous: "<i class='fas fa-angle-left'></i>", next: "<i class='fas fa-angle-right'></i>" } }
        });
    }

    // Initial load
    loadData();
});
</script>
@endpush
