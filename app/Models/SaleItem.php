<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'item_id',
        'quantity',
        'price',
        'custom_name',
        'is_custom',
    ];

    // ITEM
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // SALE 
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}