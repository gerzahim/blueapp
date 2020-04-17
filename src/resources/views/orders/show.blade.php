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
                    <a class="breadcrumb-item" href="{{ route('order.index') }}">Orders</a>
                    <span class="breadcrumb-item active">Order Info</span>
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
                            <a href="{{ route('order.index') }}" class="btn btn-primary btn-sm">Back to Orders</a>
                        </div>
                        Order
                    </div>
                    <div class="card-body">
                        <label><strong>Order ID</strong></label>
                        <h5>{{ $order->name }}</h5>
                        <h6 class="card-title mt-5"><label><strong>Order Items</strong></label></h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Batch Number</th>
                                    <th scope="col">PO</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($products_order as $product_order)
                                        <tr>
                                            <td>{{ $product_order['product_name'] }}</td>
                                            <td>{{ $product_order['qty'] }}</td>
                                            <td>{{ $product_order['batch'] }}</td>
                                            <td>{{ $product_order['po_name'] }}</td>
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
