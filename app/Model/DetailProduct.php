<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DetailProduct extends Model
{
    protected $table = 'detail_product';

    public function product()
    {
        return $this->belongsTo('App\Model\Product', 'product_id');
    }
}
