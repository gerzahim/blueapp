<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefurbishItems extends Model
{
    protected $table = 'refurbish_items';

    protected $fillable = ['refurbish_id','product_id','qty','purchases_id'];
}
