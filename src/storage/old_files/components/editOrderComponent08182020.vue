<template>
    <div class="col-12">
        <form method="POST" @submit="checkForm" v-bind:action="computedAction">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Order Number</small></label>
                            <input type="hidden" name="id" v-model="order.id">
                            <input type="hidden" name="transaction_type_id" value="3">
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[error_name ? 'is-invalid' : '']" v-model="order.name" readonly>
                            <div v-show="error_name" class="invalid-feedback">Please Indicate Order Number !</div>
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0"><small>Courier</small></label>
                            <div class="input-group input-group-sm">
                                <select id="courier_id" name="courier_id" class="form-control form-control-sm" v-bind:class="[error_courier ? 'is-invalid' : '']" v-model="order.courier_id">
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
                            <input type="text" class="form-control form-control-sm" id="tracking" name="tracking" v-model="order.tracking" placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0"><small>Customer</small></label>
                            <div class="input-group input-group-sm">
                                <select id="client_id" name="client_id" class="form-control form-control-sm" v-bind:class="[error_client ? 'is-invalid' : '']" v-model="client_selected">
                                    <option value="0" disabled selected>Select Customer</option>
                                    <option v-for="client in clients" :value="client.id" :key="client.id">
                                        {{ client.name }}
                                    </option>
                                </select>
                                <div v-show="error_client" class="invalid-feedback">Please Select a Customer!</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Reference</small></label>
                            <input type="text" class="form-control form-control-sm" id="reference" name="reference" min="1" v-model="order.reference" placeholder="...">
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
                                        <strong><li>Please Add a Product to Order !</li></strong>
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
                    <button type="reset" class="btn btn-dark">Reset</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    export default {

        props: ["post_order", "post_products"],
        data: function () {
            return {
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
                current_prod_po_id: 0,
                current_prod_po_item_id: 0,
                current_prod_id: 0,
                current_prod_name: '',
                current_prod_batch: '',
                current_prod_po_name: '',
                current_prod_available: 0,
                previousqty:0,
            }
        },
        computed: {
           computedAction: function() {
               return `/order/${this.order.id}`
           }
       },
        methods: {
            dispatchAction (prodc) {
                this.current_prod_po_id = prodc.po_id
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
                if (this.client_selected == 0) {
                    this.error_client = true
                    this.errors.push('No Customer Selected')
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
                        'po_id': this.current_prod_po_id,
                        'po_item_id': this.current_prod_po_item_id,
                        'product_id': this.current_prod_id,
                        'product_name': this.current_prod_name,
                        'batch': this.current_prod_batch,
                        'po_name': this.current_prod_po_name,
                        'available': this.current_prod_available,
                        'qty': parseInt(this.qty)+parseInt(previousQty)})
                    }else {
                        variables.push({
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
            fetchPurchasesItems() {
                axios
                    .get('/get_purchases_items')
                    .then(response => {
                        this.products = response.data.products
                    })
            }
        },
        mounted() {

        },
        created() {
            this.fetchVendors()
            this.fetchCouries()
            this.fetchClients()
            this.fetchPurchasesItems()
            this.order = JSON.parse(this.post_order);
            this.vars = JSON.parse(this.post_products);
            this.date = this.order.date
            this.client_selected = this.order.client_id
        }
    }
</script>
