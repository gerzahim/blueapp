<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RMA extends Model
{
    protected $table = 'rmas';

    protected $fillable = [ 'name', 'contact_type_id', 'contact_id', 'courier_id', 'tracking', 'transaction_type_id', 'date', 'reference'];

    public function product(){
        return $this->hasMany('App\Product');
    }

    public function purchases(){
        return $this->hasMany('App\PurchasesItems');
    }

    public function orders(){
        return $this->hasMany('App\Order');
    }

    public function orders_items(){
        return $this->hasMany('App\OrderItems');
    }
}
