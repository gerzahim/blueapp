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
                    <a class="breadcrumb-item" href="{{ route('client.index') }}">Customers</a>
                    <span class="breadcrumb-item active">Customer Info</span>
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
                            <a href="{{ route('client.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-undo-alt"></i>&nbsp;Back to Customers</a>
                        </div>
                        Customer
                    </div>
                    <div class="card-body">
                        <label><strong>Name</strong></label>
                        <h5>{{ $client->name }}</h5>
                        <label><strong>Email </strong></label>
                        <h5>{{ $client->email }}</h5>
                        <label><strong>Contact Person</strong></label>
                        <h5>{{ $client->contact_person }}</h5>
                        <label><strong>Notes</strong></label>
                        <h5>{{ $client->notes }}</h5>
                        <label><strong>Street Address</strong></label>
                        <h5>{{ $client->address1 }}</h5>
                        <label><strong>Apartment/Suite/Other</strong></label>
                        <h5>{{ $client->address2 }}</h5>
                        <label><strong>City </strong></label>
                        <h5>{{ $client->city }}</h5>
                        <label><strong>State </strong></label>
                        <h5>{{ $client->state }}</h5>
                        <label><strong>Zip Code </strong></label>
                        <h5>{{ $client->postal_code }}</h5>
                        <label><strong>Country </strong></label>
                        <h5>{{ $client->country }}</h5>
                        <label><strong>Phone </strong></label>
                        <h5>{{ $client->phone }}</h5>
                        <label><strong>Mobile Phone </strong></label>
                        <h5>{{ $client->mobile }}</h5>
                        <label><strong>EIN Number </strong></label>
                        <h5>{{ $client->ein_number }}</h5>
                        <label><strong>Resale Tax</strong></label>
                        <h5>{{ $client->resale_tax }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
