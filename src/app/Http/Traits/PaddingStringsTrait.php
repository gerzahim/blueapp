<?php

namespace App\Http\Traits;

trait PaddingStringsTrait {

    /**
     * @param $product
     * @param $po
     * @param $available
     * @return string
     */
    public function formatPadString($product, $po, $available){

        $product = substr($product, 0, 15);
        $product = str_pad($product, 15, '.' , STR_PAD_RIGHT);

        $po = substr($po, 0, 14);
        $po = str_pad($po, 14, '.' , STR_PAD_RIGHT);

        return "${product} | ${po} | Av (${available})";
    }

    /**
     * @param $product
     * @param $po
     * @param $order_id
     * @return string
     */
    public function formatPadString2($product, $po, $order_id, $available){

        $product = substr($product, 0, 15);
        $product = str_pad($product, 15, '.' , STR_PAD_RIGHT);

        $po = substr($po, 0, 14);
        $po = str_pad($po, 14, '.' , STR_PAD_RIGHT);

        return "${product} | ${po} | Order # ${order_id} | Qty (${available})";
    }

    /**
     * @param $product
     * @param $po
     * @param $available
     * @return string
     */
    public function formatPadString3($product, $po, $available){

        $product = substr($product, 0, 15);
        $product = str_pad($product, 15, '.' , STR_PAD_RIGHT);

        $po = substr($po, 0, 14);
        $po = str_pad($po, 14, '.' , STR_PAD_RIGHT);

        return "${product} | ${po} | Av (${available})";
    }



}
