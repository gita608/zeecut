<!-- core:js -->
<script src="{{asset('assets/vendors/core/core.js')}}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{asset('assets/vendors/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/vendors/apexcharts/apexcharts.min.js')}}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{asset('assets/vendors/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('assets/js/template.js')}}"></script>
<!-- endinject -->
{{-- <script src="{{asset('assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('assets/js/data-table.js')}}"></script> --}}

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        $('#table1').DataTable({
            dom: 'Bfrtip',
            pageLength: 10, // Default number of rows to display
            buttons: [
                { extend: 'excelHtml5', className: 'btn btn-sm', text: '<i class="fas fa-file-excel"></i> Excel' },
                { extend: 'csvHtml5', className: 'btn btn-sm', text: '<i class="fas fa-file-csv"></i> CSV' },
                { extend: 'pdfHtml5', className: 'btn btn-sm', text: '<i class="fas fa-file-pdf"></i> PDF' }
            ]
        });
    });

</script>



<!-- Custom js for this page -->
<script src="{{asset('assets/js/dashboard-light.js')}}"></script>
<!-- End custom js for this page -->

</body>

</html>