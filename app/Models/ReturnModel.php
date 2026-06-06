<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'customer_phone',
        'total'
    ];

    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }
}