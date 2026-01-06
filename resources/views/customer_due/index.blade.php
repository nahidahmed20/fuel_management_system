@extends('master')
@section('title', 'Customer Dues')

@section('content')
<div class="content-page">
    <div class="container-fluid py-4">
        <div id="today" class="row report-section">
            <div class="col-lg-12">
                
                <div class="card shadow-sm border-0">
                    {{-- Header --}}
                    
                    <div class="card-header-custom d-flex justify-content-between align-items-center mb-2">
                        <h4><i class="fas fa-users me-2"></i> All Customer Dues</h4>
                        <a href="{{ route('customer_due.create') }}" class="btn btn-sm btn-add d-flex align-items-center">
                            <i class="fa fa-plus-circle me-1"></i> Add Due
                        </a>
                    </div>

                    <form action="{{ route('customer_due.filter') }}" method="GET" class="row g-2 mb-3 align-items-end">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label small">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label small">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-2 d-flex">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </form>


                    {{-- Summary Section --}}
                    <div class="mt-3">
                        <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i> Summary</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-center shadow-sm border-0 bg-danger text-white">
                                    <div class="card-body">
                                        <h6>Total Due</h6>
                                        <h3>{{ number_format($totalDue, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center shadow-sm border-0 bg-success text-white">
                                    <div class="card-body">
                                        <h6>Total Payment</h6>
                                        <h3>{{ number_format($totalPayment, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center shadow-sm border-0 bg-primary text-white">
                                    <div class="card-body">
                                        <h6>Current Due</h6>
                                        <h3>{{ number_format($currentDue, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="customer_due_today">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color: white !important;">#</th>
                                        <th style="color: white !important;">Customer</th>
                                        <th style="color: white !important;">Amount Due (৳)</th>
                                        <th style="color: white !important;">Due Date</th>
                                        <th style="color: white !important;">Note</th>
                                        <th class="text-center" style="color: white !important;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $due)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $due->customer->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($due->amount_due, 3) }}</td>
                                            <td>{{ $due->due_date ?? '-' }}</td>
                                            <td>{{ $due->note ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('customer_due.edit', $due) }}" class="btn btn-sm btn-edit">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('customer_due.destroy', $due) }}" method="GET" class="d-inline-block delete-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-delete delete-btn">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                {{-- <tfoot>
                                    <tr class="fw-bold table-success">
                                        <td colspan="2">Total</td>
                                        <td>{{ number_format($today->sum('amount_due'), 2) }} ৳</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div> {{-- End Card Body --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#customer_due_today').DataTable({
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
<script>
    $(document).ready(function () { 
        $(".delete-btn").on("click", function(event) {
            event.preventDefault();
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
