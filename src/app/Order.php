<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['name', 'client_id', 'courier_id', 'tracking', 'transaction_type_id', 'date', 'reference'];

    public function product(){
        return $this->hasMany('App\Product');
    }

    public function purchases(){
        return $this->hasMany('App\PurchasesItems');
    }
}



