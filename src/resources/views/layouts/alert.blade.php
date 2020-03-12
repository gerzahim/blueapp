@if( Session::has('success') )
    <script type="text/javascript">
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.success( '{{ Session::get('success') }}')
        {{ Session::forget('success') }}
    </script>
@endif


@if( Session::has('info') )
    <script type="text/javascript">
        toastr.info( '{{ Session::get('info') }}', 'Info Message', {timeOut: 5000})
        {{ Session::forget('info') }}
    </script>
@endif


@if( Session::has('warning') )
    <script type="text/javascript">
        toastr.warning( '{{ Session::get('warning') }}', 'Warning Alert', {timeOut: 5000})
        {{ Session::forget('warning') }}
    </script>
@endif


@if( Session::has('error') )
    <script type="text/javascript">
        toastr.error( '{{ Session::get('error') }}', 'Error Alert', {timeOut: 5000})
        {{ Session::forget('error') }}
    </script>
@endif

