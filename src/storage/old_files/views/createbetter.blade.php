@extends('layouts.master')


@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb pt-1">
        <div class="row">
            <div class="col-12 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Create PO</h4>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->



    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid pt-1 pb-1">

        <div class="row">
            <div class="col-md-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{ route('po.index') }}" class="btn btn-primary btn-sm">Back to PO list</a>
                        </div>
                        Create PO
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
                        <form method="POST" action="{{ route('po.store') }}">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0" ><small>PO Name</small></label>
                                            <div class="input-group input-group-sm">
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="MIA-ZHE011..">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0"><small>Vendor</small></label>
                                            <div class="input-group input-group-sm">
                                                <select id="vendor_id" name="vendor_id" class="form-control input-sm" >
                                                    @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor['id'] }}">{{ $vendor['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0"><small>Courier</small></label>
                                            <div class="input-group input-group-sm">
                                                <select id="courier_id" name="courier_id" class="form-control input-sm" >
                                                    @foreach($couriers as $courier)
                                                        <option value="{{ $courier['id'] }}">{{ $courier['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0" ><small>Tracking Number</small></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control input-sm" id="tracking" name="tracking" placeholder="...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0" ><small>Transaction Type</small></label>
                                            <div class="input-group input-group-sm">
                                                <select id="transaction_type_id" name="transaction_type_id" class="form-control input-sm" >
                                                    <option value="1">Purchase - PO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0" ><small>Bill of Landing</small></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control input-sm" id="bol" name="bol" placeholder="...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0" ><small>Package List</small></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control input-sm" id="package_list" name="package_list" placeholder="...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-po">
                                            <label class="mb-0" ><small>Reference</small></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control input-sm" id="reference" name="reference" min="1" placeholder="...">
                                                <input type="hidden" id="transaction_type_id" name="transaction_type_id" value="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card border-success pb-1 mb-2 mt-2">
                                        <div class="card-header bg-success pb-0 pt-1">
                                            <h4 class="mb-1 mt-1 text-white text-sm-left ">Add Products to PO</h4>
                                        </div>
                                        <div class="card-body pt-1 pb-1">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group-po">
                                                        <label class="mb-0"><small>Products</small></label>
                                                        <div class="input-group input-group-sm">
                                                            <select id="courier_id" name="courier_id" class="form-control input-sm" >
                                                                @foreach($couriers as $courier)
                                                                    <option value="{{ $courier['id'] }}">{{ $courier['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group-po">
                                                        <label class="mb-0" ><small>Qty</small></label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" class="form-control input-sm" id="qty[]" name="qty[]" min="1" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group-po">
                                                        <label class="mb-0" ><small>Batch</small></label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control input-sm" id="batch_number[]" name="batch_number[]" placeholder="XFR4487...">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group-po">
                                                        <label class="mb-0"><small>Action</small></label>
                                                        <div class="input-group input-group-sm">
                                                            <button type="button" class="btn-success" @click="$delete(vars, key)"><i class="fas fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted">Products</h6>
                                        <ul class="list-group-po nobull">
                                            <li class="list-group-po-item py-1 px-3">
                                                <div class="row h-100">
                                                    <div class="col-2 my-auto">
                                                        <span class="badge badge-primary badge-pill"><b>15</b></span>
                                                    </div>
                                                    <div class="col-5 my-auto">
                                                        Product AAA
                                                    </div>
                                                    <div class="col-3 my-auto">
                                                        <small>
                                                            <span class="badge badge-light">N/A</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-2 my-auto">
                                                        <button type="button" class="btn-danger pull-right" @click="$delete(vars, key)"><i class="fa fa-times-circle"></i></button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-po-item py-1 px-3">
                                                <div class="row h-100">
                                                    <div class="col-2 my-auto">
                                                        <span class="badge badge-primary badge-pill"><b>15</b></span>
                                                    </div>
                                                    <div class="col-5 my-auto">
                                                        Product AAA
                                                    </div>
                                                    <div class="col-3 my-auto">
                                                        <small>
                                                            <span class="badge badge-light">N/A</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-2 my-auto">
                                                        <button type="button" class="btn-danger pull-right" @click="$delete(vars, key)"><i class="fa fa-times-circle"></i></button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-po-item py-1 px-3">
                                                <div class="row h-100">
                                                    <div class="col-2 my-auto">
                                                        <span class="badge badge-primary badge-pill"><b>15</b></span>
                                                    </div>
                                                    <div class="col-5 my-auto">
                                                        Product AAA
                                                    </div>
                                                    <div class="col-3 my-auto">
                                                        <small>
                                                            <span class="badge badge-light">N/A</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-2 my-auto">
                                                        <button type="button" class="btn-danger pull-right" @click="$delete(vars, key)"><i class="fa fa-times-circle"></i></button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-po-item py-1 px-3">
                                                <div class="row h-100">
                                                    <div class="col-2 my-auto">
                                                        <span class="badge badge-primary badge-pill"><b>15</b></span>
                                                    </div>
                                                    <div class="col-5 my-auto">
                                                        Product AAA
                                                    </div>
                                                    <div class="col-3 my-auto">
                                                        <small>
                                                            <span class="badge badge-light">N/A</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-2 my-auto">
                                                        <button type="button" class="btn-danger pull-right" @click="$delete(vars, key)"><i class="fa fa-times-circle"></i></button>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>


                            </div>

                            <div class="form-actions mt-2">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-info">Save</button>
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

