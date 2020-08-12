@extends('layouts.master')


@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <nav class="breadcrumb">
                    <a class="breadcrumb-item" href="{{ url('/') }}">Home</a>
                    <a class="breadcrumb-item" href="{{ route('refurbishes.index') }}">Refurbish</a>
                    <span class="breadcrumb-item active">Edit Refurbish</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->



    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-10 m-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{ route('refurbishes.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-undo-alt"></i>&nbsp;Back Refurbishments list</a>
                        </div>
                        Edit Refurbishment
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <p>
                                <strong>Input Error!</strong>
                            </p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <refurbish-component
                            v-bind:props_action="'{{ $form_action }}'"
                            v-bind:props_refurbish_edit="'{{ $refurbish }}'"
                            v-bind:props_products_edit="'{{ $products_refurbishes }}'"
                        ></refurbish-component>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection

