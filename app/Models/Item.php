<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
    'name',
    'category_id',
    'supplier_id',
    'price',
    'stock',
    'barcode',
    'manufactured_at', 
    'expiry_date',
    'low_stock_limit',
];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}