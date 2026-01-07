@extends('master')

@section('title')
    Mobil Sell List
@endsection


@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background-color: #27548A; color: #fff;">
                        <div class="header-title">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-oil-can me-2"></i> Mobil Sell (Out) List
                            </h4>
                        </div>
                        <a href="{{ route('mobilOut.create') }}" 
                        class="btn btn-sm d-flex justify-content-center align-items-center mt-2 mt-sm-0"
                        style="background: linear-gradient(45deg, #36D1DC, #5B86E5); color: white; border: none; font-weight: 500; padding: 6px 12px; border-radius: 2px;">
                            Add 
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="outTable" class="table table-bordered ">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="color: white!important">#</th>
                                        <th style="color: white!important">Name</th>
                                        <th style="color: white!important">Quantity (L)</th>
                                        <th style="color: white!important">Total Sell (৳)</th>
                                        <th style="color: white!important">Date</th>
                                        <th style="color: white!important" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($outs as $key => $out)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $out->name }}</td>
                                        <td>{{ number_format($out->quantity, 3) }}</td>
                                        <td>৳{{ number_format($out->total_sell, 3) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($out->date)->format('d M Y') }}</td>
                                        <td class="action-btns text-center">
                                            <a href="{{ route('mobilOut.edit', $out->id) }}" class="btn btn-sm btn-edit" style="background: #20c997; color: white;">
                                                <i class="fas fa-edit"></i> 
                                            </a>
                                            {{-- Optional Delete --}}
                                            
                                            <form action="{{ route('mobilOut.destroy', $out->id) }}" method="GET" style="display:inline-block;" class="delete-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-delete" style="background: #e63946; color: white;">
                                                    <i class="fas fa-trash-alt"></i> 
                                                </button>
                                            </form>
                                        </td>
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
</div>
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#outTable').DataTable({
            responsive: true
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault(); 

            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); 
                }
            });
        });
    });
</script>
@endpush
