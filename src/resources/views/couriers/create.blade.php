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
                    <a class="breadcrumb-item" href="{{ route('courier.index') }}">Couriers</a>
                    <span class="breadcrumb-item active">Create Courier</span>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{ route('courier.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-undo-alt"></i>&nbsp;Back to Couriers</a>
                        </div>
                        Create Courier
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

                        <form method="POST" action="{{ route('courier.store') }}">
                                @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
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

