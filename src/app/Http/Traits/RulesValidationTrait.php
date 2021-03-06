<?php

namespace App\Http\Traits;
use Illuminate\Http\Request;

trait RulesValidationTrait {

    // Purchases
    public function getRulesValidationPO(Request $request, $is_update){
        $rules = [
            'name'                => 'required|unique:purchases|max:50',
            'transaction_type_id' => 'required',
            'vendor_id'           => 'required',
            'date'                => 'required',
        ];
        if ($is_update) {
            $rules['name'] = 'required|max:50|unique:purchases,name,'.$request->id;
        }
        return $rules;
    }

    // Orders
    public function getRulesValidationOrders($is_update){
        $rules = [
            'name'                => 'required',
            'date'                => 'required',
            'transaction_type_id' => 'required',
            'client_id'           => 'required'
        ];
        if ($is_update) {
            unset($rules['name']);
        }
        return $rules;
    }

    // RMAs
    public function getRulesValidationRMAs($is_vendor, $is_update){
        $rules = [
            'name'                => 'required',
            'date'                => 'required',
            'transaction_type_id' => 'required',
            'client_id'           => 'required'
        ];

        if($is_vendor) {
            unset($rules['client_id']);
            $rules['vendor_id'] = 'required';
        }

        if ($is_update) {
            unset($rules['name']);
        }
        return $rules;
    }

    // Refurbishes
    public function getRulesValidationRefurbishes(){
        return [
            'name' => 'required',
            'date' => 'required',
        ];
    }

}
