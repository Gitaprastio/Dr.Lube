<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $table = 'agreements_list_items';

    public function agreement()
    {
        return $this->belongsTo('App\Model\ProductAgreement', 'product_agreement_id');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Model\Product', 'product_id');
    }

    public function purchaseItem()
    {
        return $this->hasMany('App\Model\PurchaseItem', 'list_item_id', 'id');
    }
}
