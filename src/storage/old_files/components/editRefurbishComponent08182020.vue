<template>
    <div class="col-12">
        <form method="POST" @submit="checkForm"  v-bind:action="computedAction">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <input type="hidden" name="id" v-model="id">
                            <label class="mb-0" ><small>Refurbish Number</small></label>
                            <input type="hidden" name="transaction_type_id" value="2">
                            <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[error_name ? 'is-invalid' : '']" v-model="name" readonly>
                            <div v-show="error_name" class="invalid-feedback">Please Indicate Refurbish Number !</div>
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
                    <div class="col-md-12">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Reference</small></label>
                            <input type="text" class="form-control form-control-sm" id="reference" name="reference" min="1" placeholder="..." v-model="form_reference">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="spinner-border text-success" role="status" v-show="(loading_products)">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="form-group-po" v-show="(!loading_products)">
                            <div class="card border-success pb-1 mb-2 mt-2">
                                <div class="card-header bg-success pb-0 pt-1">
                                    <h6 class="mb-1 mt-1 text-white text-sm-left">Add Refurbished Products</h6>
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
                            <label class="mb-0" ><small>List Products Selected</small></label>
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
                                        <strong><li>Please Add a Product to Refurbish !</li></strong>
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
                    <a class="btn btn-dark"  href="/refurbishes">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    export default {

        props: ["props_name", "props_refurbishes", "props_products"],
        data: function () {
            return {
                errors:[],
                error_name: false,
                error_date: false,
                error_qty: false,
                error_product: false,
                error_vars: false,
                id: null,
                name: null,
                date: '',
                product_selected: 0,
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
                loading_products: false,
            }
        },
        computed: {
            computedAction: function() {
                return `/refurbishes/${this.refurbish.id}`
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
            fetchRMAS() {
                this.loading_products = true
                axios
                    .get('/get_rmas')
                    .then(response => {
                        this.products = response.data.products
                        this.loading_products = false
                    })
            },
        },
        created() {
            this.fetchRMAS()

            this.refurbish = JSON.parse(this.props_refurbishes);
            this.vars = JSON.parse(this.props_products);

            this.id   = this.refurbish.id
            this.name = this.refurbish.name
            this.date = this.refurbish.date
            this.reference = this.refurbish.reference

        }
    }
</script>
