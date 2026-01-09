@extends('master')

@section('title', 'Expense Report')

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
                <h4><i class="fas fa-money-bill-wave me-2"></i> Expense Report</h4>
            </div>

            {{-- Date Range Filter --}}
            <div class="card-body border-bottom">
                <div class="row g-3">
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
                @include('reports.partials.expense_table', ['expenses' => $expenses])
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    let fromDate = '';
    let toDate = '';

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

    function loadData() {
        $.ajax({
            url: "{{ route('reports.expense') }}",
            data: { from_date: fromDate, to_date: toDate },
            success: function(data) {
                $('#reportTable').html(data);
                $('#expenseTable').DataTable({
                    responsive: true,
                    destroy: true,
                    dom: 'lBfrtip',
                    buttons: [
                        { extend: 'excelHtml5', className: 'btn btn-success btn-sm', text: 'Excel' },
                        { extend: 'pdfHtml5', className: 'btn btn-danger btn-sm', text: 'PDF' },
                        { extend: 'print', className: 'btn btn-primary btn-sm', text: 'Print' }
                    ]
                });
            }
        });
    }

    loadData();
});
</script>
@endpush
