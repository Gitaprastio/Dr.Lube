<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    public function purchaseOrder()
    {
        return $this->belongsTo('App\Model\PurchaseOrder', 'purchase_order_id');
    }
}
