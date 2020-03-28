@extends('layouts.master')

@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Inventory - Stock</h4>
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
                    <div class="card-body">

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-12">
                                <h4 class="font-light">Stock List</h4>
                            </div>
                        </div>

                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Product</th>
                                    <th>Batch</th>
                                    <th>PO</th>
                                    <th>Purchased</th>
                                    <th>Sold</th>
                                    <th>QOH</th>
                                    <!-- th>On Hold</th -->
                                    <th>Available</th>
                                    <th>RMA</th>
                                    <th>Refurbished</th>
                                    <!-- th>Lended</th -->
                                </tr>
                            </thead>
                            @foreach ($stocks as $key => $stock)
                                <tbody>
                                    <tr class="text-center">
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            <a href="{{ route('product.show',$stock->product_id) }}">
                                                {{ $products_name[$stock->product_id] }}
                                            </a>
                                        </td>
                                        <td>{{ $products_batch[$stock->purchases_item_id] }}</td>
                                        <td>
                                            <a href="{{ route('purchases.show',$stock->purchases_id) }}">
                                                {{ $purchases_name[$stock->purchases_id] }}
                                            </a>
                                        </td>
                                        <td>{{ $stock->purchased }}</td>
                                        <td>{{ $stock->sold }}</td>
                                        <td>{{ $stock->qoh }}</td>
                                        <!-- td>{{ $stock->on_hold }}</td -->
                                        <td>{{ $stock->available }}</td>
                                        <td>{{ $stock->rma }}</td>
                                        <td>{{ $stock->refurbished }}</td>
                                        <!-- td>{{ $stock->lended }}</td -->
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                        {!! $stocks->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
