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
        $items = collect();

        // NEW LIVEWIRE VERSION
        if ($request->filled('ids')) {

            $ids = explode(',', $request->ids);

            $items = Item::whereIn('id', $ids)->get();

            return view('barcodes.export-a4', compact('items'));
        }

        // OLD JS VERSION
        $data = json_decode($request->items, true);

        if (!$data) {
            return back();
        }

        foreach ($data as $entry) {

            $item = Item::find($entry['id']);

            if (!$item) {
                continue;
            }

            for ($i = 0; $i < ($entry['qty'] ?? 1); $i++) {

                $items->push($item);

            }
        }

        return view('barcodes.export-a4', compact('items'));
    }
}