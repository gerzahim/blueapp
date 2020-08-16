<template>
    <div class="col-12">
        <form method="POST" @submit="processForm" v-bind:action="computedAction">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="_method" value="PUT" v-if="action_edit" />
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0"><small>PO Name</small></label>
                            <input type="hidden" name="id" v-model="form_id" />
                            <input type="hidden" name="transaction_type_id" :value="form_transaction_type_id">
                            <input type="text" id="name" name="name" placeholder="MIA-ZHE011.."
                                   class="form-control form-control-sm"
                                   v-bind:class="[errors.name ? 'is-invalid' : '']"
                                   v-model="form_name"
                                   @change="validateName"
                            />
                            <div v-show="errors.name" class="invalid-feedback">
                                Please Indicate unique PO Name !
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <label class="mb-0"><small>Date</small></label>
                            <b-form-datepicker
                                id="example-datepicker" size="sm"
                                class="form-control form-control-sm mb-2"
                                v-bind:class="[errors.date ? 'is-invalid' : '']"
                                v-model="form_date"
                            ></b-form-datepicker>
                            <input type="hidden" name="date" id="date" :value="form_date">
                            <div v-show="errors.date" class="invalid-feedback">
                                Please Pick a Date !
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-po">
                            <div v-show="loading_vendors" class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div v-show="!loading_vendors">
                                <label class="mb-0"><small>Supplier</small></label>
                                <select id="vendor_id" name="vendor_id"
                                        class="form-control form-control-sm"
                                        v-bind:class="[ errors.vendor ? 'is-invalid' : '']"
                                        v-model="form_vendor_id"
                                        @change="validateVendor">
                                    <option value="0" disabled selected>Select Supplier</option>
                                    <option v-for="vendor in vendors" :value="vendor.id" :key="vendor.id">
                                        {{ vendor.name }}
                                    </option>
                                </select>
                                <div v-show="errors.vendor" class="invalid-feedback">
                                    Please Select a Supplier!
                                </div>
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

                <!-- Table Row of Products -->
                <div class="p-2 border shadow-sm rounded">
                    <input
                        type="hidden"
                        name="vars"
                        :value="JSON.stringify(vars)"
                    />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50%" scope="col">
                                                Product
                                            </th>
                                            <th width="30%" scope="col">
                                                Batch Number
                                            </th>
                                            <th width="10%" scope="col">Qty</th>
                                            <th width="10%" scope="col">
                                                <center>Handle</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(po_product, k) in vars" :key="k" >
                                            <td width="50%">
                                                <div v-show="loading_products" class="spinner-border text-success" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <div v-show="!loading_products" class="input-group">
                                                    <select id="product_id" name="product_id"
                                                            class="form-control form-control-sm"
                                                            v-model="po_product.product_id"
                                                            @change="validateProductsVars">
                                                        <option value="0" selected disabled>Select Product</option>
                                                        <option v-for="product in products" v-bind:value="product.id" :key="product.id">
                                                            {{ product.name }}
                                                        </option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <!-- Modal Button - Create Product  -->
                                                        <button
                                                            type="button"
                                                            class="btn waves-effect waves-light btn-light btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#create-modal"
                                                        >
                                                            <i
                                                                class="fas fa-plus-circle"
                                                            ></i
                                                            >&nbsp;Add New
                                                            Product
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" id="batch_number" name="batch_number" placeholder="XFR4487..."
                                                       class="form-control form-control-sm text-uppercase"                                                    
                                                       v-model="po_product.batch_number"
                                                       @change="validateProductsVars"
                                                />
                                            </td>
                                            <th scope="row">
                                                <input type="number" min="1" class="form-control form-control-sm"
                                                       v-model="po_product.qty"
                                                       @change="validateProductsVars">
                                            </th>
                                            <td align="center">
                                                <a href="#" @click="$delete(vars, k)">
                                                    <h3><i class="far fa-trash-alt" style="color:red"></i></h3>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr v-show="errors.vars">
                                            <th colspan="4">
                                                <div class="alert alert-danger">
                                                    {{ errors.message }}
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">
                                                <button type="button" class="btn btn-success btn-sm" @click="addNewRow">
                                                    <i class="fas fa-plus-circle"></i>&nbsp; Add a Line
                                                </button>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Table Row of Products -->
            </div>

            <div class="form-actions mt-2">
                <div class="text-right">
                    <button type="submit" class="btn btn-info">
                        <i class="far fa-save"></i>&nbsp;Save
                    </button>
                </div>
            </div>
        </form>


        <!-- Modal content -->
        <div id="create-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="fa fa-window-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="modal-body mt-2">
                        <ul class="nav nav-tabs nav-justified nav-bordered mb-3">
                            <li class="nav-item">
                                <a href="#home" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Create New Product</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile" data-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Create New Category</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block"><i class="fas fa-plus-circle"></i>&nbsp; New P. Dimensions</span>
                                </a>
                            </li>
                        </ul>


                        <div class="tab-content">
                            <br><br>
                            <div class="tab-pane show active" id="home">
                                <form method="POST" @submit.prevent="addNewProduct" action="/product">
                                    <input type="hidden" name="_token" :value="csrf">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Product Name</label>
                                                    <input type="text" class="form-control" id="product_name" name="name" v-model="form_modal_product_name" placeholder="P10-MCXXa">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Product Description</label>
                                                    <input type="text" class="form-control" id="product_description" name="description" v-model="form_modal_product_description" placeholder="Unit Cabinet P10, PO MIA-ZH...">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Dimensions</label>
                                                    <select id="dimensions_id" name="product_dimensions_id" class="form-control form-control-sm" v-model="form_modal_product_dimension">
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
                                                    <select id="category_id" name="product_category_id" class="form-control form-control-sm" v-model="form_modal_product_category">
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
                                                <div v-show="loading_save_product">
                                                    <button type="button" class="btn btn-light" data-dismiss="modal">
                                                        <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Close
                                                    </button>
                                                    <button class="btn btn-primary" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        Saving...
                                                    </button>
                                                </div>
                                                <div v-show="!loading_save_product">
                                                    <button type="button" class="btn btn-light" data-dismiss="modal">
                                                        <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Close
                                                    </button>
                                                    <button type="submit" class="btn btn-info">
                                                        <i class="far fa-save"></i>&nbsp;Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="tab-pane" id="profile">
                                <p>In Construction...</p>
                            </div>
                            <div class="tab-pane" id="settings">
                                <p>In Construction...</p>
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
    data: function() {
        return {
            csrf: document.head.querySelector('meta[name="csrf-token"]')
                .content,

            action_edit         : false,
            loading_products    : false,
            loading_vendors     : false,
            loading_couriers    : false,
            loading_dimensions  : false,
            loading_categories  : false,
            loading_save_product: false,

            // var form
            form_id                 : "",
            form_name               : null,
            form_date               : "",
            form_vendor_id          : 0,
            form_courier_id         : 0,
            form_transaction_type_id: 1,
            form_tracking           : "",
            form_bol                : "",
            form_package_list       : "",
            form_reference          : "",

            // var form_modal_product
            form_modal_product_name       : "",
            form_modal_product_description: "",
            form_modal_product_dimension  : 0,
            form_modal_product_category   : 0,

            // var adder
            product_selected: 0,
            qty: 1,
            batch_number: "",

            options_toastr: {
                closeButton      : true,
                progressBar      : true,
                positionClass    : "toast-top-right",
                preventDuplicates: true,
                showDuration     : "300",
                hideDuration     : "1000",
                extendedTimeOut  : "1000",
                timeOut          : 5000
            },

            // var errors
            errors: {
                name   : false,
                date   : false,
                vendor : false,
                vars   : false,
                message: ""
            },

            products  : [],
            vendors   : [],
            couriers  : [],
            dimensions: [],
            categories: [],
            vars      : [],
            purchase  : []
        };
    },

    watch: {
        form_date() {
            this.validateDate();
        }
    },

    computed: {
        computedAction: function() {
            if (this.action_edit) {
                return `/purchases/${this.form_id}`;
            }
            // form action_create
            return `/purchases`;
        }
    },

    methods: {
        processForm: function(e) {
            this.validateName();
            this.validateDate();
            this.validateVendor();
            this.validateProductsVars(this.vars);

            if (
                this.errors.name ||
                this.errors.date ||
                this.errors.vendor ||
                this.errors.vars
            ) {
                e.preventDefault(); // Here triggering stop submit action
                // Here you can put code relevant when event stops;
                toastr.error(
                    "Values in Form aren't right !",
                    "Error Alert",
                    this.options_toastr
                );
                return;
            }
        },

        // Add New Product From Modal
        addNewProduct() {
            if (
                this.form_modal_product_name === "" ||
                typeof this.form_modal_product_name == "undefined"
            ) {
                toastr.error("Please Type a Name !", "Error Alert", this.options_toastr);
                return;
            }

            if (
                typeof this.form_modal_product_dimension == "undefined" ||
                this.form_modal_product_dimension === null ||
                this.form_modal_product_dimension === 0
            ) {
                toastr.error("Please Select a Product Dimension !", "Error Alert", this.options_toastr);
                return;
            }

            if (
                typeof this.form_modal_product_category == "undefined" ||
                this.form_modal_product_category === null ||
                this.form_modal_product_category === 0
            ) {
                toastr.error("Please Select a Category !", "Error Alert", this.options_toastr);
                return;
            }

            try {
                this.loading_save_product = true;
                axios.post("/create_product", {
                        name         : this.form_modal_product_name,
                        description  : this.form_modal_product_description,
                        dimensions_id: this.form_modal_product_dimension,
                        category_id  : this.form_modal_product_category
                    })
                    .then(response => {
                        this.loading_save_product = false;
                        toastr.success("Product added Successfully", "Information Alert", this.options_toastr);
                    })
                    .catch(error => {
                        this.loading_save_product = false;
                        toastr.error("Fail to Save Product !", "Error Alert", this.options_toastr);
                    });
            } catch (error) {
                console.log(error);
            }

            // Clear Inputs Fields
            this.form_modal_product_name        = "";
            this.form_modal_product_description = "";
            this.form_modal_product_dimension   = 0;
            this.form_modal_product_category    = 0;

            // Update products List
            this.fetchProducts();
        },
        //Methods for Form
        validateName() {
            this.errors.name = false;
            if (!this.form_name) {
                this.errors.name = true;
            }
        },
        validateDate() {
            this.errors.date = false;
            if (this.form_date === "") {
                this.errors.date = true;
            }
        },
        validateVendor() {
            this.errors.vendor = false;
            if (this.form_vendor_id === 0) {
                this.errors.vendor = true;
            }
        },

        validateProductsVars(array) {
            this.errors.vars = false;
            this.errors.message = "";

            // Validate at least one product added
            if (array.length < 1) {
                this.errors.vars = true;
                this.errors.message = "Please add Products to PO";
                return;
            }

            //Validate Not empty or null values selected
            for (let i = 0; i < array.length; i++) {
                if (array[i].product_id === 0 || array[i].qty === 0) {
                    this.errors.vars = true;
                    this.errors.message = "Please Select a Product and Qty !";
                    return;
                }
            }

            // validate no duplicate products-barcode in vars
            for (let i = 0; i < array.length; i++) {
                for (let j = 0; j < array.length; j++) {
                    if (
                        i !== j &&
                        array[i].product_id === array[j].product_id &&
                        array[i].batch_number === array[j].batch_number
                    ) {
                        this.errors.vars = true;
                        this.errors.message = "Error, Products are Duplicated ! !";
                        return;
                    }
                }
            }
        },

        addNewRow() {
            let variables = this.vars;
            variables.push({
                product_id: this.product_selected,
                product_name: "",
                qty: this.qty,
                batch_number: this.batch_number.toUpperCase()
            });
        },

        //Methods for Ajax
        fetchProducts() {
            this.loading_products = true;
            axios.get("/get_products").then(response => {
                this.loading_products = false;
                this.products = response.data.products;
            });
        },
        fetchVendors() {
            this.loading_vendors = true;
            axios.get("/get_vendors").then(response => {
                this.vendors = response.data.vendors;
                this.loading_vendors = false;
            });
        },
        fetchCouriers() {
            this.loading_couriers = true;
            axios.get("/get_couriers").then(response => {
                this.couriers = response.data.couriers;
                this.loading_couriers = false;
            });
        },
        fetchProductsDimensions() {
            this.loading_dimensions = true;
            axios.get("/get_dimensions").then(response => {
                this.dimensions = response.data.dimensions;
                this.loading_dimensions = false;
            });
        },
        fetchCategories() {
            this.loading_categories = true;
            axios.get("/get_categories").then(response => {
                this.categories = response.data.categories;
                this.loading_categories = false;
            });
        }
    },

    created() {
        this.fetchProducts();
        this.fetchVendors();
        this.fetchCouriers();
        this.fetchProductsDimensions();
        this.fetchCategories();
        this.addNewRow();

        if (this.props_action) {
            this.action_edit = true;
        }

        if (this.props_products_edit) {
            this.vars = JSON.parse(this.props_products_edit);
        }

        if (this.props_purchase_edit) {
            this.purchase = JSON.parse(this.props_purchase_edit);
            this.form_id = this.purchase.id;
            this.form_name = this.purchase.name;
            this.form_date = this.purchase.date;
            this.form_vendor_id = this.purchase.contact_id;
            this.form_courier_id = this.purchase.courier_id;
            this.form_tracking = this.purchase.tracking;
            this.form_bol = this.purchase.bol;
            this.form_package_list = this.purchase.package_list;
            this.form_reference = this.purchase.reference;
        }
    }
};
</script>
