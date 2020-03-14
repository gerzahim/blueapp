<template>
    <div class="col-12">
        <form method="POST" @submit="checkForm" action="/purchases">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Order Number</small></label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[error_name ? 'is-invalid' : '']" v-model="name" placeholder="MIA-ZHE011..">
                            <div v-show="error_name" class="invalid-feedback">Please Indicate unique Order Number !</div>
                        </div>
                    </div>
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
                                <option value="3">Sale</option>
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
                            <label class="mb-0" ><small>Date</small></label>
                            <input type="text" class="form-control form-control-sm" id="package_list" name="package_list" placeholder="...">
                        </div>
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
                                    <div class="col-sm-12 col-lg-12 col-xl-6 my-auto px-1">
                                        {{variable.product_name}}
                                        <span class="badge badge-primary badge-pill"><b>{{variable.qty}}</b></span>
                                    </div>
                                    <div class="col-sm-12 col-lg-12 col-xl-5 my-auto px-1">
                                        <h6>
                                            <span class="badge badge-dark">{{variable.batch_number}}</span>
                                        </h6>
                                    </div>
                                    <div class="col-sm-12 col-lg-12 col-xl-1 my-auto px-1 text-right">
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
                                        <select id="purchaseitem_id" name="purchaseitem_id" class="form-control form-control-sm" v-bind:class="[error_purchases_items ? 'is-invalid' : '']" v-model="purchases_items_selected">
                                            <option value="0" disabled selected>Select Product</option>
                                            <option v-for="purchaseitem in purchases_items" v-bind:value="{ id: purchaseitem.id, text: purchaseitem.text }" :key="purchaseitem.id">
                                                {{ purchaseitem.text }}
                                            </option>
                                        </select>
                                        <div v-show="error_purchases_items" class="invalid-feedback">Please Select a Product!</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group-po">
                                        <label class="mb-0" ><small>Qty</small></label>
                                        <input type="number" class="form-control form-control-sm" v-bind:class="[error_qty ? 'is-invalid' : '']" v-model="qty" min="1" max="5">
                                        <div v-show="error_qty" class="invalid-feedback">Please Indicate Qty!</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
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
        data: function () {
            return {
                errors:[],
                error_name: false,
                error_qty: false,
                error_product: false,
                error_courier: false,               
                error_client: false,
                error_purchases_items: false,
                error_vars: false,
                name: null,
                product_selected: 0,
                courier_selected: 0,
                client_selected: 0,
                purchases_items_selected: 0,
                qty: 1,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                products: [],
                clients: [],
                couriers: [],
                purchases_items: [],
                vars: [],
            }
        },
        methods: {
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
                    this.errors.push('No Client Selected')
                }
                if (!this.vars.length) {
                    this.error_vars = true
                    this.errors.push('No products Added!');
                }
            },
            resetAddProducts() {
                this.purchaseitem_id=0,
                this.qty= 1,
            },
            cleanFormErrors () {
                this.errors = [];
                this.error_name = false
                this.error_client = false
                this.error_vars = false
            },
            cleanErrors() {
                this.errors = [];
                this.error_qty = false;
                this.error_purchases_items = false;

            },
            isProductGoodToAdd() {
                this.cleanErrors()

                if(this.qty < 1) {
                    this.error_qty = true;
                    this.errors.push("Please Indicate Qty!");
                }

                if(typeof(this.purchases_items_selected.id) == 'undefined' || this.purchases_items_selected.id === null || this.purchases_items_selected.id === 0) {
                    this.error_purchases_items = true;
                    this.errors.push("Must Select a Product!");
                }

                if(!this.errors.length) return true;
                return false
            },
            isThisValuesInArray(array, id) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].purchaseitem === id)
                        return [array[i].qty, i];
                }
                return 0;
            },
            insertNewProduct() {
                if( this.isProductGoodToAdd() ) {
                    let variables = this.vars
                    //purchaseitem
                    let getInfoArray = this.isThisValuesInArray(this.vars, this.purchases_items_selected.id)
                    let previousQty = getInfoArray[0]
                    let product_array_key = getInfoArray[1]
                    if (getInfoArray[0] > 0){
                        variables.splice(product_array_key,1)
                        variables.push({'product_id': this.product_selected.id,'product_name': this.product_selected.name, 'qty': parseInt(this.qty)+parseInt(previousQty)})
                    }else {
                        variables.push({'product_id': this.product_selected.id,'product_name': this.product_selected.name, 'qty': this.qty})
                    }
                    console.log('Component mounted.', typeof(this.vars), this.vars)
                }
                this.resetAddProducts()
                this.checkErrors()
            },
            fetchProducts() {
                axios
                    .get('/get_products')
                    .then(response => {
                        this.products = response.data.products
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
                        this.purchases_items = response.data.purchases_items
                    })
            } 
        },
        mounted() {

        },
        created() {
            this.fetchProducts()
            this.fetchCouries()
            this.fetchClients()
            this.fetchPurchasesItems()            
        }
    }
</script>
