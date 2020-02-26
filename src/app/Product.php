<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'code', 'description', 'dimensions_id', 'category_id', 'image'];

    /*
    public function category(){
        return $this->belongsTo('Inventory\Models\ProductCategory');
    }
    public function scopeOrdered($query){
        return $query->orderBy('created_at', 'desc')->get();
    } 
    */
}
