<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';
    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }
    public function list_item()
    {
        return $this->hasMany('App\Model\PurchaseItem', 'purchase_order_id', 'id');
    }
    public function invoices()
    {
        return $this->hasMany('App\Model\Invoice', 'purchase_order_id', 'id');
    }
}
