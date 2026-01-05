<!doctype html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset('dashboard/assets/images/sfs.png') }}">
    <title>@yield('title')</title>


    @include('include.style')

<body class="  ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">

        <div class="iq-sidebar sidebar-default">
            <div class="iq-sidebar-logo d-flex align-items-center ">
                <a href="{{ route('home') }}" class="header-logo d-flex align-items-center">
                    <img src="{{ asset('dashboard/assets/images/surma-logo2.png') }}" class="img-fluid rounded-normal light-logo" alt="logo" style="height: 35px;">
                </a>
                <div class="iq-menu-bt-sidebar ml-0 d-block d-md-none">
                    <i class="fa-solid fa-bars wrapper-menu"></i>
                </div>
            </div>

            <div class="data-scrollbar" data-scroll="1">
                <nav class="iq-sidebar-menu">
                    <ul id="iq-sidebar-toggle" class="iq-menu list-unstyled p-0">

                        {{-- Dashboard --}}
                        <li class="">
                            <a href="{{ route('home') }}" class="d-flex align-items-center">
                                <i class="fas fa-tachometer-alt text-primary me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- Fuels Management --}}
                        @php
                            $fuelActive = Request::is('fuel/types*') || Request::is('nozzle*') || Request::is('fuel/stock*') || Request::is('fuel/seals*') || Request::is('fuel/short*');
                        @endphp

                        <li class="{{ $fuelActive ? 'active' : '' }}">
                            <a href="#fuel" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                            aria-expanded="{{ $fuelActive ? 'true' : 'false' }}">
                                <i class="fas fa-industry text-danger me-2"></i>
                                <span>Fuels Management</span>
                                <i class="fas fa-angle-down ms-auto"></i>
                            </a>

                            <ul id="fuel" class="iq-submenu collapse list-unstyled ps-4 {{ $fuelActive ? 'show' : '' }}">

                                {{-- Fuel Stock --}}
                                <li>
                                    <a href="{{ route('fuel.stock.create') }}" class="{{ Request::routeIs('fuel.stock.create') ? 'active' : '' }}">
                                        <i class="fas fa-fill-drip text-success me-2"></i> Add Fuel Stock
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('fuel.stock.index') }}" class="{{ Request::routeIs('fuel.stock.index') ? 'active' : '' }}">
                                        <i class="fas fa-boxes-stacked text-info me-2"></i> Fuel Stock List
                                    </a>
                                </li>

                                {{-- Nozzle Meter --}}
                                <li>
                                    <a href="{{ route('nozzle.meter.create') }}" class="{{ Request::routeIs('nozzle.meter.create') ? 'active' : '' }}">
                                        <i class="fas fa-gauge-high text-success me-2"></i> Create Nozzle Meter
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('nozzle.meter.index') }}" class="{{ Request::routeIs('nozzle.meter.index') ? 'active' : '' }}">
                                        <i class="fas fa-gauge text-info me-2"></i> Nozzle Meter List
                                    </a>
                                </li>

                                {{-- Fuel Sell --}}
                                <li>
                                    <a href="{{ route('fuel.sell.create') }}" class="{{ Request::routeIs('fuel.sell.create') ? 'active' : '' }}">
                                        <i class="fas fa-cart-shopping text-primary me-2"></i> Sell Fuel
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('fuel.sell.index') }}" class="{{ Request::routeIs('fuel.sell.index') ? 'active' : '' }}">
                                        <i class="fas fa-clock-rotate-left text-info me-2"></i> Fuel Sell History
                                    </a>
                                </li>

                                {{-- Fuel Short --}}
                                <li>
                                    <a href="{{ route('fuel.short.create') }}" class="{{ Request::routeIs('fuel.short.create') ? 'active' : '' }}">
                                        <i class="fas fa-triangle-exclamation text-danger me-2"></i> Add Fuel Short
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('fuel.short.index') }}" class="{{ Request::routeIs('fuel.short.index') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list text-warning me-2"></i> Fuel Short List
                                    </a>
                                </li>

                                {{-- Fuel Type --}}
                                <li>
                                    <a href="{{ route('fuel-type.create') }}" class="{{ Request::routeIs('fuel-type.create') ? 'active' : '' }}">
                                        <i class="fas fa-layer-group text-success me-2"></i> Add Fuel Type
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('fuel-type.index') }}" class="{{ Request::routeIs('fuel-type.index') ? 'active' : '' }}">
                                        <i class="fas fa-layer-group text-info me-2"></i> Fuel Type List
                                    </a>
                                </li>

                                {{-- Nozzles --}}
                                <li>
                                    <a href="{{ route('nozzle.create') }}" class="{{ Request::routeIs('nozzle.create') ? 'active' : '' }}">
                                        <i class="fas fa-faucet-drip text-success me-2"></i> Add Nozzle
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('nozzle.index') }}" class="{{ Request::routeIs('nozzle.index') ? 'active' : '' }}">
                                        <i class="fas fa-faucet text-info me-2"></i> List Nozzle
                                    </a>
                                </li>

                                {{-- Report --}}
                                <li>
                                    <a href="{{ route('fuel.report.view', 'today') }}" class="{{ Request::is('fuel/report/today') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line text-warning me-2"></i> Fuel Reports
                                    </a>
                                </li>

                            </ul>
                        </li>

                        @php
                            $mobilActive = Request::is('mobil*');
                        @endphp
                        <li class="{{ $mobilActive ? 'active' : '' }}">
                            <a href="#mobil" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                            aria-expanded="{{ $mobilActive ? 'true' : 'false' }}">
                                <i class="fas fa-oil-well text-dark me-2"></i>
                                <span>Mobils Management</span>
                                <i class="fas fa-angle-down ms-auto"></i>
                            </a>

                            <ul id="mobil" class="iq-submenu collapse list-unstyled ps-4 {{ $mobilActive ? 'show' : '' }}">

                                <li>
                                    <a href="{{ route('mobil.create') }}" class="{{ Request::routeIs('mobil.create') ? 'active' : '' }}">
                                        <i class="fas fa-fill-drip text-success me-2"></i> Add Mobil Entry
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('mobil.index') }}" class="{{ Request::routeIs('mobil.index') ? 'active' : '' }}">
                                        <i class="fas fa-box-open text-info me-2"></i> Mobil Entry List
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('mobilOut.create') }}" class="{{ Request::routeIs('mobilOut.create') ? 'active' : '' }}">
                                        <i class="fas fa-cart-plus text-success me-2"></i> Add Mobil Sell
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('mobilOut.index') }}" class="{{ Request::routeIs('mobilOut.index') ? 'active' : '' }}">
                                        <i class="fas fa-receipt text-info me-2"></i> Mobil Sales List
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('mobil.report.view','today') }}" class="{{ Request::is('mobil/report/today') ? 'active' : '' }}">
                                        <i class="fas fa-chart-pie text-warning me-2"></i> Mobil Reports
                                    </a>
                                </li>

                            </ul>
                        </li>


                        {{-- Products --}}
                        <li class="{{ Request::is('product*') ? 'active' : '' }}">
                            <a href="#product" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('product*') ? 'true' : 'false' }}">
                                <i class="fas fa-cubes text-secondary me-2"></i>
                                <span>Product Management</span>
                                <i class="fas fa-angle-down ms-auto"></i>
                            </a>

                            <ul id="product" class="iq-submenu collapse list-unstyled ps-4 {{ Request::is('product*') ? 'show' : '' }}">

                                <li>
                                    <a href="{{ route('product.stock.index') }}" class="{{ Request::routeIs('product.stock.index') ? 'active' : '' }}">
                                        <i class="fas fa-boxes-stacked text-primary me-2"></i> Product Stock List
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('product.stock.create') }}" class="{{ Request::routeIs('product.stock.create') ? 'active' : '' }}">
                                        <i class="fas fa-circle-plus text-success me-2"></i> Add Product Stock
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('product.sales.index') }}" class="{{ Request::routeIs('product.sales.index') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice-dollar text-warning me-2"></i> Product Sales List
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('product.sales.create') }}" class="{{ Request::routeIs('product.sales.create') ? 'active' : '' }}">
                                        <i class="fas fa-cart-shopping text-danger me-2"></i> Add Product Sell
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('product.index') }}" class="{{ Request::routeIs('product.index') ? 'active' : '' }}">
                                        <i class="fas fa-tags text-info me-2"></i> All Product Names
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('product.create') }}" class="{{ Request::routeIs('product.create') ? 'active' : '' }}">
                                        <i class="fas fa-tag text-success me-2"></i> Add Product Name
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('product.report.view','today') }}" class="{{ Request::routeIs('product.report.view') ? 'active' : '' }}">
                                        <i class="fas fa-chart-column text-warning me-2"></i> Sales & Stock Report
                                    </a>
                                </li>

                            </ul>
                        </li>


                        {{-- Customer Dues --}}

                        @php
                            $dueActive =
                                Request::routeIs('customers.*') ||
                                Request::routeIs('customer_due.*') ||
                                Request::routeIs('customer-due-payments.*');
                        @endphp

                        <li class="{{ $dueActive ? 'active' : '' }}">
                            <a href="#customerdue" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                            aria-expanded="{{ $dueActive ? 'true' : 'false' }}">
                                <i class="fas fa-user-clock text-primary me-2"></i>
                                <span>Customer Dues</span>
                                <i class="fas fa-angle-down ms-auto"></i>
                            </a>

                            <ul id="customerdue" class="iq-submenu collapse list-unstyled ps-4 {{ $dueActive ? 'show' : '' }}">

                                <li>
                                    <a href="{{ route('customers.index') }}" class="{{ Request::routeIs('customers.index') ? 'active' : '' }}">
                                        <i class="fas fa-address-book text-info me-2"></i> Customer List
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('customers.create') }}" class="{{ Request::routeIs('customers.create') ? 'active' : '' }}">
                                        <i class="fas fa-user-plus text-success me-2"></i> Customer Add
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('customer_due.index') }}" class="{{ Request::routeIs('customer_due.index') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice text-warning me-2"></i> Due List
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('customer_due.create') }}" class="{{ Request::routeIs('customer_due.create') ? 'active' : '' }}">
                                        <i class="fas fa-circle-plus text-success me-2"></i> Add Due
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('customer-due-payments.index') }}" class="{{ Request::routeIs('customer-due-payments.index') ? 'active' : '' }}">
                                        <i class="fas fa-credit-card text-info me-2"></i> Payment List
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('customer.due.report', 'today') }}" class="{{ Request::is('customer/due/report/today') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line text-warning me-2"></i> Customer Due Reports
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('customer.payment.report', 'today') }}" class="{{ Request::is('customer/payment/report/today') ? 'active' : '' }}">
                                        <i class="fas fa-chart-pie text-warning me-2"></i> Customer Payment Reports
                                    </a>
                                </li>

                            </ul>
                        </li>

                        {{-- Loan Management --}}
                        @php
                            $loanActive = Request::is('borrowers*') || Request::is('loans*') || Request::is('loan-payments*');
                        @endphp
                        <li class="{{ $loanActive ? 'active' : '' }}">
                            <a href="#borrowMoney" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                            aria-expanded="{{ $loanActive ? 'true' : 'false' }}">
                                <i class="fas fa-building-columns text-warning me-2"></i>
                                <span>Loan Management</span>
                                <i class="fas fa-angle-down ms-auto"></i>
                            </a>

                            <ul id="borrowMoney" class="iq-submenu collapse list-unstyled ps-4 {{ $loanActive ? 'show' : '' }}">

                                <li>
                                    <a href="{{ route('borrowers.index') }}" class="{{ Request::routeIs('borrowers.index') ? 'active' : '' }}">
                                        <i class="fas fa-user-group text-info me-2"></i> Borrowed By
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('borrowers.create') }}" class="{{ Request::routeIs('borrowers.create') ? 'active' : '' }}">
                                        <i class="fas fa-user-clock text-success me-2"></i> New Borrowing
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('loans.index') }}" class="{{ Request::routeIs('loans.index') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice-dollar text-warning me-2"></i> Loan Records
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('loans.create') }}" class="{{ Request::routeIs('loans.create') ? 'active' : '' }}">
                                        <i class="fas fa-circle-plus text-success me-2"></i> Create Loan
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('loan-payments.index') }}" class="{{ Request::routeIs('loan-payments.index') ? 'active' : '' }}">
                                        <i class="fas fa-clock-rotate-left text-info me-2"></i> Payment History
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('loan.due.report', 'today') }}" class="{{ Request::is('loan/due/report/today') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line text-warning me-2"></i> Loan Due Reports
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('loan.payment.report', 'today') }}" class="{{ Request::is('loan/payment/report/today') ? 'active' : '' }}">
                                        <i class="fas fa-chart-pie text-warning me-2"></i> Loan Payment Reports
                                    </a>
                                </li>

                            </ul>
                        </li>

                        {{-- Expense --}}
                        @php
                            $expenseActive = Request::is('expense*');
                        @endphp
                        <li class="{{ $expenseActive ? 'active' : '' }}">
                            <a href="#expense" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                            aria-expanded="{{ $expenseActive ? 'true' : 'false' }}">
                                <i class="fas fa-file-invoice-dollar text-success me-2"></i>
                                <span>Expense</span>
                                <i class="fas fa-angle-down ms-auto"></i>
                            </a>

                            <ul id="expense" class="iq-submenu collapse list-unstyled ps-4 {{ $expenseActive ? 'show' : '' }}">

                                <li>
                                    <a href="{{ route('expense.index') }}" class="{{ Request::routeIs('expense.index') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list text-info me-2"></i> List Expense
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('expense.create') }}" class="{{ Request::routeIs('expense.create') ? 'active' : '' }}">
                                        <i class="fas fa-circle-plus text-success me-2"></i> Add Expense
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('expense.report') }}" class="{{ Request::routeIs('expense.report') ? 'active' : '' }}">
                                        <i class="fas fa-chart-bar text-warning me-2"></i> Expense Report
                                    </a>
                                </li>

                            </ul>
                        </li>

                        {{-- Profit Report  --}}
                        @php
                            $profitActive = Request::is('profit*') || Request::is('fuel.report.view*');
                        @endphp
                        @if (Auth::check() && Auth::user()->role == 'admin')
                        <li class="{{ $profitActive ? 'active' : '' }}">
                            <a href="#profitReport" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                            aria-expanded="{{ $profitActive ? 'true' : 'false' }}">
                                <i class="fas fa-chart-line text-success me-2"></i>
                                <span>Profit Reports</span>
                                <i class="fas fa-angle-down ms-auto"></i>
                            </a>

                            <ul id="profitReport" class="iq-submenu collapse list-unstyled ps-4 {{ $profitActive ? 'show' : '' }}">

                                <li>
                                    <a href="{{ route('profit.all') }}" class="{{ Request::routeIs('profit.all') ? 'active' : '' }}">
                                        <i class="fas fa-sack-dollar text-success me-2"></i> Profit Overview
                                    </a>
                                </li>

                            </ul>
                        </li>
                         @endif
                        {{-- Current Blance  --}}
                        @if (Auth::check() && Auth::user()->role == 'admin')
                            @php
                                $balanceActive = Request::is('balance*');
                                $depositActive = Request::is('account/deposit*');
                                $withdrawActive = Request::is('account/withdraw*');
                            @endphp
                            <li class="{{ $balanceActive ? 'active' : '' }}">
                                <a href="#balanceMenu" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                                aria-expanded="{{ $balanceActive ? 'true' : 'false' }}">
                                    <i class="fas fa-building-columns text-warning me-2"></i>
                                    <span>Accounts Balance</span>
                                    <i class="fas fa-angle-down ms-auto"></i>
                                </a>

                                <ul id="balanceMenu" class="iq-submenu collapse list-unstyled ps-4 {{ $balanceActive ? 'show' : '' }}">

                                    {{-- Balance Overview --}}
                                    <li>
                                        <a href="{{ route('balance') }}" class="{{ Request::routeIs('balance') ? 'active' : '' }}">
                                            <i class="fas fa-scale-balanced text-warning me-2"></i> Total Balance
                                        </a>
                                    </li>

                                    {{-- Future Deposit / Withdraw icons ready --}}
                                    {{--
                                    <li>
                                        <a href="{{ route('account.create') }}">
                                            <i class="fas fa-circle-plus text-success me-2"></i> Add Deposit
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('account.index') }}">
                                            <i class="fas fa-clipboard-list text-primary me-2"></i> Deposit List
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('cash.withdraw.create') }}">
                                            <i class="fas fa-circle-minus text-danger me-2"></i> Add Withdraw
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('cash.withdraw.index') }}">
                                            <i class="fas fa-clock-rotate-left text-dark me-2"></i> Withdraw List
                                        </a>
                                    </li>
                                    --}}
                                </ul>
                            </li>

                        @endif
                        {{-- Manage Users --}}
                        @if (Auth::check() && Auth::user()->role == 'admin')
                            <li class="{{ Request::is('user*') ? 'active' : '' }}">
                                <a href="#people" class="collapsed d-flex align-items-center" data-bs-toggle="collapse"
                                aria-expanded="{{ Request::is('user*') ? 'true' : 'false' }}">
                                    <i class="fas fa-users-gear text-primary me-2"></i>
                                    <span>Manage Users</span>
                                    <i class="fas fa-angle-down ms-auto"></i>
                                </a>

                                <ul id="people" class="iq-submenu collapse list-unstyled ps-4 {{ Request::is('user*') ? 'show' : '' }}">
                                    <li>
                                        <a href="{{ route('permissions.index') }}" class="{{ Request::routeIs('permissions.index') ? 'active' : '' }}">
                                            <i class="fas fa-user-plus text-success me-2"></i> Permission
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('roles.index') }}" class="{{ Request::routeIs('roles.index') ? 'active' : '' }}">
                                            <i class="fas fa-user-plus text-success me-2"></i> Role
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('user.list') }}" class="{{ Request::routeIs('user.list') ? 'active' : '' }}">
                                            <i class="fas fa-clipboard-user text-info me-2"></i> User List
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('user.create') }}" class="{{ Request::routeIs('user.create') ? 'active' : '' }}">
                                            <i class="fas fa-user-plus text-success me-2"></i> Create User
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif

                    </ul>
                </nav>


                <div id="sidebar-bottom" class="position-relative sidebar-bottom"></div>
                <div class="p-3"></div>
            </div>
        </div>
<style>
    .x-flash {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1050;
        margin: 0 auto;
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        background-color: #f8d7da;
        color: #721c24;
        text-align: center;
        font-weight: 600;
    }
</style>


   @flasher_render

        <div class="iq-top-navbar ">
            <div class="iq-navbar-custom">
                <nav class="navbar navbar-expand-lg  px-3">
                    <div class="container-fluid d-flex justify-content-between align-items-center">

                        <!-- Logo & Site Name -->
                        <div class="d-flex align-items-center">
                            <i class="ri-menu-line wrapper-menu text-white fs-4 me-3" id="sidebarToggle"></i>
                            <a href="{{ route('home') }}" class="d-flex align-items-center text-white text-decoration-none">
                                <img src="{{ asset('dashboard/assets/images/icon.png') }}" alt="logo" height="40" class="me-2">
                                <h5 class="mb-0 d-none d-sm-block">Surma Filling Station</h5>
                            </a>
                        </div>

                        <!-- Date -->
                        <span class="text-white small d-none d-md-block" id="currentDateTime"></span>

                        <!-- Profile Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center dropdown-toggle text-white" id="profileDropdown" role="button">
                                <img src="{{ asset(Auth::user()->image) }}" class="rounded-circle" width="35" height="35" alt="User">
                            </a>

                            <div class="dropdown-menu dropdown-menu-end p-3 shadow" aria-labelledby="profileDropdown">
                                <div class="text-center">
                                    <img src="{{ asset(Auth::user()->image) }}" class="rounded-circle mb-2" width="60" height="60" alt="user">
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                    <div class="mt-3 d-flex justify-content-around">
                                        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-primary">Profile</a>
                                        <a href="#" class="btn btn-sm btn-outline-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    </div>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </div>

                    </div>
                </nav>
            </div>
        </div>



        @yield('content')



    </div>
    <!-- Wrapper End-->
    <!--<footer class="iq-footer bg-light py-3 mt-auto">-->
    <!--</footer>-->



    @include('include.script')
</body>

</html>










