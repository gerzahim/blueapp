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
                    <a class="breadcrumb-item" href="{{ route('rma.index') }}">RMA</a>
                    <span class="breadcrumb-item active">RMA Info</span>
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
                            <a href="{{ route('rma.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-undo-alt"></i>&nbsp;Back to RMA List</a>
                        </div>
                        RMA
                    </div>
                    <div class="card-body">
                        <label><strong>RMA ID</strong></label>
                        <h5>{{ $rma->name }}</h5>
                        <h6 class="card-title mt-5"><label><strong>RMA Items</strong></label></h6>
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
                                    @foreach($products_rma as $product_rma)
                                        <tr>
                                            <td>{{ $product_rma['product_name'] }}</td>
                                            <td>{{ $product_rma['qty'] }}</td>
                                            <td>{{ $product_rma['batch'] }}</td>
                                            <td>{{ $product_rma['po_name'] }}</td>
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
