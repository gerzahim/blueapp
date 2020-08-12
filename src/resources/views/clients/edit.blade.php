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
                    <span class="breadcrumb-item active">Edit Customer</span>
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
                            <a href="{{ route('client.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-undo-alt"></i>&nbsp;Back to Customers</a>
                        </div>
                        <strong>Update Customer</strong>
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

                        <form action="{{ route('client.update',$client->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $client->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $client->email }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact Person</label>
                                            <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ $client->contact_person }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="2">{{ $client->notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Street Address</label>
                                            <input type="text" class="form-control" id="address1" name="address1" value="{{ $client->address1 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Apartment/Suite/Other</label>
                                            <input type="text" class="form-control" id="address2" name="address2" value="{{ $client->address2 }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="{{ $client->city }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            <input type="text" class="form-control" id="state" name="state" value="{{ $client->state }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ $client->postal_code }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <input type="text" class="form-control" id="country" name="country" value="{{ $client->country }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ $client->phone }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile Phone</label>
                                            <input type="tel" class="form-control" id="mobile" name="mobile" value="{{ $client->mobile }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>EIN Number</label>
                                            <input type="text" class="form-control" id="ein_number" name="ein_number" value="{{ $client->ein_number }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Resale Tax</label>
                                            <input type="text" class="form-control" id="resale_tax" name="resale_tax" value="{{ $client->resale_tax }}">
                                        </div>
                                    </div>
                                </div>
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
