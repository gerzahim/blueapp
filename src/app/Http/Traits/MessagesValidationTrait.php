<?php

namespace App\Http\Traits;


trait MessagesValidationTrait {

    // Purchases
    public function getMessagesValidationPO(){
        return [
            'name.unique'                  => 'The PO Name attribute has already been taken.',
            'transaction_type_id.required' => 'The Transaction Type must be Selected.',
            'vendor_id.required'           => 'Must Select a Supplier',
            'date.required'                => 'Must Select a Date',
        ];
    }

    // Orders
    public function getMessagesValidationOrders(){
        return [
            'name.required'                => 'The Order Number attribute has already been taken.',
            'date.required'                => 'Must Select a Date',
            'transaction_type_id.required' => 'The Transaction Type must be Selected.',
            'client_id.required'           => 'Must Select a Customer',
        ];
    }


    // RMAs
    public function getMessagesValidationRMAs(){
        return [
            'name.required'                => 'The RMA is Required.',
            'date.required'                => 'Must Select a Date',
            'transaction_type_id.required' => 'The Transaction Type must be Selected.',
            'client_id.required'           => 'Must Select a Customer',
            'vendor_id.required'           => 'Must Select a Supplier',
        ];
    }

    // Refurbishes
    public function getMessagesValidationRefurbishes(){
        return [
            'name.required' => 'The Refurbished Number is Required.',
            'date.required' => 'Must Select a Date',
        ];
    }


}
