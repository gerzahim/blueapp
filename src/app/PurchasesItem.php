<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchasesItem extends Model
{
    protected $fillable = ['purchases_id','product_id','qty','batch_number'];
}
