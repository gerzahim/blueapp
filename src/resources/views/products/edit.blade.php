@extends('layouts.master')


@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Product</h4>
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
                            <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm">Back to Products</a>
                        </div>
                        Edit Product
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

                        <form method="POST" action="{{ route('product.update',$product->id) }}">
                            @csrf @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="P10-MCXXa" value="{{ $product->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Product Description</label>
                                            <input type="text" class="form-control" id="description" name="description" placeholder="Unit Cabinet P10, PO MIA-ZH..." value="{{ $product->description }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dimensions</label>
                                            <select id="dimensions_id" name="dimensions_id" class="selectpicker form-control" >
                                                @foreach($dimensions as $dimension)
                                                    <!-- Mark Selected if it's the same one -->
                                                    @if($dimension['id'] == $product->dimensions_id)
                                                        <option selected="selected" value="{{ $dimension['id'] }}">{{ $dimension['name'] }}</option>
                                                    @else
                                                         <option value="{{ $dimension['id'] }}">{{ $dimension['name'] }}</option>
                                                    @endif

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select id="category_id" name="category_id" class="selectpicker form-control">
                                                @foreach($categories as $category)
                                                    <!-- Mark Selected if it's the same one -->
                                                    @if($category['id'] == $product->category_id)
                                                        <option selected="selected" value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                                    @else
                                                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    <button type="reset" class="btn btn-dark">Reset</button>
                                </div>
                            </div>
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
