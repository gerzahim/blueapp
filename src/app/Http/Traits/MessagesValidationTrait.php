<?php

namespace App\Http\Traits;


trait MessagesValidationTrait {

    // Purchases
    public function getMessagesValidationPO(){
        $messages = [
            'name.unique'                     => 'The PO Name attribute has already been taken.',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'vendor_id.required'              => 'Must Select a Supplier',
            'date.required'                   => 'Must Select a Date',
        ];
        return $messages;
    }

}
