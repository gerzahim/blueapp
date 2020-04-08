<?php

namespace App\Http\Traits;


trait MessagesValidationTrait {

    // Purchases
    public function getMessagesValidationPO(){
        return [
            'name.unique'                     => 'The PO Name attribute has already been taken.',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'vendor_id.required'              => 'Must Select a Supplier',
            'date.required'                   => 'Must Select a Date',
        ];
    }

    // Orders
    public function getMessagesValidationOrders(){
        return [
            'name.required'                   => 'The Order Number attribute has already been taken.',
            'date.required'                   => 'Must Select a Date',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'client_id.required'              => 'Must Select a Customer',
        ];
    }


}
