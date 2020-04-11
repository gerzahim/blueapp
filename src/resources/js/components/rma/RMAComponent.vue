<template>
    <div class="col-12">
        <form method="POST" @submit="processForm" v-bind:action="computedAction">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="_method" value="PUT" v-if="action_edit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <input type="hidden" name="id" v-model="form_id">
                            <input type="hidden" name="transaction_type_id" :value="form_transaction_type_id">
                            <label class="mb-0" ><small>RMA Number</small></label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[ errors.name ? 'is-invalid' : '']" v-model="form_name" readonly>
                            <div v-show="errors.name" class="invalid-feedback">Please Indicate RMA Number !</div>
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
                <div class="row"> <!-- CONTACT Type Supplier or CUSTOMER -->
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Defective Product From:</small></label>
                            <select id="contact_type_id" name="contact_type_id" class="form-control form-control-sm" v-model="form_contact_type_id" @change="dispatchContactType">
                                <option value="0" selected>Customer</option>
                                <option value="1" >Supplier</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po" v-show="form_contact_type_id < 1">
                            <div class="spinner-border text-success" role="status" v-show="loading_customers">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div v-show="!loading_customers">
                                <label class="mb-0"><small>Customer</small></label>
                                <select id="client_id" name="client_id" class="form-control form-control-sm" v-bind:class="[ errors.client ? 'is-invalid' : '']" v-model="form_client_id" @change="dispatchActionCustomer">
                                    <option value="0" disabled selected>Select Customer</option>
                                    <option v-for="client in clients" :value="client.id" :key="client.id">
                                        {{ client.name }}
                                    </option>
                                </select>
                                <div v-show="errors.client" class="invalid-feedback">Please Select a Customer!</div>
                            </div>
                        </div>
                        <div class="form-group-po" v-show="form_contact_type_id > 0">
                            <div class="spinner-border text-success" role="status" v-show="loading_vendors">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div v-show="!loading_vendors">
                                <label class="mb-0"><small>Supplier</small></label>
                                <select id="vendor_id" name="vendor_id" class="form-control form-control-sm" v-bind:class="[ errors.vendor ? 'is-invalid' : '']" v-model="form_vendor_id" @change="dispatchActionVendor">
                                    <option value="0" disabled selected>Select Supplier</option>
                                    <option v-for="vendor in vendors" :value="vendor.id" :key="vendor.id">
                                        {{ vendor.name }}
                                    </option>
                                </select>
                                <div v-show="errors.vendor" class="invalid-feedback">Please Select a Supplier!</div>
                            </div>
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
                    <div class="col-md-12">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Reference</small></label>
                            <input type="text" class="form-control form-control-sm" id="reference" name="reference" min="1" placeholder="..." v-model="form_reference">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header bg-warning py-0" v-show="(add_products_initial)">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="col-10">
                                    <h6 class="mb-1 mt-1 text-white text-sm-left">Select Customer or Supplier!</h6>
                                </div>
                            </div>
                        </div>
                        <div class="spinner-border text-success" role="status" v-show="(loading_customers || loading_vendors)">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="form-group-po" v-show="(!add_products_initial && !loading_customers && loaded_customer)">
                            <div class="card border-success pb-1 mb-2 mt-2">
                                <div class="card-header bg-success pb-0 pt-1">
                                    <h6 class="mb-1 mt-1 text-white text-sm-left">Select Product Returned From Customer Order</h6>
                                </div>
                                <div class="card-body bg-light pt-1 pb-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group-po">
                                                <label class="typo__label mb-0"><small>List Products</small></label>
                                                <multiselect :options="products" placeholder="Select Product" label="text" track-by="text" v-model="product_selected" @select="dispatchAction">
                                                </multiselect>
                                                <div v-show="errors_adder.product" class="invalid-feedback">Please Select a Product!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Qty</small></label>
                                                <input type="number" class="form-control form-control-sm" v-bind:class="[errors_adder.qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                                <div v-show="errors_adder.qty" class="invalid-feedback">Please Indicate Qty!</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
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
                        <div class="form-group-po" v-show="(!add_products_initial && !loading_vendors && loaded_vendor)">
                            <div class="card border-success pb-1 mb-2 mt-2">
                                <div class="card-header bg-success pb-0 pt-1">
                                    <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products Defective From Supplier PO</h6>
                                </div>
                                <div class="card-body bg-light pt-1 pb-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group-po">
                                                <label class="typo__label mb-0"><small>List Products</small></label>
                                                <multiselect :options="products" placeholder="Select Product" label="text" track-by="text" v-model="product_selected" @select="dispatchAction">
                                                </multiselect>
                                                <div v-show="errors_adder.product" class="invalid-feedback">Please Select a Product!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Qty</small></label>
                                                <input type="number" class="form-control form-control-sm" v-bind:class="[errors_adder.qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                                <div v-show="errors_adder.qty" class="invalid-feedback">Please Indicate Qty!</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
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
                            <input type="hidden" name="vars" :value="JSON.stringify(vars)">
                            <ul class="list-group list-group-full">
                                <li v-for="(variable, key) in vars" :key="key" class="list-group-item">
                                    <div class="row" style="height: 50px;">
                                        <div class="col-10 align-middle">
                                            <div class="row">
                                                <div class="col-12">
                                                    {{variable.product_name}}
                                                    <span class="badge badge-primary badge-pill"><b>{{variable.qty}}</b></span>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <span class="badge badge-success">{{variable.po_name}}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <span class="badge badge-secondary">{{variable.batch}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 align-middle text-right mt-1">
                                            <!-- a href="#" @click="$delete(vars, key)"><h3><i class="fa fa-times-circle" style="color:red"></i></h3></a -->
                                            <a href="#" @click="dispatchDelete(vars, key)"><h3><i class="fa fa-times-circle" style="color:red"></i></h3></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <ul v-show="errors.vars">
                                <div class="alert alert-danger">
                                    <p>
                                        <strong><li>Please Add Product to Order !</li></strong>
                                    </p>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div> <!-- END ROW -->


            </div>

            <div class="form-actions mt-2">
                <div class="text-right">
                    <button type="submit" class="btn btn-info">Save</button>
                    <a class="btn btn-dark"  href="/rma">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    export default {

        props: ["props_name", "props_action", "props_products_edit", "props_rma_edit"],
        data: function () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                action_edit: false,
                add_products_initial: true,
                loading_products: false,
                loading_couriers: false,
                loading_customers: false,
                loading_vendors: false,
                loaded_customer: false,
                loaded_vendor: false,
                // var form
                form_id: '',
                form_name: null,
                form_date: '',
		        form_contact_type_id: 0,
                form_client_id: 0,
		        form_vendor_id: 0,
                form_courier_id: 0,
                form_transaction_type_id: 2,
                form_tracking: '',
                form_reference: '',
                // var adder
                product_selected: 0,
                qty: 1,
                // var errors
                errors: {
                    name :false,
                    date : false,
                    client :false,
                    vendor :false,
                    provider: false,
                    vars :false,
                },
                errors_adder: {
                    qty :false,
                    product :false,
                    available: false,
                },

		        // var products_vars
                current_prod_order_id: 0,
                current_prod_po_id: 0,
                current_prod_po_item_id: 0,
                current_prod_id: 0,
                current_prod_name: '',
                current_prod_batch: '',
                current_prod_po_name: '',
                current_prod_available: 0,

                products: [],
                clients: [],
                vendors: [],
                couriers: [],
                vars: [],
                vars_deleted: [],
                rma:[],
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
                    return `/rma/${this.form_id}`
                }
                // form action_create
                return `/rma`
            },
        },
        methods: {
            processForm:function(e) {
                this.validateDate()
                //this.validateClient()
                //this.validateVendor()
                this.validateProvider()
                this.areProductsSelected()

                if (this.errors.date || this.errors.provider || this.errors.vars) { //Put here the condition you want
                    e.preventDefault(); // Here triggering stop submit action
                    // Here you can put code relevant when event stops;
                    toastr.error('Form is Not Good!', 'Error Alert', {timeOut: 5000})
                    return;
                }
            },
            dispatchDelete(vars, key) {

                // Move Delete object to vars_deleted[]
                let exist_in_vars_deleted = 0
                exist_in_vars_deleted = this.isThisValueInArray(this.vars_deleted, vars[key].product_id)
                //exist ?
                if (exist_in_vars_deleted) {
                    key = exist_in_vars_deleted[1]
                    this.vars_deleted.splice(key,1)
                }
                //Insert in vars_deleted if exist rewrite values
                this.vars_deleted.push({
                    'order_id': this.vars[key].order_id,
                    'po_id': this.vars[key].po_id,
                    'po_item_id': this.vars[key].po_item_id,
                    'product_id': this.vars[key].product_id,
                    'product_name': this.vars[key].product_name,
                    'batch': this.vars[key].batch,
                    'po_name': this.vars[key].po_name,
                    'available': this.vars[key].available,
                    'qty': this.vars[key].qty
                })

                // Delete from vars[], vars.delete(vars, key)
                vars.splice(key,1)

            },
            dispatchAction(prodc) {
                this.current_prod_po_id = prodc.po_id
                this.current_prod_order_id = prodc.order_id
                this.current_prod_po_item_id = prodc.po_item_id
                this.current_prod_id = prodc.product_id
                this.current_prod_name = prodc.name
                this.current_prod_batch = prodc.batch
                this.current_prod_po_name = prodc.po_name
                this.current_prod_available = prodc.available

            },
            dispatchActionCustomer() {
                this.validateClient()
                this.fetchProductsByCustomerOrderID()
            },
            dispatchActionVendor() {
                this.validateVendor()
                this.fetchProductsByVendorID()
            },
            //Methods for Form
            validateDate(){
                this.errors.date = false
                if (this.form_date === '') {
                    this.errors.date = true
                }
            },
            validateVendor(){
                this.errors.vendor = false
                if (this.form_vendor_id === 0 && this.form_contact_type_id == 1) {
                    this.errors.vendor = true
                }
            },
            validateClient(){
                this.errors.client = false
                if (this.form_client_id === 0 && this.form_contact_type_id == 0) {
                    this.errors.client = true
                }
            },
            validateProvider() {
                this.errors.provider = false
                if (this.form_contact_type_id) {
                    this.validateVendor()
                    if (this.errors.vendor) {
                        this.errors.provider = true
                    }
                }else{
                    this.validateClient()
                    if (this.errors.client) {
                        this.errors.provider = true
                    }
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
                if(typeof(this.product_selected.id) == 'undefined' || this.product_selected.id === null || this.product_selected.id === 0 || JSON.stringify(this.product_selected) === '{}') {
                    this.errors_adder.product = true;
                }
            },
            getPreviousQty(array, id) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].product_id === id)
                        return array[i].qty;
                }
                return 0;
            },
            validateProductAvailable() {
                this.errors_adder.available = false;
                let previous_qty = 0
                let previous_qty_deleted = 0
                let available = 0
                previous_qty = this.getPreviousQty(this.vars, this.current_prod_id)
                previous_qty_deleted = this.getPreviousQty(this.vars_deleted, this.current_prod_id)
                available = parseInt(this.current_prod_available) - parseInt(previous_qty) + parseInt(previous_qty_deleted)

                if(this.qty > available) {
                    toastr.error('Check Input Quantity is greater than the available!', 'Error Alert', {timeOut: 5000})
                    this.errors_adder.available = true;
                }
            },
            isThisValueInArray(array, id) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].product_id === id)
                        return [array[i].qty, i];
                }
                return 0;
            },
            getValuesInArray(array, id) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].product_id === id)
                        return [array[i].qty, i];
                }
                return 0;
            },

            areErrorsAdder(){
                this.validateQty() // validate qty is good
                this.validateProductSelected() // validate Product is good
                this.validateProductAvailable() // Validate Available is good
                if (this.errors_adder.qty || this.errors_adder.product || this.errors_adder.available) {
                    return true;
                }
                return false
            },
            resetAddProducts() {
                //this.product_selected=0,
                this.qty= 1
            },
            insertNewProduct() {
                if( !this.areErrorsAdder() ) {

                    let variables = this.vars
                    ///// check why this dont fail if not found coincidence ////
                    let getInfoArray = this.isThisValueInArray(this.vars, this.current_prod_id)
                    let previousQty = getInfoArray[0]
                    let product_array_key = getInfoArray[1]
                    if (previousQty > 0){
                        this.getValuesInArray(this.vars, this.current_prod_id)
                        variables.splice(product_array_key,1)
                        this.qty = parseInt(this.qty)+parseInt(previousQty)
                    }
                    variables.push({
			            'order_id': this.current_prod_order_id,
                        'po_id': this.current_prod_po_id,
                        'po_item_id': this.current_prod_po_item_id,
                        'product_id': this.current_prod_id,
                        'product_name': this.current_prod_name,
                        'batch': this.current_prod_batch,
                        'po_name': this.current_prod_po_name,
                        'available': this.current_prod_available,
                        'qty': this.qty})
                }
                this.areProductsSelected()
                this.resetAddProducts()
            },

            //Methods for Ajax
            fetchCouriers() {
                this.loading_couriers = true
                axios.get('/get_couriers')
                    .then(response => {
                        this.couriers = response.data.couriers
                        this.loading_couriers = false
                    })
            },
            fetchClients() {
                this.loading_customers = true
                axios.get('/get_clients_with_orders')
                    .then(response => {
                        this.clients = response.data.clients
                        this.loading_customers = false
                    })
            },
            fetchVendors() {
                this.loading_vendors = true
                axios.get('/get_vendors_with_po')
                    .then(response => {
                        this.vendors = response.data.vendors
                        this.loading_vendors = false
                    })
            },
            fetchProductsByCustomerOrderID(deleteVars = 0) {
                this.product_selected = 0
                this.loading_customers = true //the loading begin
                this.loaded_vendor = false
                if (deleteVars){
                    this.vars = []
                }
                axios.get(`/get_orders_by_customer_id/${this.form_client_id}`)
                    .then(response => {
                        this.loading_customers = false
                        this.loaded_customer  = true
                        this.add_products_initial = false
                        this.products = response.data.products
                    })
                    .catch(error => {
                        this.loading_customers = false
                    })
            },
            fetchProductsByVendorID: function (deleteVars = true) {
                this.product_selected = 0
                this.loading_vendors = true //the loading begin
                this.loaded_customer = false
                if (deleteVars){
                    this.vars = []
                }
                axios.get(`/get_purchases_by_vendor_id/${this.form_vendor_id}`)
                    .then(response => {
                        this.loading_vendors = false
                        this.loaded_vendor  = true
                        this.add_products_initial = false
                        this.products = response.data.products
                    })
                    .catch(error => {
                        this.loading_vendors = false
                    })
            },
            dispatchContactType() {
                this.add_products_initial = true
                this.vars = []
                this.form_client_id = 0
                if(this.form_contact_type_id === '1'){
                    //means Vendor was Selected
                    this.form_vendor_id = 0
                }
            },
        },
        created() {
            this.fetchCouriers()
            this.fetchClients()
            this.fetchVendors()

            if (this.props_action){
                this.action_edit = true
            }

            if (this.props_name) {
                this.form_name = this.props_name
            }

            if(this.props_products_edit){
                this.vars = JSON.parse(this.props_products_edit);
            }

            if(this.props_rma_edit){
                this.rma = JSON.parse(this.props_rma_edit);
                this.form_id = this.rma.id
                this.form_name = this.rma.name
                this.form_date = this.rma.date
                this.form_contact_type_id = this.rma.contact_type_id
                if (this.form_contact_type_id){
                    this.form_vendor_id = this.rma.contact_id
                    this.loaded_customer = 0
                    this.loaded_vendor = 1
                    this.fetchProductsByVendorID(false)
                }else{
                    this.form_client_id = this.rma.contact_id
                    this.loaded_customer = 1
                    this.loaded_vendor = 0
                    this.fetchProductsByCustomerOrderID(false)
                }
                this.form_courier_id = this.rma.courier_id
                this.form_tracking = this.rma.tracking
                this.form_reference = this.rma.reference
            }
        }
    }
</script>
