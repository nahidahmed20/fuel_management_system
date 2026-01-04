@extends('master')

@section('title', 'Dashboard')

@section('content')
@push('style')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 576px) {
        .card-title, .card-text {
            font-size: 14px;
        }

        .fs-3 {
            font-size: 1.25rem !important;
        }

        h5 {
            font-size: 1rem;
        }

        .table th, .table td {
            white-space: nowrap;
            font-size: 13px;
        }

        .card .card-body {
            text-align: center;
        }
    }

    canvas#fuelSalesChart {
        width: 100% !important;
        height: auto !important;
    }
</style>

@endpush
<div class="content-page">
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="mb-4">
            <h2 class="fw-bold text-dark">Dashboard Overview</h2>
            <p class="text-muted">Welcome, {{ Auth::user()->name }}! Here's the summary of today's performance and recent fuel sales.</p>
            <hr>
        </div>

        
{{-- Top 4 Summary Cards --}}
    <div class="row g-3 mb-4">

        <!-- Fuel Sales Today -->
        <div class="col-sm-6 col-md-3">
            <div class="card text-white shadow-sm h-100" style="background-color:#254D70">
                <div class="card-body">
                    <h5 class="card-title">Fuel Sales Today</h5>
                    <p class="card-text fs-3 fw-semibold">{{ number_format($fuelSaleToday, 3) }} ৳</p>
                </div>
            </div>
        </div>

        <!-- Product Sales Today -->
        <div class="col-sm-6 col-md-3">
            <div class="card text-white shadow-sm h-100" style="background-color:#00809D">
                <div class="card-body">
                    <h5 class="card-title">Product Sales Today</h5>
                    <p class="card-text fs-3 fw-semibold">{{ number_format($productSaleToday, 3) }} ৳</p>
                </div>
            </div>
        </div>

        <!-- Product Stock -->
        <div class="col-sm-6 col-md-3">
            <div class="card text-white shadow-sm h-100" style="background-color:#FF4F0F">
                <div class="card-body">
                    <h5 class="card-title">Product Stock</h5>
                    <p class="card-text fs-3 fw-semibold">{{ number_format($productStock, 3) }}</p>
                </div>
            </div>
        </div>

        <!-- Expense Today -->
        <div class="col-sm-6 col-md-3">
            <div class="card text-white shadow-sm h-100" style="background-color:#0ABAB5">
                <div class="card-body">
                    <h5 class="card-title">Expense Today</h5>
                    <p class="card-text fs-3 fw-semibold">{{ number_format($expenseToday, 3) }} ৳</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Fuel & Mobil Stock Cards --}}
        <div class="row g-3 mb-3">
            @foreach($fuelStocks as $fuel)
                <div class="col-sm-6 col-md-3">
                    <div class="card text-white shadow-sm h-100" style="background-color:#075B5E">
                        <div class="card-body">
                            <h5 class="card-title">{{ $fuel->name }} Stock</h5>
                            <p class="card-text fs-3 fw-semibold">
                                {{ number_format($fuel->available_stock, 3) }} L
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Mobil Stock -->
            <div class="col-sm-6 col-md-3">
                <div class="card text-white shadow-sm h-100" style="background-color:#075B5E">
                    <div class="card-body">
                        <h5 class="card-title">Mobil Stock</h5>
                        <p class="card-text fs-3 fw-semibold">
                            {{ number_format($mobilStock, 3) }} L
                        </p>
                    </div>
                </div>
            </div>
        </div>



        <!-- Fuel Sale Chart -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header  text-white" style="background-color:#D84040">
                <h5 class="mb-0">Fuel Sales Last 7 Days</h5>
            </div>
            <div class="card-body">
                <canvas id="fuelSalesChart"></canvas>
            </div>
        </div>

    
    </div>
</div>
@endsection



@push('script')
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#latestFuelSalesTable').DataTable({
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
    $(document).ready(function() {

        // Prepare Chart.js data
        const ctx = document.getElementById('fuelSalesChart').getContext('2d');

        const labels = @json($fuelChartLabels->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        }));

        const petrolData = @json($petrolData);
        const dieselData = @json($dieselData);
        const octenData = @json($octenData);

        const fuelSalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Petrol',
                        data: petrolData,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Diesel',
                        data: dieselData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Octen',
                        data: octenData,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold',
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'nearest',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity (Liters)'
                        },
                        ticks: {
                            stepSize: 5,
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

