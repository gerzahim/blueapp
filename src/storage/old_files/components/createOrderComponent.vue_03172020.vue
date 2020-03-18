<template>
    <div class="col-12">
        <form method="POST" @submit="checkForm" action="/order">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Order Number</small></label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[error_name ? 'is-invalid' : '']" v-model="name" readonly>
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
                            <input type="text" class="form-control form-control-sm" id="tracking" name="tracking" placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Transaction Type</small></label>
                            <select id="transaction_type_id" name="transaction_type_id" class="form-control form-control-sm" >
                                <option value="3">Sales</option>
                            </select>
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
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                            <label class="typo__label">Select with search</label>
                            <multiselect v-model="producta" :options="products" placeholder="Select Product" label="text" track-by="text" @select="dispatchAction">
                            </multiselect>
                            <pre class="language-json"><code>{{ producta }}</code></pre>
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <!--
<div>
  <label class="typo__label">Open console to see logs.</label>
  <multiselect placeholder="Pick action" :options="actions" :searchable="false" :reset-after="true" @select="dispatchAction"></multiselect>
</div>

<div>
  <label class="typo__label">Groups</label>
  <multiselect v-model="value" :options="options" :multiple="true" group-values="libs" group-label="language" :group-select="true" placeholder="Type to search" track-by="name" label="name"><span slot="noResult">Oops! No elements found. Consider changing the search query.</span></multiselect>
  <pre class="language-json"><code>{{ value  }}</code></pre>
</div>

                        <div class="form-group">
                            <label class="typo__label mb-0"><small>Select Product</small></label>
                            <multiselect v-model="value" deselect-label="Can't remove this value" track-by="text" label="text" placeholder="Select one" :options="products" :searchable="true" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ product }">{{ product.text }}</template>
                            </multiselect>
                            <pre class="language-json"><code>{{ value  }}</code></pre>
                        </div>
                        -->
                    </div>                                     
                </div>




                <!-- List Products added -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted">Products</h6>
                        <input type="hidden" name="vars" :value="JSON.stringify(vars)">
                        <ul class="list-group-po nobull px-1">
                            <li v-for="(variable, key) in vars" :key="key" class="list-group-po-item py-1 px-1 mx-1 bg-light">
                                <div class="row mx-1 h-100">
                                    <div class="col-sm-12 col-lg-12 col-xl-12 my-auto px-1">
                                        {{variable.product_name}}
                                        <span class="badge badge-primary badge-pill"><b>{{variable.qty}}</b></span>
                                        <span class="badge badge-dark">{{variable.batch}}</span>
                                    </div>
                                    <div class="col-sm-12 col-lg-11 col-xl-11 my-auto px-1">
                                        PO: {{variable.po_name}}
                                    </div>
                                    <div class="col-sm-12 col-lg-1 col-xl-1 my-auto px-1 text-right">
                                        <button type="button" class="btn-danger pull-right" @click="$delete(vars, key)"><i class="fa fa-times-circle"></i></button>
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
                
                <!-- Add Products -->
                <div class="col-md-12 px-0">
                    <div class="card border-success pb-1 mb-2 mt-2">
                        <div class="card-header bg-success pb-0 pt-1">
                            <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products to Order</h6>
                        </div>

                        <div class="card-body bg-light pt-1 pb-1">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group-po">
                                        <label class="mb-0"><small>Products</small></label>
                                        <select id="product_id" name="product_id" class="form-control form-control-sm" v-bind:class="[error_product ? 'is-invalid' : '']" v-model="product_selected">
                                            <option value="0" disabled selected>Select a Product</option>
                                            <option v-for="product in products" v-bind:value="{ id: product.id, text: product.text, name: product.name, batch: product.batch, po_name: product.po_name, available: product.available }" :key="product.id">
                                                {{ product.text }}
                                            </option>
                                        </select>
                                        <div v-show="error_product" class="invalid-feedback">Please Select a Product!</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group-po">
                                        <label class="mb-0" ><small>Qty</small></label>
                                        <input type="number" class="form-control form-control-sm" v-bind:class="[error_qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                        <div v-show="error_qty" class="invalid-feedback">Please Indicate Qty!</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
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

        props: ["post_name"],
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
                value: [],
                current_prod_po_id: 0,
                current_prod_id: 0,
                current_prod_name: '',
                current_prod_batch: '',
                current_prod_po_name: '',
                current_prod_available: 0,
            }
        },
        methods: {
            dispatchAction (prodc) {
                console.log('You just dispatched "console.log" action!', prodc)
                this.current_prod_po_id = prodc.po_id
                this.current_prod_id = prodc.product_id
                this.current_prod_name = prodc.name
                this.current_prod_batch = prodc.batch
                this.current_prod_po_name = prodc.po_name
                this.current_prod_available = prodc.available
                console.log('You current_prod_po_id', this.current_prod_po_id)
                console.log('You current_prod_id', this.current_prod_id)
                console.log('You current_prod_name', this.current_prod_name)
                console.log('You current_prod_batch', this.current_prod_batch)
                console.log('You current_prod_po_name', this.current_prod_po_name)
                console.log('You current_prod_available', this.current_prod_available)

                /*
                id: 5
                text: "Product: Product 541 | PO: BBB7 | Available (10)"
                product_id: 2
                name: (...)
                batch: (...)
                po_name: (...)
                po_id: (...)
                po_item_id: (...)
                available: (...)
                */

                
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
            isProductGoodToAdd() {
                this.cleanErrors()

                if(this.qty < 1) {
                    this.error_qty = true;
                    this.errors.push("Please Indicate Qty!");
                }

                if(typeof(this.product_selected.id) == 'undefined' || this.product_selected.id === null || this.product_selected.id === 0) {
                    this.error_product = true;
                    this.errors.push("Must Select a Product!");
                }

                if(!this.errors.length) return true;
                return false
            },
            isProductSelectedGoodToAdd(){
                console.log('I entered Here  qty - current_prod_available', this.qty, this.current_prod_available)
                this.cleanErrors()

                if(this.qty < 1) {
                    this.error_qty = true;
                    this.errors.push("Please Indicate Qty!");
                }
                if(this.qty > this.current_prod_available) {
                    this.error_qty = true;
                    this.errors.push("Please Select Qty <= to Available!");
                }
                console.log('I entered Here Main producta', this.producta)
                if(typeof(this.producta) == 'undefined' || this.producta === null || this.producta === 0 || JSON.stringify(this.producta) === '{}') {
                    console.log('I entered Here  producta', this.producta)
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
                    let getInfoArray = this.isThisValueInArray(this.vars, this.product_selected.id)
                    let previousQty = getInfoArray[0]
                    let product_array_key = getInfoArray[1]
                    if (getInfoArray[0] > 0){
                        variables.splice(product_array_key,1)
                        variables.push({
                        'po_id': this.current_prod_po_id,
                        'product_id': this.current_prod_id,
                        'product_name': this.current_prod_name,
                        'batch': this.current_prod_batch,
                        'po_name': this.current_prod_po_name,
                        'available': this.current_prod_available, 
                        'qty': parseInt(this.qty)+parseInt(previousQty)})
                    }else {
                        variables.push({
                        'po_id': this.current_prod_po_id,
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
            insertNewProduct() {
                if( this.isProductGoodToAdd() ) {
                    let variables = this.vars
                    let getInfoArray = this.isThisValueInArray(this.vars, this.product_selected.id)
                    let previousQty = getInfoArray[0]
                    let product_array_key = getInfoArray[1]
                    if (getInfoArray[0] > 0){
                        variables.splice(product_array_key,1)
                        variables.push({'po_id': this.product_selected.po_id,'product_id': this.product_selected.id,'product_name': this.product_selected.name,'batch': this.product_selected.batch,'po_name': this.product_selected.po_name,'available': this.product_selected.available, 'qty': parseInt(this.qty)+parseInt(previousQty)})
                    }else {
                        variables.push({'po_id': this.product_selected.po_id,'product_id': this.product_selected.id,'product_name': this.product_selected.name,'batch': this.product_selected.batch,'po_name': this.product_selected.po_name,'available': this.product_selected.available, 'qty': this.qty})
                    }
                }
                this.resetAddProducts()
                this.checkErrors()
            },
            fetchProducts() {
                axios
                    .get('/get_products')
                    .then(response => {
                        //this.products = response.data.products
                    })
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
            this.name = this.post_name;            
        }
    }
</script>
