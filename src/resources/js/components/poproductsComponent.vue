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
                            <select id="product_id[]" name="product_id[]" class="selectpicker form-control" >
                                <option value="1">Product 1</option>
                                <!--
                                @foreach($products as $product)
                                    <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                @endforeach                                                 
                                -->
                            </select>  
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" class="form-control" id="qty_product[]" v-model="variable.varname" name="qty_product[]" min="1" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Batch</label>
                            <input type="text" class="form-control" id="batch[]" v-model="variable.value" name="batch[]" placeholder="XFR4487...">
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
                vars: [{'varname': '', 'value': ''}],
                empty: false,
            }
        },
        methods: {
            insertVarLine() {
                let variables = this.vars
                variables.push({'varname': '', 'value': ''})
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
