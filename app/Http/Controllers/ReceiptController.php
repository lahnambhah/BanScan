<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class ReceiptController extends Controller
{
    public function print(Sale $sale)
    {
        $sale->load('items.item');

        return view('receipt.print', compact('sale'));
    }
}