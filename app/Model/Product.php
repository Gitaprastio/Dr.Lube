<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function detail(){
        return hasOne('App\Model\DetailProduct');
    }

    public function agreement()
    {
        return $this->hasMany('App\Model\ListItem', 'product_id', 'id');
    }
}
