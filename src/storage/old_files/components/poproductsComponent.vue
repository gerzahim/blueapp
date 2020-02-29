<template>
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header">
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-6">
                        <h4 class="mb-0 mt-2">Select Products</h4>
                    </div>
                    <!-- Column -->
                    <div class="col-md-6">
                        <div class="float-right">
                            <a v-on:click="insertVarLine();" class="btn btn-success">Add</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body">


                <div v-for="(variable, key) in vars" :key="key" class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Product </label>
                            <select id="product_id[]" name="product_id[]" class="form-control" >
                                <option v-for="product in products" v-bind:value="product.id" v-text="product.name"></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" class="form-control" id="qty[]" v-model="variable.qty" name="qty[]" min="1" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Batch</label>
                            <input type="text" class="form-control" id="batch_number[]" v-model="variable.batch_number" name="batch_number[]" placeholder="XFR4487...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Action</label><br>
                            <button type="button" class="btn btn-danger" @click="$delete(vars, key)"><i class="fa fa-times-circle"></i></button>
                            <!--
                            <a v-on:click="removeElement(index);" class="btn btn-danger text-white">Remove</a>
                            -->
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                id: 0,
                modalShown: false,
                accessView: false,
                ajax: false,
                name: '',
                products: [],
                vars: [{'qty': '1', 'batch_number': ''}],
                empty: false,
            }
        },
        methods: {
            insertVarLine() {
                let variables = this.vars
                variables.push({'qty': '1', 'batch_number': ''})
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
            this.fetchProducts()
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
