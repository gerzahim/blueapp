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
                    <a class="breadcrumb-item" href="{{ route('vendor.index') }}">Suppliers</a>
                    <span class="breadcrumb-item active">Supplier Info</span>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{ route('vendor.index') }}" class="btn btn-primary btn-sm">Back to Suppliers</a>
                        </div>
                        Supplier
                    </div>
                    <div class="card-body">
                        <label><strong>Name</strong></label>
                        <h5>{{ $vendor->name }}</h5>
                        <label><strong>Email </strong></label>
                        <h5>{{ $vendor->email }}</h5>
                        <label><strong>Contact Person</strong></label>
                        <h5>{{ $vendor->contact_person }}</h5>
                        <label><strong>Notes</strong></label>
                        <h5>{{ $vendor->notes }}</h5>
                        <label><strong>Street Address</strong></label>
                        <h5>{{ $vendor->address1 }}</h5>
                        <label><strong>Apartment/Suite/Other</strong></label>
                        <h5>{{ $vendor->address2 }}</h5>
                        <label><strong>City </strong></label>
                        <h5>{{ $vendor->city }}</h5>
                        <label><strong>State </strong></label>
                        <h5>{{ $vendor->state }}</h5>
                        <label><strong>Zip Code </strong></label>
                        <h5>{{ $vendor->postal_code }}</h5>
                        <label><strong>Country </strong></label>
                        <h5>{{ $vendor->country }}</h5>
                        <label><strong>Phone </strong></label>
                        <h5>{{ $vendor->phone }}</h5>
                        <label><strong>Mobile Phone </strong></label>
                        <h5>{{ $vendor->mobile }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
