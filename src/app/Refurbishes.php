<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refurbishes extends Model
{
    protected $table = 'refurbishes';

    protected $fillable = [ 'name', 'transaction_type_id', 'date', 'reference'];
}
