<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    protected $fillable = [ 'id', 'name', 'contact_type_id', 'contact_id', 'courier_id', 'tracking', 'transaction_type_id', 'bol', 'package_list', 'reference'];

}
