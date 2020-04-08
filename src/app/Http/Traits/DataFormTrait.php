<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait DataFormTrait {

    public function getTime(){
        return date('Y-m-d H:i:s');
    }

    // Purchases
    public function setDataPurchase( Request $request){
        $data = [
            'name'                => $request->name,
            'contact_type_id'     => 1,
            'contact_id'          => $request->vendor_id,
            'courier_id'          => $request->courier_id,
            'tracking'            => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            'bol'                 => $request->bol,
            'package_list'        => $request->package_list,
            'date'                => $request->date,
            'reference'           => $request->reference,
            'created_at'          => $this->getTime(),
            'updated_at'          => $this->getTime()
        ];
        return $data;
    }

    public function setDataPurchaseItems($purchases_id, $po_line){
        $data = [
            'purchases_id' => $purchases_id,
            'product_id'   => $po_line->product_id,
            'qty'          => $po_line->qty,
            'batch_number' => $po_line->batch_number,
            'created_at'   => $this->getTime(),
            'updated_at'   => $this->getTime()
        ];
        return $data;
    }
}
