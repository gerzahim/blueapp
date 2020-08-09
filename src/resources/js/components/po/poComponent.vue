<template>
    <div class="col-12">
        <form method="POST" @submit="processForm" v-bind:action="computedAction">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="_method" value="PUT" v-if="action_edit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>PO Name</small></label>
                            <input type="hidden" name="id" v-model="form_id">
                            <input type="hidden" name="transaction_type_id" :value="form_transaction_type_id">
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[ errors.name ? 'is-invalid' : '']" v-model="form_name" placeholder="MIA-ZHE011.." @change="validateName">
                            <div v-show="errors.name" class="invalid-feedback">Please Indicate unique PO Name !</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Date </small></label>
                            <b-form-datepicker id="example-datepicker" v-bind:class="[errors.date ? 'is-invalid' : '']" v-model="form_date" size="sm" class="form-control form-control-sm mb-2"></b-form-datepicker>
                            <input type="hidden" name="date" id="date" :value="form_date">
                            <div v-show="errors.date" class="invalid-feedback">Please Pick a Date !</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <div class="spinner-border text-success" role="status" v-show="loading_vendors">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div v-show="!loading_vendors">
                                <label class="mb-0"><small>Supplier</small></label>
                                <select id="vendor_id" name="vendor_id" class="form-control form-control-sm" v-bind:class="[ errors.vendor ? 'is-invalid' : '']" v-model="form_vendor_id" @change="validateVendor">
                                    <option value="0" disabled selected>Select Supplier</option>
                                    <option v-for="vendor in vendors" :value="vendor.id" :key="vendor.id">
                                        {{ vendor.name }}
                                    </option>
                                </select>
                                <div v-show="errors.vendor" class="invalid-feedback">Please Select a Supplier!</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Bill of Landing</small></label>
                            <input type="text" class="form-control form-control-sm" id="bol" name="bol" placeholder="..." v-model="form_bol">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <div class="spinner-border text-success" role="status" v-show="loading_couriers">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div v-show="!loading_couriers">
                                <label class="mb-0"><small>Courier</small></label>
                                <select id="courier_id" name="courier_id" class="form-control form-control-sm" v-model="form_courier_id">
                                    <option value="0" disabled selected>Select Courier</option>
                                    <option v-for="courier in couriers" :value="courier.id" :key="courier.id">
                                        {{ courier.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Tracking Number</small></label>
                            <input type="text" class="form-control form-control-sm" id="tracking" name="tracking" placeholder="..." v-model="form_tracking">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Package List</small></label>
                            <input type="text" class="form-control form-control-sm" id="package_list" name="package_list" placeholder="..." v-model="form_package_list">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Reference</small></label>
                            <input type="text" class="form-control form-control-sm" id="reference" name="reference" min="1" placeholder="..." v-model="form_reference">
                        </div>
                    </div>
                </div>
                <div class="p-2 border shadow-sm rounded">
                    <input type="hidden" name="vars" :value="JSON.stringify(vars)">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th width="50%" scope="col">Product</th>
                                        <th width="30%" scope="col">Batch Number</th>
                                        <th width="10%" scope="col">Qty</th>                                        
                                        <th width="10%" scope="col">Handle</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(po_product, k) in vars" :key="k">

                                        <td width="50%"> 
                                            <select id="product_id" name="product_id" class="form-control form-control-sm" v-bind:class="[errors_adder.product ? 'is-invalid' : '']" v-model="po_product.product_id">
                                                <option value="0" selected disabled>Select Product</option>
                                                <option v-for="product in products" v-bind:value="product.id" :key="product.id">
                                                    {{ product.name }}
                                                </option>
                                            </select>
                                            <!-- <div v-show="errors_adder.product" class="invalid-feedback">Please Select a Product!</div> -->
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm text-uppercase" id="batch_number" name="batch_number" v-model="po_product.batch_number" placeholder="XFR4487...">
                                        </td>
                                        <th scope="row">
                                            <input type="number" class="form-control form-control-sm" v-bind:class="[errors_adder.qty ? 'is-invalid' : '']" v-model="po_product.qty" min="1">
                                            <!-- <div v-show="errors_adder.qty" class="invalid-feedback">Please Indicate Qty!</div> -->
                                        </th>
                                        <td align="center"> 
                                            <!--
                                            <i class="far fa-trash-alt" @click="deleteRow(k, po_product)"></i>
                                            <a href="#" @click="$delete(vars, key)"><h3><i class="fa fa-times-circle" style="color:red"></i></h3></a>
                                            -->
                                            <a href="#" @click="$delete(vars, k)"><h3><i class="far fa-trash-alt" style="color:red"></i></h3></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4">
                                            <button type='button' class="btn btn-success btn-sm" @click="addNewRow">
                                                <i class="fas fa-plus-circle"></i>&nbsp; Add a Line
                                            </button>
                                            <!-- Create Product modal -->
                                            <button type='button' 
                                                    class="btn btn-secondary btn-sm" 
                                                    data-toggle="modal"
                                                    data-target="#create-modal">
                                                <i class="fas fa-tags"></i>&nbsp; Create New Product
                                            </button>
                                        </th>
                                    </tr>
                                    <tr v-show="errors.vars">
                                        <th colspan="4">
                                            <div class="alert alert-danger">
                                                <p>
                                                    <strong><li>Please Add a Product to PO</li></strong>
                                                </p>
                                            </div>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- 
                <div class="p-4 border shadow-sm rounded">
                    <h4 class="card-title">List Products Selected</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group-po">
                            <input type="hidden" name="vars" :value="JSON.stringify(vars)">
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Batch Number</th>
                                        <th scope="col">Handle</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(variable, key) in vars" :key="key">
                                        <th scope="row"><span class="badge badge-primary badge-pill"><b>{{variable.qty}}</b></span></th>
                                        <td>{{variable.product_name}}</td>
                                        <td>{{variable.batch_number}}</td>
                                        <td><a href="#" @click="$delete(vars, key)"><h3><i class="fa fa-times-circle" style="color:red"></i></h3></a></td>
                                    </tr>
                                    <tr v-show="errors.vars">
                                        <th colspan="4">
                                            <div class="alert alert-danger">
                                                <p>
                                                    <strong><li>Please Add a Product to PO</li></strong>
                                                </p>
                                            </div>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                -->

                <!--
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group-po">
                            <div class="spinner-border text-success" role="status" v-show="loading_products">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="card border-success pb-1 mb-2 mt-2" v-show="!loading_products">
                                <div class="card-header bg-success pb-0 pt-1">
                                    <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products to PO</h6>
                                </div>
                                <div class="card-body bg-light pt-1 pb-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group-po">
                                                <label class="mb-0"><small>Products</small></label>
                                                <select id="product_id" name="product_id" class="form-control form-control-sm" v-bind:class="[errors_adder.product ? 'is-invalid' : '']" v-model="product_selected">
                                                    <option value="0" disabled selected>Select Product</option>
                                                    <option v-for="product in products" v-bind:value="{ id: product.id, name: product.name }" :key="product.id">
                                                        {{ product.name }}
                                                    </option>
                                                </select>
                                                <div v-show="errors_adder.product" class="invalid-feedback">Please Select a Product!</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Qty</small></label>
                                                <input type="number" class="form-control form-control-sm" v-bind:class="[errors_adder.qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                                <div v-show="errors_adder.qty" class="invalid-feedback">Please Indicate Qty!</div>
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
                            <label class="mb-0" ><small>List Products Selected</small></label>
                            <input type="hidden" name="vars" :value="JSON.stringify(vars)">
                            <ul class="list-group list-group-full">
                                <li v-for="(variable, key) in vars" :key="key" class="list-group-item">
                                    <div class="row pr-1">
                                        <div class="col-7 align-middle pl-2 px-1">
                                            {{variable.product_name}}
                                            <span class="badge badge-primary badge-pill"><b>{{variable.qty}}</b></span>
                                        </div>
                                        <div class="col-4 align-middle px-1">
                                            <h4>
                                                <span class="badge badge-secondary px-1">{{variable.batch_number}}</span>
                                            </h4>
                                        </div>
                                        <div class="col-1 align-middle text-center px-1">
                                            <a href="#" @click="$delete(vars, key)"><h3><i class="fa fa-times-circle" style="color:red"></i></h3></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <ul v-show="errors.vars">
                                <div class="alert alert-danger">
                                    <p>
                                        <strong><li>Please Add a Product to PO !</li></strong>
                                    </p>
                                </div>
                            </ul>
                        </div>
                    </div>
                    
                </div> 
                -->

                <!-- END ROW -->
            </div>

            <div class="form-actions mt-2">
                <div class="text-right">
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>



        <!-- Signup modal content -->
        <div id="create-modal" class="modal fade" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-body mt-2">
                    <ul class="nav nav-tabs nav-justified nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#home" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Create a Product</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Create Category</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Create Product Dimensions</span>
                            </a>
                        </li>
                    </ul>


                    <div class="tab-content">
                        <br><br>
                        <div class="tab-pane show active" id="home">
                                <form method="POST" @submit="checkFormProduct" action="/product">
                                <input type="hidden" name="_token" :value="csrf">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="P10-MCXXa">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Product Description</label>
                                                <input type="text" class="form-control" id="description" name="description" placeholder="Unit Cabinet P10, PO MIA-ZH...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Dimensions</label>
                                                <select id="dimensions_id" name="dimensions_id" class="form-control form-control-sm">
                                                    <option value="0" selected disabled>Select Product Dimension</option>
                                                    <option v-for="dimension in dimensions" v-bind:value="dimension.id" :key="dimension.id">
                                                        {{ dimension.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select id="category_id" name="category_id" class="form-control form-control-sm">
                                                    <option value="0" selected disabled>Select Category</option>
                                                    <option v-for="category in categories" v-bind:value="category.id" :key="category.id">
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-light"
                                                    data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-info"><i class="far fa-save"></i>&nbsp;Save</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane" id="profile">
                            <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim
                                justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis
                                eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum
                                semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor
                                eu, consequat vitae, eleifend ac, enim.</p>
                        </div>
                        <div class="tab-pane" id="settings">
                            <p>Food truck quinoa dolor sit amet, consectetuer adipiscing elit. Aenean
                                commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et
                                magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis,
                                ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa
                                quis enim.</p>
                        </div>
                    </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->




    </div>

    
</template>

<script>
    export default {
        props: ["props_action", "props_products_edit", "props_purchase_edit"],
        data: function () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                action_edit:false,
                loading_products:false,
                loading_vendors:false,
                loading_couriers:false,
                loading_dimensions:false,
                loading_categories:false,

                // var form
                form_id: '',
                form_name: null,
                form_date: '',
                form_vendor_id: 0,
                form_courier_id: 0,
                form_transaction_type_id: 1,
                form_tracking: '',
                form_bol: '',
                form_package_list: '',
                form_reference: '',
                // var adder
                product_selected: 0,
                qty: 1,
                batch_number: '',
                // var errors
                errors: {
                    name:false,
                    date:false,
                    vendor:false,
                    vars:false,
                },
                errors_adder: {
                    qty:false,
                    product:false,
                },

                products:[],
                vendors:[],
                couriers:[],
                dimensions:[],
                categories:[],
                vars:[],
                purchase:[],
            }
        },
        watch: {
            form_date(){
                this.validateDate();
            }
        },
        computed: {
            computedAction: function() {
                if(this.action_edit){
                    return `/purchases/${this.form_id}`
                }
                // form action_create
                return `/purchases`
            },
        },
        methods: {
            processForm:function(e) {
                this.validateName()
                this.validateDate()
                this.validateVendor()
                this.areProductsSelected()

                if (this.errors.name || this.errors.date || this.errors.vendor || this.errors.vars) { //Put here the condition you want
                    e.preventDefault(); // Here triggering stop submit action
                    // Here you can put code relevant when event stops;
                    toastr.error('Form is Not Good!', 'Error Alert', {timeOut: 5000})
                    return;
                }
            },
            checkFormProduct:function(e) {
                /*
                this.checkErrors()
                if (!this.errors.length) {
                    return true;
                    alert('Form is Not Good')
                }
                e.preventDefault();
                */
            },
            //Methods for Form
            validateName(){
                this.errors.name = false
                if (!this.form_name) {
                    this.errors.name = true
                }
            },
            validateDate(){
                this.errors.date = false
                if (this.form_date === '') {
                    this.errors.date = true
                }
            },
            validateVendor(){
                this.errors.vendor = false
                if (this.form_vendor_id === 0) {
                    this.errors.vendor = true
                }
            },
            areProductsSelected(){
                this.errors.vars = false
                if (!this.vars.length) {
                    this.errors.vars = true
                }
            },
            //Methods for Adder
            validateQty() {
                this.errors_adder.qty = false;
                if(this.qty < 1) {
                    this.errors_adder.qty = true;
                }
            },
            validateProductSelected() {
                this.errors_adder.product = false;
                if(typeof(this.product_selected.id) == 'undefined' || this.product_selected.id === null || this.product_selected.id === 0) {
                    this.errors_adder.product = true;
                }
            },

            areTheseValuesInArray(array, id, batch) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].product_id === id && array[i].batch_number === batch)
                        return [array[i].qty, i];
                }
                return 0;
            },
            areErrorsAdder(){
                this.validateQty()
                this.validateProductSelected()
                if (this.errors_adder.qty || this.errors_adder.product) {
                    return true;
                }
                return false
            },
            resetAddProducts() {
                this.product_selected=0,
                this.qty= 1,
                this.batch_number= ''
            },
            insertNewProduct() {
                if( !this.areErrorsAdder() ) {

                    let variables = this.vars
                    let getInfoArray = this.areTheseValuesInArray(this.vars, this.product_selected.id, this.batch_number)

                    let previousQty = getInfoArray[0]
                    let product_array_key = getInfoArray[1]
                    if (previousQty > 0){
                        variables.splice(product_array_key,1)
                        this.qty = parseInt(this.qty)+parseInt(previousQty)
                    }
                    variables.push({'product_id': this.product_selected.id,'product_name': this.product_selected.name, 'qty': this.qty, 'batch_number': this.batch_number.toUpperCase()})
                }
                this.areProductsSelected()
                this.resetAddProducts()
            },
            
            addNewRow() {
                let variables = this.vars
                variables.push({'product_id': this.product_selected,'product_name': '', 'qty': this.qty, 'batch_number': this.batch_number.toUpperCase()})
            },

            //Methods for Ajax
            fetchProducts() {
                this.loading_products = true
                axios.get('/get_products')
                    .then(response => {
                        this.loading_products = false
                        this.products = response.data.products
                    })
            },
            fetchVendors() {
                this.loading_vendors = true
                axios.get('/get_vendors')
                     .then(response => {
                        this.vendors = response.data.vendors
                        this.loading_vendors = false
                    })
            },
            fetchCouriers() {
                this.loading_couriers = true
                axios.get('/get_couriers')
                    .then(response => {
                        this.couriers = response.data.couriers
                        this.loading_couriers = false
                    })
            },
            fetchProductsDimensions() {
                this.loading_dimensions = true
                axios.get('/get_dimensions')
                    .then(response => {
                        this.dimensions = response.data.dimensions
                        this.loading_dimensions = false
                    })
            },
            fetchCategories() {
                this.loading_categories = true
                axios.get('/get_categories')
                    .then(response => {
                        this.categories = response.data.categories
                        this.loading_categories = false
                    })
            },
        },
        created() {
            this.fetchProducts()
            this.fetchVendors()
            this.fetchCouriers()
            this.fetchProductsDimensions()
            this.fetchCategories()
            this.addNewRow()
            //this.vendors = this.props_vendors
            //this.products = this.props_products

            if (this.props_action){
                this.action_edit = true
            }

            if(this.props_products_edit){
                this.vars = JSON.parse(this.props_products_edit);
            }

            if(this.props_purchase_edit){

                this.purchase = JSON.parse(this.props_purchase_edit);
                this.form_id = this.purchase.id
                this.form_name = this.purchase.name
                this.form_date = this.purchase.date
                this.form_vendor_id = this.purchase.contact_id
                this.form_courier_id = this.purchase.courier_id
                this.form_tracking = this.purchase.tracking
                this.form_bol = this.purchase.bol
                this.form_package_list = this.purchase.package_list
                this.form_reference = this.purchase.reference
            }
        }
    }
</script>

