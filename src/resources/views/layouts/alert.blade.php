@if( Session::has('success') )
    <script type="text/javascript">
        toastr.success( '{{ Session::get('success') }}', 'Success Alert', {timeOut: 5000})
        {{ Session::forget('success') }}
    </script>
@endif


@if( Session::has('info') )
    <script type="text/javascript">
        toastr.info( '{{ Session::get('info') }}', 'Info Alert', {timeOut: 5000})
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

