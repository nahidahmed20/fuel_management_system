<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Backend Bundle JavaScript -->
<script src="{{asset('dashboard')}}/assets/js/backend-bundle.min.js"></script>
    
<!-- Table Treeview JavaScript -->
<script src="{{asset('dashboard')}}/assets/js/table-treeview.js"></script>

<!-- Chart Custom JavaScript -->
<script src="{{asset('dashboard')}}/assets/js/customizer.js"></script>

<!-- Chart Custom JavaScript -->
<script async src="{{asset('dashboard')}}/assets/js/chart-custom.js"></script>

<!-- app JavaScript -->
<script src="{{asset('dashboard')}}/assets/js/app.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Bootstrap JavaScript Bundle (Popper Included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include jQuery and DataTable JS -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- toastr JS -->
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function () {
        // Show date and time
        function updateDateTime() {
            var now = new Date();
            var date = now.toLocaleDateString('en-GB');
            var time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            $('#currentDateTime').html('📅 ' + date + ' 🕒 ' + time);
        }
        updateDateTime();
        setInterval(updateDateTime, 60000);

        
    });
</script>
    
<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif
</script>
@stack('script')