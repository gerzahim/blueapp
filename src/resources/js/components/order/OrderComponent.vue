<template>
    <div class="col-12">
        <form method="POST" @submit="processForm" v-bind:action="computedAction">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="_method" value="PUT" v-if="action_edit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Order Number</small></label>
                            <input type="hidden" name="id" v-model="form_id">
                            <input type="hidden" name="transaction_type_id" :value="form_transaction_type_id">
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[ errors.name ? 'is-invalid' : '']" v-model="form_name" readonly>
                            <div v-show="errors.name" class="invalid-feedback">Please Indicate Order Number !</div>
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
                            <div class="spinner-border text-success" role="status" v-show="loading_customers">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div v-show="!loading_customers">
                                <label class="mb-0"><small>Customer</small></label>
                                <select id="client_id" name="client_id" class="form-control form-control-sm" v-bind:class="[ errors.client ? 'is-invalid' : '']" v-model="form_client_id" @change="validateClient">
                                    <option value="0" disabled selected>Select Customer</option>
                                    <option v-for="client in clients" :value="client.id" :key="client.id">
                                        {{ client.name }}
                                    </option>
                                </select>
                                <div v-show="errors.client" class="invalid-feedback">Please Select a Supplier!</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Reference</small></label>
                            <input type="text" class="form-control form-control-sm" id="reference" name="reference" min="1" placeholder="..." v-model="form_reference">
                        </div>
                    </div>
                </div>

                <!-- Table Row of Products -->
                <div class="p-2 border shadow-sm rounded">
                    <input type="hidden" name="vars" :value="JSON.stringify(vars)"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="35%" scope="col">Product</th>
                                            <th width="20%" scope="col">Batch Number</th>
                                            <th width="25%" scope="col">PO</th>
                                            <th width="10%" scope="col">Qty</th>
                                            <th width="10%" scope="col"><center>Handle</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(variable, key) in vars" :key="key" >
                                            <td width="35%">
                                                {{variable.product_name}}
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{variable.batch}}</span>
                                            </td>
                                            <td scope="row">
                                                <span class="badge badge-warning">{{variable.po_name}}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary badge-pill"><b>{{variable.qty}}</b></span>
                                            </td>
                                            <td align="center">
                                                <a href="#" @click="dispatchDelete(vars, key)">
                                                    <h3><i class="far fa-trash-alt" style="color:red"></i></h3>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr v-show="errors.vars">
                                            <th colspan="5">
                                                <div class="alert alert-danger">
                                                    {{ errors.message }}
                                                </div>
                                            </th>
                                        </tr>
                                        <!-- tr>
                                            <th colspan="5">                                                
                                                <div class="col-md-12">
                                                    <div class="form-group-po">
                                                        <div class="spinner-border text-success" role="status" v-show="loading_products">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <div class="card border-success pb-1 mb-2 mt-2" v-show="!loading_products">
                                                            <div class="card-header bg-success pb-0 pt-1">
                                                                <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products to Order</h6>
                                                            </div>
                                                            <div class="card-body bg-light pt-1 pb-1">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <div class="form-group-po">
                                                                            <label class="typo__label mb-0"><small>List Products</small></label>
                                                                            <multiselect :options="products" placeholder="Select Product" label="text" track-by="text" v-model="product_selected" @select="dispatchAction">
                                                                            </multiselect>
                                                                            <div v-show="errors_adder.product" class="invalid-feedback">Please Select a Product!</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group-po">
                                                                            <label class="mb-0" ><small>Qty</small></label>
                                                                            <input type="number" class="form-control form-control-sm" v-bind:class="[errors_adder.qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                                                            <div v-show="errors_adder.qty" class="invalid-feedback">Please Indicate Qty!</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group-po">
                                                                            <label class="mb-0"><small>Add</small></label>
                                                                            <div class="input-group input-group-sm">
                                                                                <button type="button" class="btn-success" @click="insertNewProduct()"><i class="fas fa-plus-circle"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Table Row of Products -->

                <!-- Adder Products Tools -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="spinner-border text-success" role="status" v-show="loading_products">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="card border-success pb-1 mb-2 mt-2" v-show="!loading_products">
                            <div class="card-header bg-success pb-0 pt-1">
                                <h6 class="mb-1 mt-1 text-white text-sm-left">Add Products to Order</h6>
                            </div>
                            <div class="card-body bg-light pt-1 pb-1">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group-po">
                                            <label class="typo__label mb-0"><small>List Products</small></label>
                                            <multiselect :options="products" placeholder="Select Product" label="text" track-by="text" v-model="product_selected" @select="dispatchAction">
                                            </multiselect>
                                            <div v-show="errors_adder.product" class="invalid-feedback">Please Select a Product!</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group-po">
                                            <label class="mb-0" ><small>Qty</small></label>
                                            <input type="number" class="form-control form-control-sm" v-bind:class="[errors_adder.qty ? 'is-invalid' : '']" v-model="qty" min="1">
                                            <div v-show="errors_adder.qty" class="invalid-feedback">Please Indicate Qty!</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group-po">
                                            <label class="mb-0"><small>Add</small></label>
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn-success" @click="insertNewProduct()"><i class="fas fa-plus-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- END Adder ROW -->
            </div>

            <div class="form-actions mt-2">
                <div class="text-right">
                    <button type="submit" class="btn btn-info">
                        <i class="far fa-save"></i>&nbsp;Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ["props_name", "props_action", "props_products_edit", "props_order_edit"],
        data: function () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,

                action_edit      : false,
                loading_products : false,
                loading_customers: false,
                loading_couriers : false,
                
                // var form
                form_id                 : '',
                form_name               : null,
                form_date               : '',
                form_client_id          : 0,
                form_courier_id         : 0,
                form_transaction_type_id: 3,
                form_tracking           : '',
                form_reference          : '',

                // var adder
                product_selected: 0,
                qty             : 1,

                // var errors
                errors: {
                    name   : false,
                    date   : false,
                    client : false,
                    vendor : false,
                    vars   : false,
                },
                errors_adder: {
                    qty      : false,
                    product  : false,
                    available: false,
                },

                // var products_vars
                current_prod_po_id     : 0,
                current_prod_po_item_id: 0,
                current_prod_id        : 0,
                current_prod_name      : '',
                current_prod_batch     : '',
                current_prod_po_name   : '',
                current_prod_available : 0,

                products      : [],
                clients       : [],
                couriers      : [],
                vars          : [],
                vars_available: [],
                order         : [],
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
                    return `/order/${this.form_id}`
                }
                // form action_create
                return `/order`
            },
        },
        methods: {
            processForm:function(e) {
                this.validateDate()
                this.validateClient()
                this.areProductsSelected()

                if (this.errors.date || this.errors.vendor || this.errors.vars) { //Put here the condition you want
                    e.preventDefault(); // Here triggering stop submit action
                    // Here you can put code relevant when event stops;
                    toastr.error('Form is Not Good!', 'Error Alert', {timeOut: 5000})
                    return;
                }
            },
            dispatchDelete(vars, key) {

                // Move Delete object to vars_deleted[]
                let exist_in_vars_available = 0
                exist_in_vars_available = this.isThisValueInArray(this.vars_available, vars[key].product_id)
                //exist ?
                if (!exist_in_vars_available) {
                    this.vars_available.push({
                        'product_id': this.vars[key].product_id,
                        'qty': parseInt(this.vars[key].available) + parseInt(this.vars[key].qty)
                    })
                }

                // Delete from vars[], vars.delete(vars, key)
                vars.splice(key,1)

            },
            dispatchAction (prodc) {
                this.current_prod_po_id = prodc.po_id
                this.current_prod_po_item_id = prodc.po_item_id
                this.current_prod_id = prodc.product_id
                this.current_prod_name = prodc.name
                this.current_prod_batch = prodc.batch
                this.current_prod_po_name = prodc.po_name
                this.current_prod_available = prodc.available
            },
            //Methods for Form
            validateDate(){
                this.errors.date = false
                if (this.form_date === '') {
                    this.errors.date = true
                }
            },
            validateClient(){
                this.errors.client = false
                if (this.form_client_id === 0) {
                    this.errors.client = true
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
            getPreviousQty(array, id) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].product_id === id)
                        return array[i].qty;
                }
                return 0;
            },
            validateProductAvailable() {
                this.errors_adder.available = false;
                let previous_qty            = 0
                let previous_qty_edit       = 0
                let available               = 0

                // exist on vars_edit, means was already added
                previous_qty_edit = this.getPreviousQty(this.vars_available, this.current_prod_id)
                // exist available

                previous_qty = this.getPreviousQty(this.vars, this.current_prod_id)
                if (!previous_qty_edit ) {
                    if (previous_qty){
                        this.vars_available.push({
                            'product_id': this.current_prod_id,
                            'qty': parseInt(this.current_prod_available) + parseInt(previous_qty)
                        })
                    }else{
                        this.vars_available.push({
                            'product_id': this.current_prod_id,
                            'qty': parseInt(this.current_prod_available)
                        })
                    }
                }

                available = this.getPreviousQty(this.vars_available, this.current_prod_id)

                if( (parseInt(this.qty)+ parseInt(previous_qty)) > available) {
                    toastr.error('Quantity entered is greater than the quantity available!', 'Error Alert', {timeOut: 5000})
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

            areErrorsAdder(){
                this.validateQty() // validate qty is good
                this.validateProductSelected() // validate Product is good
                this.validateProductAvailable() // Validate Available is good
                if (this.errors_adder.qty || this.errors_adder.product || this.errors_adder.available) {
                    return true;
                }
                return false
            },
            resetAdderProducts() {
                this.product_selected = 0,
                this.qty = 1
            },
            insertNewProduct() {
                if( !this.areErrorsAdder() ) {

                    let variables         = this.vars
                    let getInfoArray      = this.isThisValueInArray(this.vars, this.current_prod_id)
                    let previousQty       = getInfoArray[0]
                    let product_array_key = getInfoArray[1]
                    // If Product exist already on list , update Qty
                    if (previousQty > 0) {
                        variables.splice(product_array_key,1)
                        this.qty = parseInt(this.qty)+parseInt(previousQty)
                    }
                    variables.push({
                        'po_id'       : this.current_prod_po_id,
                        'po_item_id'  : this.current_prod_po_item_id,
                        'product_id'  : this.current_prod_id,
                        'product_name': this.current_prod_name,
                        'batch'       : this.current_prod_batch,
                        'po_name'     : this.current_prod_po_name,
                        'available'   : this.current_prod_available,
                        'qty'         : this.qty 
                    })
                }
                this.areProductsSelected()
                this.resetAdderProducts()
            },

            //Methods for Ajax
            fetchClients() {
                this.loading_customers = true
                axios.get('/get_clients')
                    .then(response => {
                        this.clients           = response.data.clients
                        this.loading_customers = false
                    })
            },
            fetchCouriers() {
                this.loading_couriers = true
                axios.get('/get_couriers')
                    .then(response => {
                        this.couriers         = response.data.couriers
                        this.loading_couriers = false
                    })
            },
            fetchPurchasesItems() {
                this.loading_products = true
                axios
                    .get('/get_purchases_items')
                    .then(response => {
                        this.products         = response.data.products
                        this.loading_products = false
                    })
            }
        },
        created() {
            this.fetchCouriers()
            this.fetchClients()
            this.fetchPurchasesItems()

            if (this.props_action){
                this.action_edit = true
            }

            if (this.props_name) {
                this.form_name = this.props_name
            }

            if(this.props_products_edit){
                this.vars = JSON.parse(this.props_products_edit);
            }

            if(this.props_order_edit){
                this.order           = JSON.parse(this.props_order_edit);
                this.form_id         = this.order.id
                this.form_name       = this.order.name
                this.form_date       = this.order.date
                this.form_client_id  = this.order.client_id
                this.form_courier_id = this.order.courier_id
                this.form_tracking   = this.order.tracking
                this.form_reference  = this.order.reference
            }
        }
    }
</script>
