@extends('master')

@section('title', 'Customer Due Report')

@push('style')
<style>
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
    .dataTables_wrapper .dt-buttons .dt-button {
        margin: 0 5px;
    }
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

            <div class="card-header-custom">
                <h4><i class="fas fa-user-friends me-2"></i> Customer Due Report</h4>
            </div>

            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Customer</label>
                        <select id="customer_id" class="form-select select2">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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

            <div class="card-body" id="reportTable">
                @include('reports.partials.customer_due_table', ['customerDues' => $customerDues])
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')


<script>
$(document).ready(function() {
    let fromDate = '';
    let toDate = '';

    $('.select2').select2({ width: '100%' });

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

    $('#customer_id').on('change', function() { loadData(); });

    function loadData() {
        $.ajax({
            url: "{{ route('reports.customer.due') }}",
            data: {
                customer_id: $('#customer_id').val(),
                from_date: fromDate,
                to_date: toDate
            },
            success: function(data) {
                $('#reportTable').html(data);
                $('#customerDueTable').DataTable({
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
