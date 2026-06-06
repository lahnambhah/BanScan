<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $fillable = [
        'return_id',
        'sale_item_id',
        'item_id',
        'quantity',
        'price',
    ];

    // RETURN
    public function return()
    {
        return $this->belongsTo(ReturnModel::class);
    }

    // ITEM
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // SALE ITEM
    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }
}