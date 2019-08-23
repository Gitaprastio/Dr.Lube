<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Model\PurchaseOrder', 'purchase_order_id');
    }

    public function listItem()
    {
        return $this->belongsTo('App\Model\ListItem', 'list_item_id');
    }
}
