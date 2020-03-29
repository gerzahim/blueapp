<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RMAItems extends Model
{
    protected $fillable = ['rma_id','product_id','qty','order_id','purchases_id'];
}
