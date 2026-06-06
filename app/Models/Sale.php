<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['receipt_no', 'customer_phone', 'total'];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
