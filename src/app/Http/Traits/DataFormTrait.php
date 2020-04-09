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

    /**
     *
     * @param $purchases_id
     * @param $po_line
     * @return array
     */
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


    // Orders
    /**
     * @param Request $request
     * @param bool $is_update
     * @return array
     */
    public function setDataOrder( Request $request, $is_update = false){

        $data = [
            'name'                => $request->name,
            'client_id'           => $request->client_id,
            'courier_id'          => $request->courier_id,
            'tracking'            => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            'date'                => $request->date,
            'reference'           => $request->reference,
            'created_at'          => $this->getTime(),
            'updated_at'          => $this->getTime()
        ];
        if ($is_update) {
            unset($data['name']);
        }
        return $data;
    }

    /**
     *
     * @param $purchases_id
     * @param $po_line
     * @return array
     */
    public function setDataOrderItems($order_id, $order_line){
        return [
            'order_id'     => $order_id,
            'purchases_id' => $order_line->po_item_id,
            'qty'          => $order_line->qty,
            'created_at'   => $this->getTime(),
            'updated_at'   => $this->getTime()
        ];
    }


    // RMAs
    /**
     * @param Request $request
     * @param bool $is_update
     * @return array
     */
    public function setDataRMA( Request $request, $contact_id, $is_update = false){
        $data = [
            'name'                => $request->name,
            'contact_type_id'     => $request->contact_type_id,
            'contact_id'          => $contact_id,
            'transaction_type_id' => $request->transaction_type_id,
            'courier_id'          => $request->courier_id,
            'tracking'            => $request->tracking,
            'date'                => $request->date,
            'reference'           => $request->reference,
            'created_at'          => $this->getTime(),
            'updated_at'          => $this->getTime()
        ];
        if ($is_update) {
            unset($data['name']);
        }
        return $data;
    }

    /**
     *
     * @param $purchases_id
     * @param $po_line
     * @return array
     */
    public function setDataRMAItems($rma_id, $rma_line){
        return [
            'rma_id'       => $rma_id,
            'product_id'   => $rma_line->product_id,
            'qty'          => $rma_line->qty,
            'order_id'     => $rma_line->order_id,
            'purchases_id' => $rma_line->po_item_id,
            'created_at'   => $this->getTime(),
            'updated_at'   => $this->getTime()
        ];
    }



}
