<template>
    <div class="col-12">
        <form method="POST" @submit="checkForm"  v-bind:action="computedAction">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <input type="hidden" name="id" v-model="rma.id">
                            <input type="hidden" name="transaction_type_id" value="2">
                            <label class="mb-0" ><small>RMA Number</small></label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[error_name ? 'is-invalid' : '']" v-model="name" readonly>
                            <div v-show="error_name" class="invalid-feedback">Please Indicate RMA Number !</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Date </small></label>
                            <b-form-datepicker id="example-datepicker" v-bind:class="[error_date ? 'is-invalid' : '']" v-model="date" size="sm" class="form-control form-control-sm mb-2"></b-form-datepicker>
                            <input type="hidden" name="date" id="date" :value="date">
                            <div v-show="error_date" class="invalid-feedback">Please Pick a Date !</div>
                        </div>
                    </div>
                </div>
                <div class="row"> <!-- CONTACT Type Supplier or CUSTOMER -->
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Contact Type</small></label>
                            <select id="contact_type_id" name="contact_type_id" class="form-control form-control-sm" v-model="contact_type_selected" @change="dispatchContactType">
                                <option value="0" selected>Customer</option>
                                <option value="1" >Supplier</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po" v-show="contact_type_selected < 1">
                            <label class="mb-0"><small>Customer</small></label>
                            <select id="client_id" name="client_id" class="form-control form-control-sm" v-bind:class="[error_client ? 'is-invalid' : '']" v-model="client_selected" @change="fetchProductsByCustomerOrderID">
                                <option value="0" disabled selected>Select Customer</option>
                                <option v-for="client in clients" :value="client.id" :key="client.id">
                                    {{ client.name }}
                                </option>
                            </select>
                            <div v-show="error_client" class="invalid-feedback">Please Select a Customer!</div>
                        </div>
                        <div class="form-group-po" v-show="contact_type_selected > 0">
                            <label class="mb-0"><small>Supplier</small></label>
                            <select id="vendor_id" name="vendor_id" class="form-control form-control-sm" v-bind:class="[error_vendor ? 'is-invalid' : '']" v-model="vendor_selected" @change="fetchProductsByVendorID">
                                <option value="0" disabled selected>Select Supplier</option>
                                <option v-for="vendor in vendors" :value="vendor.id" :key="vendor.id">
                                    {{ vendor.name }}
                                </option>
                            </select>
                            <div v-show="error_vendor" class="invalid-feedback">Please Select a Supplier!</div>
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
                            <input type="text" class="form-control form-control-sm" id="tracking" name="tracking" v-model="tracking" placeholder="...">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Reference Notes</small></label>
                            <input type="text" class="form-control form-control-sm" id="reference" name="reference" min="1" v-model="reference" placeholder="...">
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
                                    <h6 class="mb-1 mt-1 text-white text-sm-left" v-show="(contact_type_selected < 1)">Select Product Return From Customer!</h6>
                                    <h6 class="mb-1 mt-1 text-white text-sm-left" v-show="(contact_type_selected > 0)">Select Product Defective From Supplier!</h6>
                                </div>
                            </div>
                        </div>
                        <div class="spinner-border text-success" role="status" v-show="(loading_customer || loading_vendor)">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="form-group-po" v-show="(!add_products_initial && !loading_customer && loaded_customer)">
                            <div class="card border-success pb-1 mb-2 mt-2">
                                <div class="card-header bg-success pb-0 pt-1">
                                    <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products to RMA From Customer Order</h6>
                                </div>
                                <div class="card-body bg-light pt-1 pb-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group-po">
                                                <label class="typo__label mb-0"><small>List Products</small></label>
                                                <multiselect v-model="producta" :options="products" placeholder="Select Product" label="text" track-by="text" @select="dispatchAction">
                                                </multiselect>
                                                <div v-show="error_product" class="invalid-feedback">Please Select a Product!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Qty</small></label>
                                                <input type="number" class="form-control form-control-sm" v-bind:class="[error_qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                                <div v-show="error_qty" class="invalid-feedback">Please Indicate Qty!</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group-po">
                                                <label class="mb-0"><small>Add</small></label>
                                                <div class="input-group input-group-sm">
                                                    <button type="button" class="btn-success" @click="insertNewProduct2()"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group-po" v-show="(!add_products_initial && !loading_vendor && loaded_vendor)">
                            <div class="card border-success pb-1 mb-2 mt-2">
                                <div class="card-header bg-success pb-0 pt-1">
                                    <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products to RMA From Supplier PO</h6>
                                </div>
                                <div class="card-body bg-light pt-1 pb-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group-po">
                                                <label class="typo__label mb-0"><small>List Products</small></label>
                                                <multiselect v-model="producta" :options="products" placeholder="Select Product" label="text" track-by="text" @select="dispatchAction">
                                                </multiselect>
                                                <div v-show="error_product" class="invalid-feedback">Please Select a Product!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group-po">
                                                <label class="mb-0" ><small>Qty</small></label>
                                                <input type="number" class="form-control form-control-sm" v-bind:class="[error_qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                                <div v-show="error_qty" class="invalid-feedback">Please Indicate Qty!</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group-po">
                                                <label class="mb-0"><small>Add</small></label>
                                                <div class="input-group input-group-sm">
                                                    <button type="button" class="btn-success" @click="insertNewProduct2()"><i class="fas fa-plus"></i></button>
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
                                            <a href="#" @click="$delete(vars, key)"><h3><i class="fa fa-times-circle" style="color:red"></i></h3></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <ul v-show="error_vars">
                                <div class="alert alert-danger">
                                    <p>
                                        <strong><li>Please Add a Product to RMA !</li></strong>
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

        props: ["props_name", "props_contact_types", "props_clients", "props_vendors", "props_rma", "props_products"],
        data: function () {
            return {
                rma:[],
                errors:[],
                error_name: false,
                error_date: false,
                error_qty: false,
                error_product: false,
                error_courier: false,
                error_vendor: false,
                error_client: false,
                error_vars: false,
                name: null,
                date: '',
                contact_type_selected: 0,
                product_selected: 0,
                courier_selected: 0,
                vendor_selected: 0,
                client_selected: 0,
                qty: 1,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                products: [],
                producta:[],
                clients: [],
                couriers: [],
                vars: [],
                current_prod_order_id: 0,
                current_prod_po_id: 0,
                current_prod_po_item_id: 0,
                current_prod_id: 0,
                current_prod_name: '',
                current_prod_batch: '',
                current_prod_po_name: '',
                current_prod_available: 0,
                previousqty:0,
                loading_customer: false,
                loaded_customer: false,
                loading_vendor: false,
                loaded_vendor: false,
                add_products_initial: true,
            }
        },
        computed: {
            computedAction: function() {
                return `/rma/${this.rma.id}`
            }
        },
        methods: {
            dispatchAction (prodc) {
                this.current_prod_po_id = prodc.po_id
                //this.current_prod_order_id = (typeof prodc.order_id !== "undefined") ? prodc.order_id : 0;
                this.current_prod_order_id = prodc.order_id
                this.current_prod_po_item_id = prodc.po_item_id
                this.current_prod_id = prodc.product_id
                this.current_prod_name = prodc.name
                this.current_prod_batch = prodc.batch
                this.current_prod_po_name = prodc.po_name
                this.current_prod_available = prodc.available

            },
            disabledDate (date) {
                return date.getTime() < Date.now()
            },
            checkForm:function(e) {

                this.checkErrors()
                if (!this.errors.length) {
                    return true;
                    alert('Form is Not Good')
                }
                e.preventDefault();
            },
            checkErrors() {
                this.cleanFormErrors ()
                if (!this.name) {
                    this.error_name = true
                    this.errors.push('Name required.');
                }
                if (this.client_selected == 0 && this.contact_type_selected == 0) {
                    this.error_client = true
                    this.errors.push('No Customer Selected')
                }
                if (this.vendor_selected == 0 && this.contact_type_selected == 1) {
                    this.error_vendor = true
                    this.errors.push('No Vendor Selected')
                }
                if (this.date == '') {
                    this.error_date = true
                    this.errors.push('No Date Selected')
                }
                if (!this.vars.length) {
                    this.error_vars = true
                    this.errors.push('No products Added!');
                }
            },
            resetAddProducts() {
                this.product_id=0,
                    this.qty= 1
            },
            cleanFormErrors () {
                this.errors = [];
                this.error_name = false
                this.error_date = false
                this.error_client = false
                this.error_vars = false
            },
            cleanErrors() {
                this.errors = [];
                this.error_qty = false;
                this.error_product = false;

            },
            getQtyAvailable(array, id) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].product_id === id)
                        return array[i].qty;
                }
                return 0;
            },
            isProductSelectedGoodToAdd(){

                this.cleanErrors()

                if(this.qty < 1) {
                    this.error_qty = true;
                    this.errors.push("Please Indicate Qty!");
                }

                this.previousqty = 0
                this.previousqty = this.getQtyAvailable(this.vars, this.current_prod_id)
                let newqty = this.qty

                if(this.previousqty){
                    newqty =  parseInt(this.previousqty)+parseInt(this.qty)
                }

                if(newqty > this.current_prod_available) {
                    toastr.error('Check Input Quantity is greater than the available!', 'Error Alert', {timeOut: 5000})
                    this.errors.push("Please Select Qty <= to Available!");
                }
                if(typeof(this.producta) == 'undefined' || this.producta === null || this.producta === 0 || JSON.stringify(this.producta) === '{}') {
                    this.error_product = true;
                    this.errors.push("Must Select a Product!");
                }

                if(!this.errors.length) return true;
                return false
            },
            isThisValueInArray(array, id) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].product_id === id)
                        return [array[i].qty, i];
                }
                return 0;
            },
            insertNewProduct2() {
                if( this.isProductSelectedGoodToAdd() ) {
                    let variables = this.vars
                    let getInfoArray = this.isThisValueInArray(this.vars, this.current_prod_id)
                    let previousQty = getInfoArray[0]
                    let product_array_key = getInfoArray[1]
                    if (getInfoArray[0] > 0){
                        variables.splice(product_array_key,1)
                        variables.push({
                            'order_id': this.current_prod_order_id,
                            'po_id': this.current_prod_po_id,
                            'po_item_id': this.current_prod_po_item_id,
                            'product_id': this.current_prod_id,
                            'product_name': this.current_prod_name,
                            'batch': this.current_prod_batch,
                            'po_name': this.current_prod_po_name,
                            'available': this.current_prod_available,
                            'qty': parseInt(this.qty)+parseInt(previousQty)
                        })
                    }else {
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
                }
                this.resetAddProducts()
                this.checkErrors()
            },
            fetchVendors() {
                axios
                    .get('/get_vendors')
                    .then(response => {
                        this.vendors = response.data.vendors
                    })
            },
            fetchCouries() {
                axios
                    .get('/get_couriers')
                    .then(response => {
                        this.couriers = response.data.couriers
                    })
            },
            fetchClients() {
                axios
                    .get('/get_clients')
                    .then(response => {
                        this.clients = response.data.clients
                    })
            },
            fetchProductsByCustomerOrderID(deleteVars = 0) {
                this.loading_customer = true //the loading begin
                this.loaded_vendor = false
                if (deleteVars){
                    this.vars = []
                }
                axios.get(`/get_orders_by_customer_id/${this.client_selected}`)
                    .then(response => {
                        this.loading_customer = false
                        this.loaded_customer  = true
                        this.add_products_initial = false
                        this.products = response.data.products
                    })
                    .catch(error => {
                        this.loading_customer = false
                    })
            },
            fetchProductsByVendorID: function (deleteVars = true) {
                this.loading_vendor = true //the loading begin
                this.loaded_customer = false
                if (deleteVars){
                    this.vars = []
                }
                axios.get(`/get_purchases_by_vendor_id/${this.vendor_selected}`)
                    .then(response => {
                        this.loading_vendor = false
                        this.loaded_vendor  = true
                        this.add_products_initial = false
                        this.products = response.data.products
                    })
                    .catch(error => {
                        this.loading_vendor = false
                    })
            },
            dispatchContactType() {
                this.add_products_initial = true
                this.vars = []
                if(this.contact_type_selected == '1'){
                    //means Vendor was Selected
                    this.vendor_selected = 0
                }else{
                    this.client_selected = 0
                }
            },
        },
        mounted() {

        },
        created() {
            this.fetchCouries()
            this.clients = this.props_clients
            this.vendors = this.props_vendors
            this.rma = JSON.parse(this.props_rma);
            this.vars = JSON.parse(this.props_products);

            this.add_products_initial = false
            this.name = this.rma.name
            this.date = this.rma.date

            this.contact_type_selected = this.rma.contact_type_id
            if (this.contact_type_selected){
                this.vendor_selected = this.rma.contact_id
                this.loaded_customer = 0
                this.loaded_vendor = 1
                this.fetchProductsByVendorID(false)
            }else{
                this.client_selected = this.rma.contact_id
                this.loaded_customer = 1
                this.loaded_vendor = 0
                this.fetchProductsByCustomerOrderID(false)
            }
            this.courier_selected = this.rma.courier_id
            this.tracking = this.rma.tracking
            this.reference = this.rma.reference

        }
    }
</script>
