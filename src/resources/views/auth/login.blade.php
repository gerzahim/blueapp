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

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">


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
    <!-- Login box.scss -->
    <!-- ============================================================== -->
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
         style="background:url({{ asset('/adminmart/images/big/auth-bg.jpg') }}) no-repeat center center;">
        <div class="auth-box row">
            <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url({{ asset('/adminmart/images/big/3.jpg') }});">
            </div>
            <div class="col-lg-5 col-md-7 bg-white">
                <div class="p-3">
                    <div class="text-center">
                        <img src="{{ asset('/adminmart/images/big/icon.png') }}" alt="wrapkit">
                    </div>
                    <h2 class="mt-3 text-center">Sign In</h2>
                    <p class="text-center">Enter your email address and password to access admin panel.</p>
                    <form class="mt-4" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark" for="uname">Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-block btn-dark">Sign In</button>
                            </div>
                            <!-- Sing Up Link
                            <div class="col-lg-12 text-center mt-5">
                                Don't have an account? <a href="#" class="text-danger">Sign Up</a>
                            </div>
                            -->
                            <div class="col-lg-12 text-center mt-5">
                                Developed by <a class="text-danger" target="blank" href="http://gerzahim.com/">Gerza</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center mb-0 mt-0 pt-0 pb-0">
        All Rights Reserved by Huahai. Designed and Developed by <a class="text-danger" target="blank" href="http://gerzahim.com/">Gerza</a>.
    </footer>
    <!-- ============================================================== -->
    <!-- Login box.scss -->
    <!-- ============================================================== -->



    <!-- ============================================================== -->
    <!-- All Jquery Scripts-->
    <!-- ============================================================== -->

    <!-- Laravel-MIX JS -->
    <script src="{{asset('js/app.js')}}"></script>


    <!-- Toastr JS -->
    <script src="{{asset('js/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('js/toastr/helper_toastr.js')}}"></script>
    @include('layouts.alert')

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
