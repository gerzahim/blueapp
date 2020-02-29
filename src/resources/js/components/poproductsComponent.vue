<template>
    <div class="col-12">
        <form method="POST" @submit="checkForm" action="/#">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>PO Name</small></label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="name" name="name" placeholder="MIA-ZHE011..">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0"><small>Vendor</small></label>
                            <div class="input-group input-group-sm">
                                <select id="vendor_id" name="vendor_id" class="form-control input-sm" >
                                    <select id="product_id[]" name="product_id[]" class="form-control" >
                                        <option v-for="product in products" v-bind:value="product.id" v-text="product.name"></option>
                                    </select>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0"><small>Courier</small></label>
                            <div class="input-group input-group-sm">
                                <select id="couriers_id" name="courier_id" class="form-control input-sm" >
                                    <select id="prosduct_id[]" name="product_id[]" class="form-control" >
                                        <option v-for="product in products" v-bind:value="product.id" v-text="product.name"></option>
                                    </select>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Tracking Number</small></label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control input-sm" id="tracking" name="tracking" placeholder="...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Transaction Type</small></label>
                            <div class="input-group input-group-sm">
                                <select id="transaction_type_id" name="transaction_type_id" class="form-control input-sm" >
                                    <option value="1">Purchase - PO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Bill of Landing</small></label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control input-sm" id="bol" name="bol" placeholder="...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Package List</small></label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control input-sm" id="package_list" name="package_list" placeholder="...">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0" ><small>Reference</small></label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control input-sm" id="reference" name="reference" min="1" placeholder="...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card border-success pb-1 mb-2 mt-2">
                        <div class="card-header bg-success pb-0 pt-1">
                            <h4 class="mb-1 mt-1 text-white text-sm-left ">Add Products to PO</h4>
                        </div>

                        <div class="card-body pt-1 pb-1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-po">
                                        <label class="mb-0"><small>Products</small></label>
                                        <select id="product_id" name="product_id" class="form-control form-control-sm" v-bind:class="[error_product ? 'is-invalid' : '']" v-model="product_selected">
                                            <option v-for="product in products" v-bind:value="{ id: product.id, name: product.name }">
                                                {{ product.name }}
                                            </option>
                                        </select>
                                        <div v-show="error_product" class="invalid-feedback">Please Select a Product!</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group-po">
                                        <label class="mb-0" ><small>Qty</small></label>
                                        <input type="number" class="form-control form-control-sm" v-bind:class="[error_qty ? 'is-invalid' : '']" v-model="qty" min="1" value="1">
                                        <div v-show="error_qty" class="invalid-feedback">Please Indicate Qty!</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-po">
                                        <label class="mb-0" ><small>Batch</small></label>
                                        <input type="text" class="form-control form-control-sm" id="batch_number" name="batch_number" v-model="batch_number" placeholder="XFR4487...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group-po">
                                        <label class="mb-0"><small>Action</small></label>
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn-success" @click="insertVarLine()"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted">Products</h6>
                        <ul class="list-group-po nobull">
                            <li v-for="(variable, key) in vars" :key="key" class="list-group-po-item py-1 px-3">
                                <div class="row h-100">
                                    <div class="col-2 my-auto">
                                        <span class="badge badge-primary badge-pill"><b>{{variable.qty}}</b></span>
                                    </div>
                                    <div class="col-5 my-auto">{{variable.product_name}}
                                    </div>
                                    <div class="col-3 my-auto">
                                        <small>
                                            <span class="badge badge-light">{{variable.batch_number}}</span>
                                        </small>
                                    </div>
                                    <div class="col-2 my-auto">
                                        <button type="button" class="btn-danger pull-right" @click="$delete(vars, key)"><i class="fa fa-times-circle"></i></button>
                                    </div>
                                </div>
                            </li>
                        </ul>
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
                error_qty: false,
                error_product: false,
                product_selected: '',
                qty: 0,
                batch_number: '',
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                products: [],
                vars: [],
                empty: false,
            }
        },
        methods: {
            checkForm:function(e) {
                this.errors = [];
                //if(this.total != 100) this.errors.push("Total must be 100!");
                if(!this.errors.length) return true;
                e.preventDefault();
            },
            cleanAddProducts() {
                this.product_id=0,
                this.qty= 0,
                this.batch_number= ''
            },
            cleanErrors() {
              this.error_qty= false
            },
            areProductValues() {
                //this.errors = [];

                console.log(this.qty, this.qty < 1, typeof(this.qty) )
                if(this.qty < 1) {
                    this.error_qty = true;
                    console.log('epale brother -> this.error_qty', this.error_qty)
                    return false
                    //this.errors.push("Please Indicate Qty!");
                }

                if(typeof(this.product_selected.id) == 'undefined' || this.product_selected.id === null || this.product_selected.id === 0) {
                    this.error_product = true;
                    return false
                    //this.errors.push("Must Select a Product!");
                }


                //if(!this.errors.length) return true;
                //return false
                return true;
            },
            insertVarLine() {
                if( this.areProductValues() ) {
                    let variables = this.vars
                    variables.push({'product_id': this.product_selected.id,'product_name': this.product_selected.name, 'qty': this.qty, 'batch_number': this.batch_number})
                    this.cleanErrors()
                }
                this.cleanAddProducts()
                console.log('epale brother 2 -> this.error_qty', this.error_qty)
            },

            fetchProducts() {
                axios
                    .get('/get_products')
                    .then(response => {
                        this.products = response.data.products
                    })
            }
        },
        created() {

        },
        mounted() {
            this.fetchProducts()
            console.log('Component mounted.')
        }
    }
</script>
