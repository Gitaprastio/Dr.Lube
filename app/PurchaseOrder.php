<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';
    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }
}
