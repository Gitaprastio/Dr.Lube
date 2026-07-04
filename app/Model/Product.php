<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function detail(){
        return $this->hasOne('App\Model\DetailProduct', 'product_id', 'id');
    }

    public function agreement()
    {
        return $this->hasMany('App\Model\ListItem', 'product_id', 'id');
    }
}
