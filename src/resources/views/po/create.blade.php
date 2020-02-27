@extends('layouts.master')


@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Applications</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item text-muted active" aria-current="page">PO</li>
                            <li class="breadcrumb-item text-muted">Create</li>
                        </ol>
                    </nav>
                </div>
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
                                        <div class="form-group">
                                            <label>PO Name</label>
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="MIA-ZHE011..">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Vendor</label>
                                            <select id="vendor_id" name="vendor_id" class="form-control" >
                                                @foreach($vendors as $vendor)
                                                <option value="{{ $vendor['id'] }}">{{ $vendor['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Courier</label>
                                            <select id="courier_id" name="courier_id" class="form-control" >
                                                @foreach($couriers as $courier)
                                                <option value="{{ $courier['id'] }}">{{ $courier['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tracking Number</label>
                                            <input type="text" class="form-control" id="tracking" name="tracking" placeholder="...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Transaction Type</label>
                                            <select id="transaction_type_id" name="transaction_type_id" class="form-control" >
                                                <option value="1">Purchase - PO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bill of Landing</label>
                                            <input type="text" class="form-control" id="bol" name="bol" placeholder="...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Package List</label>
                                            <input type="text" class="form-control" id="package_list" name="package_list" placeholder="...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Reference</label>
                                            <input type="text" class="form-control" id="reference" name="reference" min="1" placeholder="...">
                                            <input type="hidden" id="transaction_type_id" name="transaction_type_id" value="1">
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <po-component></po-component>



                            </div>

                            <div class="form-actions">
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

