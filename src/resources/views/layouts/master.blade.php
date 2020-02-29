<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/adminmart/images/favicon.png') }}">
    @if(!isset($TITLE)) <?php $TITLE = "Inventory Huahai";?> @endif
    <title>.: {{$TITLE}} :.</title>
    <!-- This page css -->
    <!-- Custom CSS -->
    <link href="{{ asset('/adminmart/css/style.css') }}" rel="stylesheet">

    <!-- Laravel-MIX CSS
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Main wrapper Vue app -->
    <!-- ============================================================== -->
    <div id="app">
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">


            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            @include('layouts.header')




            <!-- ============================================================== -->
            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            @include('layouts.menu')




            <!-- ============================================================== -->
            <!-- Page wrapper SECTION -->
            <!-- ============================================================== -->
            <div class="page-wrapper">

                @yield('content')

                <!-- ============================================================== -->
                <!-- footer -->
                <!-- ============================================================== -->
                <footer class="footer text-center mb-0 mt-0 pt-0 pb-0">
                    All Rights Reserved by Huahai. Designed and Developed by <a target="blank" href="http://gerzahim.com/">Gerza</a>.
                </footer>
                <!-- ============================================================== -->
                <!-- End footer -->
                <!-- ============================================================== -->




            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper SECTION  -->
            <!-- ============================================================== -->




        </div>
        <!-- ============================================================== -->
        <!-- End Main Wrapper -->
        <!-- ============================================================== -->

    </div>
    <!-- ============================================================== -->
    <!-- End Vue Container app -->
    <!-- ============================================================== -->



    <!-- ============================================================== -->
    <!-- All Jquery Scripts-->
    <!-- ============================================================== -->

    <!-- Laravel-MIX JS -->
    <script src="{{asset('js/app.js')}}"></script>


    <!-- Adminmart JS -->
    <!--
    <script src="{{asset('/adminmart/js/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('/adminmart/js/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('/adminmart/js/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    -->

    <!-- apps -->

    <script src="{{asset('/adminmart/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('/adminmart/js/feather.min.js')}}"></script>
    <script src="{{asset('/adminmart/js/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{asset('/adminmart/js/sidebarmenu.js')}}"></script>
    <!-- -->

    <!--Custom JavaScript -->

    <script src="{{asset('/adminmart/js/custom.min.js')}}"></script>



</body>
</html>
