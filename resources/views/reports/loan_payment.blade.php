@extends('master')

@section('title', 'Loan Payment Report')

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
                <h4><i class="fas fa-hand-holding-usd me-2"></i> Loan Payment Report</h4>
            </div>

            {{-- Filters --}}
            <div class="card-body border-bottom">
                <div class="row g-3">
                    {{-- Borrower --}}
                    <div class="col-md-4">
                        <label class="form-label">Borrower</label>
                        <select id="borrower_id" class="form-select select2">
                            <option value="">All Borrowers</option>
                            @foreach($borrowers as $borrower)
                                <option value="{{ $borrower->id }}">{{ $borrower->name }}</option>
                            @endforeach
                        </select>
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
                @include('reports.partials.loan_payment_table', ['payments' => $payments])
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

    $('#borrower_id').on('change', function() { loadData(); });

    function loadData() {
        $.ajax({
            url: "{{ route('reports.loan.payment') }}",
            data: {
                borrower_id: $('#borrower_id').val(),
                from_date: fromDate,
                to_date: toDate
            },
            success: function(data) {
                $('#reportTable').html(data);
                $('#loanPaymentTable').DataTable({
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
