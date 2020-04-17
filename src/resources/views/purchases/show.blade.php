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
                    <a class="breadcrumb-item" href="{{ route('purchases.index') }}">PO list</a>
                    <span class="breadcrumb-item active">PO Info</span>
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
                            <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm">Back to PO</a>
                        </div>
                        PO
                    </div>
                    <div class="card-body">
                        <label><strong>Purchase ID</strong></label>
                        <h5>{{ $purchase->id }}</h5>
                        <label><strong>Purchase Name</strong></label>
                        <h5>{{ $purchase->name }}</h5>
                        <h6 class="card-title mt-5"><label><strong>Purchase Items</strong></label></h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Batch Number</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($products_pos as $product_po)
                                        <tr>
                                            <td>{{ $product_po['product_name'] }}</td>
                                            <td>{{ $product_po['qty'] }}</td>
                                            <td>{{ $product_po['batch_number'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
