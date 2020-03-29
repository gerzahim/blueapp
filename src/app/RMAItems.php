<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RMAItems extends Model
{
    protected $table = 'rma_items';

    protected $fillable = ['rma_id','product_id','qty','order_id','purchases_id'];
}
