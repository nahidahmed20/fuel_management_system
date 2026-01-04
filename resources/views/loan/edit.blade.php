@extends('master')

@section('title', 'Edit Loan')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .card {
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1),
                        4px 8px 20px rgba(0, 0, 0, 0.05);
        }

        .select2-container--default .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }

        @media (max-width: 576px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .card-header .btn {
                margin-top: 10px;
                width: 100%;
                text-align: center;
            }

            .card-title {
                font-size: 1.1rem;
            }

            button[type="submit"] {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i> Edit Loan
                            </h4>
                        </div>
                        <a href="{{ route('loans.index') }}"
                           class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                           style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 5px;">
                            <i class="fas fa-arrow-left me-1"></i> Loan List
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('loans.update', $loan) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="borrower_id" class="form-label">Select Borrower <span class="text-danger">*</span></label>
                                    <select name="borrower_id" id="borrower_id" class="form-control select2" required>
                                        <option value="">-- Select Borrower --</option>
                                        @foreach($borrowers as $borrower)
                                            <option value="{{ $borrower->id }}" {{ $loan->borrower_id == $borrower->id ? 'selected' : '' }}>
                                                {{ $borrower->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="amount" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" value="{{ $loan->amount }}" step="any" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="loan_date" class="form-label">Loan Date <span class="text-danger">*</span></label>
                                    <input type="date" name="loan_date" id="loan_date" class="form-control" value="{{ $loan->loan_date }}" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea name="note" class="form-control" rows="2">{{ $loan->note }}</textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn text-white" style="background: linear-gradient(45deg, #ff7300, #f85108); padding: 8px 16px; border-radius: 5px; border: none;">
                                    Update Loan
                                </button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end -->
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "-- Select Borrower --",
            allowClear: true
        });

        // Optional: auto-set today's date if not already filled
        if (!$('#loan_date').val()) {
            let today = new Date().toISOString().split('T')[0];
            $('#loan_date').val(today);
        }
    });
</script>
@endpush
