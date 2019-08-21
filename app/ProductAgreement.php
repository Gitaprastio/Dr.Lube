<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAgreement extends Model
{
    protected $table = 'product_agreements';

    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

    public function items()
    {
        return $this->hasMany('App\ListItem', 'products_id', 'id');
    }
}
