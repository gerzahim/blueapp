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
                    <a class="breadcrumb-item" href="{{ route('product_dimensions.index') }}">Product Dimensions</a>
                    <span class="breadcrumb-item active">Create Product Dimensions</span>
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
            <div class="col-md-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{ route('product_dimensions.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-undo-alt"></i>&nbsp;Back to Product Dimensions</a>
                        </div>
                        Create Product Dimensions
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

                        <form method="POST" action="{{ route('product_dimensions.store') }}">
                                @csrf
                            <div class="form-group">
                                <label for="name">Dimensions</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="500x500">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;Save</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection


