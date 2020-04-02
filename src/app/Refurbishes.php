<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refurbishes extends Model
{
    protected $table = 'refurbishes';

    protected $fillable = [ 'name', 'contact_type_id', 'contact_id', 'courier_id', 'tracking', 'transaction_type_id', 'date', 'reference'];
}
