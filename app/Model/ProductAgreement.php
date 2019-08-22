<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductAgreement extends Model
{
    protected $table = 'product_agreements';

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'users_id');
    }

    public function items()
    {
        return $this->hasMany('App\Model\ListItem', 'product_agreement_id', 'id');
    }
}
