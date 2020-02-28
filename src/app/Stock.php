<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['purchases_id', 'product_id', 'purchased', 'sold', 'qoh', 'on_hold', 'available', 'rma', 'refurbished', 'lended'];
}
