<?php

namespace App\Http\Controllers;

use App\Models\ReturnModel;

class ReturnController extends Controller
{
    public function receipt($id)
    {
        $return = ReturnModel::with('items.item')->findOrFail($id);

        return view('returns.receipt', compact('return'));
    }
}