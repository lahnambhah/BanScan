<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class BarcodeController extends Controller
{
    public function download($id)
    {
        $item = Item::findOrFail($id);

        $barcode = DNS1D::getBarcodeSVG($item->barcode, 'C128');

        return response($barcode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="barcode-'.$item->id.'.svg"');
    }

    public function export(Request $request)
    {
        $data = json_decode($request->items, true);

        $items = collect();

        foreach ($data as $entry) {

            $item = Item::find($entry['id']);

            if (!$item) continue;

            for ($i = 0; $i < $entry['qty']; $i++) {

                $items->push($item);

            }

        }

        return view('barcodes.export-a4', compact('items'));
    }
}