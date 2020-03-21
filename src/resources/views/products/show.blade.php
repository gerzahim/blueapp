@extends('layouts.master')

@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Product Info</h4>
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
                            <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm">Back to Products</a>
                        </div>
                        Product
                    </div>
                    <div class="card-body">
                        <label><strong>Name</strong></label>
                        <h5>{{ $product->name }}</h5>
                        <label><strong>Description </strong></label>
                        <h5>{{ $product->description }}</h5>
                        <label><strong>Product Dimensions</strong></label>
                        @foreach($dimensions as $dimension)
                            @if($dimension['id'] == $product->dimensions_id)
                                <h5>{{ $dimension['name'] }}</h5>
                            @endif
                        @endforeach
                        <label><strong>Category</strong></label>
                        @foreach($categories as $category)
                            @if($category['id'] == $product->category_id)
                                <h5>{{ $category['name'] }}</h5>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
