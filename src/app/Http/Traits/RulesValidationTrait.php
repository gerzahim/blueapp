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

}
