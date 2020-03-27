@extends('layouts.master')


@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
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
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{ route('purchases.index') }}" class="btn btn-primary btn-sm">SAMPLE Create PO</a>
                        </div>
                        Create PO
                    </div>
                    <div class="card-body">

                        <!-- START COMPONENT -->
                        <div class="col-12">
                            <form method="POST" @submit="checkForm" action="/purchases">
                                <input type="hidden" name="_token" :value="csrf">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>PO Name</small></label>
                                                <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[error_name ? 'is-invalid' : '']" v-model="name" placeholder="MIA-ZHE011..">
                                                <div v-show="error_name" class="invalid-feedback">Please Indicate unique PO Name !</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0"><small>Vendor</small></label>
                                                <div class="input-group input-group-sm">
                                                    <select id="vendor_id" name="vendor_id" class="form-control form-control-sm" v-bind:class="[error_vendor ? 'is-invalid' : '']" v-model="vendor_selected">
                                                        <option value="0" disabled selected>Select Vendor</option>
                                                    </select>
                                                    <div v-show="error_vendor" class="invalid-feedback">Please Select a Vendor!</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0"><small>Courier</small></label>
                                                <div class="input-group input-group-sm">
                                                    <select id="courier_id" name="courier_id" class="form-control form-control-sm" v-bind:class="[error_courier ? 'is-invalid' : '']" v-model="courier_selected">
                                                        <option value="0" disabled selected>Select Courier</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Tracking Number</small></label>
                                                <input type="text" class="form-control form-control-sm" id="tracking" name="tracking" placeholder="...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Transaction Type</small></label>
                                                <select id="transaction_type_id" name="transaction_type_id" class="form-control form-control-sm" >
                                                    <option value="1">Purchase - PO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Bill of Landing</small></label>
                                                <input type="text" class="form-control form-control-sm" id="bol" name="bol" placeholder="...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Package List</small></label>
                                                <input type="text" class="form-control form-control-sm" id="package_list" name="package_list" placeholder="...">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Reference</small></label>
                                                <input type="text" class="form-control form-control-sm" id="reference" name="reference" min="1" placeholder="...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <div class="card border-success pb-1 mb-2 mt-2">
                                                    <div class="card-header bg-success pb-0 pt-1">
                                                        <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products to PO</h6>
                                                    </div>
                                                    <div class="card-body bg-light pt-1 pb-1">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group-po">
                                                                    <label class="mb-0"><small>Products</small></label>
                                                                    <select id="product_id" name="product_id" class="form-control form-control-sm" v-bind:class="[error_product ? 'is-invalid' : '']" v-model="product_selected">
                                                                        <option value="0" disabled selected>Select Product</option>
                                                                        <option v-for="product in products" v-bind:value="{ id: product.id, name: product.name }" :key="product.id">
                                                                            P510
                                                                        </option>
                                                                    </select>
                                                                    <div v-show="error_product" class="invalid-feedback">Please Select a Product!</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group-po">
                                                                    <label class="mb-0" ><small>Qty</small></label>
                                                                    <input type="number" class="form-control form-control-sm" v-bind:class="[error_qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                                                    <div v-show="error_qty" class="invalid-feedback">Please Indicate Qty!</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group-po">
                                                                    <label class="mb-0" ><small>Batch</small></label>
                                                                    <input type="text" class="form-control form-control-sm text-uppercase" id="batch_number" name="batch_number" v-model="batch_number" placeholder="XFR4487...">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group-po">
                                                                    <label class="mb-0"><small>Add</small></label>
                                                                    <div class="input-group input-group-sm">
                                                                        <button type="button" class="btn-success" @click="insertNewProduct()"><i class="fas fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>List Products added</small></label>
                                                <ul class="list-group list-group-full">


                                                    <li class="list-group-item">
                                                        <div class="row" style="height: 25px;">
                                                            <div class="col-6 align-middle">
                                                                P510
                                                                <span class="badge badge-primary badge-pill"><b>5</b></span>
                                                            </div>
                                                            <div class="col-4 align-middle">
                                                                <h4>
                                                                    <span class="badge badge-secondary">SFSDF234234234</span>
                                                                </h4>
                                                            </div>
                                                            <div class="col-2 align-middle text-right">
                                                                <a href="#" @click="$delete(vars, key)"><i class="fa fa-times-circle" style="color:red"></i></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item"> Cras justo odio <span class="badge badge-info ml-auto">6</span></li>
                                                    <li class="list-group-item"> Dapibus ac facilisis in </li>
                                                    <li class="list-group-item"> Morbi leo risus <span class="badge badge-danger ml-auto">3</span></li>
                                                    <li class="list-group-item active"> Porta ac consectetur ac <span class="badge badge-success ml-auto">10</span></li>
                                                    <li class="list-group-item"> Vestibulum at eros </li>
                                                </ul>
                                            </div>
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
                        <!-- END COMPONENT -->
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection

