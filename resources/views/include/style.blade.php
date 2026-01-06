<!-- Favicon -->
<link rel="stylesheet" href="{{asset('dashboard')}}/assets/css/backend-plugin.min.css">
<link rel="stylesheet" href="{{asset('dashboard')}}/assets/css/backende209.css?v=1.0.0">
<link rel="stylesheet" href="{{asset('dashboard')}}/assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="{{asset('dashboard')}}/assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
<link rel="stylesheet" href="{{asset('dashboard')}}/assets/vendor/remixicon/fonts/remixicon.css">  
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Bootstrap CSS -->
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<!-- Line Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome.min.css">
<!-- Bootstrap Icons CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .card-header-custom {
        background-color: #27548A;
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 500;
    }

    #fuel_stock tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-back,
    .btn-edit,
    .btn-delete {
        text-align: center;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 3px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        color: white;
    }
    

    .btn-edit {
        background-color: #20c997;
    }

    .btn-delete {
        background-color: #e63946;
        color:white;
        padding: 7px 10px;
    }

    @media (max-width: 576px) {
        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .table th, .table td {
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .action-btn-group {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        .action-btn-group > div {
            width: 100%;
        }

        .btn-edit, .btn-delete {
            width: 100% !important;
        }
    }
</style>
<style>
    /* Mobile-specific fix */
    @media (max-width: 768px) {
        .dropdown-menu {
            right: 0 !important;
            left: auto !important;
            transform: translateY(10px) !important;
            min-width: 240px;
        }
    }

    @media (min-width: 769px) {
        .dropdown-menu {
            top: 100% !important;
            right: 15px !important; 
            left: auto !important;
            margin-top: 10px;
            min-width: 240px;
            transform: translateX(-30px); 
        }
    }

    .dropdown-menu.show {
        display: block;
        z-index: 1000;
    }

    /* Pagination button spacing reduce */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 5px 0px !important;
        margin: 0 2px !important;
        border-radius: 0px !important; 
        font-size: 14px;
    }

    /* Active page highlighting */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        color: white !important;
        border: 1px solid #0d6efd !important;
    }

    /* Hover effect */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e2e6ea !important;
        border: 0px solid #ddd !important;
        color: #333 !important;
    }

    .iq-sidebar {
        background-color: #2c3e50;
        color: #ecf0f1; 
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .iq-sidebar .iq-sidebar-logo {
        background-color: #1a2733;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #34495e;
        display: flex;
        align-items: center;
    }

    .iq-sidebar .iq-sidebar-logo h5.logo-title {
        color: #ecf0f1;
        font-weight: 600;
        margin-left: 10px;
        font-size: 1.2rem;
    }

    .iq-sidebar a {
        color: #f5f7fa !important; 
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .iq-sidebar a:hover, .iq-sidebar a:focus {
        background-color: #34495e;
        color: #ffdd57 !important; 
        text-shadow: 0 0 3px rgba(255, 221, 87, 0.6);
    }

    .iq-sidebar ul.iq-menu > li > a {
        padding: 12px 20px;
        font-size: 16px;
        border-radius: 6px;
    }

    .iq-sidebar ul.iq-menu > li.active > a,
    .iq-sidebar ul.iq-menu > li > a.active {
        background-color: #ffcc00;
        color: #2c3e50 !important;
        font-weight: 700;
        box-shadow: 0 0 8px #ffcc00aa;
    }

    .iq-sidebar ul.iq-submenu li a {
        padding-left: 40px;
        font-size: 14px;
        color: #dbe6f0 !important;
        border-radius: 4px;
        transition: color 0.3s ease, background-color 0.3s ease;
    }

    .iq-sidebar ul.iq-submenu li a:hover {
        background-color: #ffcc00;
        color: #2c3e50 !important;
        font-weight: 600;
    }

    .iq-sidebar i.fas, .iq-sidebar i.fa-solid {
        font-size: 18px;
        color: #f5f7fa;
        transition: color 0.3s ease;
    }

    .iq-sidebar a:hover i.fas, 
    .iq-sidebar a:hover i.fa-solid {
        color: #ffdd57;
    }

    .iq-sidebar a.collapsed .fa-angle-down {
        color: #bdc3c7;
        transition: color 0.3s ease;
    }

    .iq-sidebar a:not(.collapsed):hover .fa-angle-down {
        color: #ffcc00;
    }

    .data-scrollbar {
        max-height: calc(100vh - 80px);
        overflow-y: auto;
    }

    .data-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .data-scrollbar::-webkit-scrollbar-track {
        background: #2c3e50;
    }

    .data-scrollbar::-webkit-scrollbar-thumb {
        background-color: #ffcc00;
        border-radius: 3px;
    }

    .p-3 {
        padding: 1rem !important;
    }

    .iq-top-navbar {
        background-color: #1f2d3d;
        border-bottom: 2px solid #00B4D8;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .search-input {
        background-color: #2c3e50;
        border: none;
        border-radius: 25px;
        padding-left: 20px;
        color: #fff;
    }

    .search-input::placeholder {
        color: #aaa;
    }

    .navbar-nav .nav-item a {
        color: #fff;
    }

    .dropdown-menu {
        background-color: #2c3e50;
        color: #fff;
        border-radius: 8px;
    }

    .dropdown-menu a.btn {
        color: #fff !important;
    }

    .badge-counter {
        font-size: 12px;
        padding: 2px 6px;
    }
    .iq-sidebar-menu a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
        padding: 0.5rem 1rem;
    }

    .iq-sidebar-menu a .fas {
        /* icon spacing handled by Bootstrap me-2 */
        font-size: 1.1rem;
    }

    .iq-sidebar-menu a .fa-angle-down {
        margin-left: auto; /* Push arrow icon to right */
        transition: transform 0.3s ease;
    }

    /* Rotate arrow when submenu is shown */
    .iq-sidebar-menu ul.collapse.show > li > a.collapsed .fa-angle-down {
        transform: rotate(180deg);
    }

    /* Indent submenu items */
    .iq-submenu li a {
        padding-left: 1rem;
        font-size: 0.95rem;
    }

    /* For nested submenu indentation */
    .iq-submenu {
        padding-left: 1rem;
    }
</style>
@stack('style')