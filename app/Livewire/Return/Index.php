<?php

namespace App\Livewire\Return;

use Livewire\Component;
use App\Models\Item;
use App\Models\Sale;
use App\Models\ReturnModel;
use App\Models\ReturnItem;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;
    public $receiptNo;
    public $salesItems = [];
    public $cart = [];
    public $filterReceipt = '';
    public $filterDate = [];


    // LOAD SALES
    public function loadSales()
    {
        $sales = Sale::where('receipt_no', $this->filterReceipt)
            ->with('items.item')
            ->get();

        if ($sales->isEmpty()) {
            session()->flash('error', 'No sales found');
            return;
        }

        $this->salesItems = [];

        foreach ($sales as $sale) {

            foreach ($sale->items as $saleItem) {

                $returnedQty = ReturnItem::where(
                    'sale_item_id',
                    $saleItem->id
                )->sum('quantity');

                $remaining = $saleItem->quantity - $returnedQty;

                if ($remaining <= 0) {
                    continue;
                }

                $this->salesItems[] = [

                    'id' => $saleItem->item_id,

                    'sale_item_id' => $saleItem->id,

                    'name' => $saleItem->is_custom
                        ? $saleItem->custom_name
                        : ($saleItem->item->name ?? 'Deleted Item'),

                    'barcode' => $saleItem->is_custom
                        ? 'CUSTOM-' . $saleItem->id
                        : ($saleItem->item->barcode ?? 'N/A'),

                    'price' => $saleItem->price,

                    'sold_qty' => $saleItem->quantity,

                    'returned_qty' => $returnedQty,

                    'remaining_qty' => $remaining,

                    'is_custom' => $saleItem->is_custom,
                ];
            }
        }
    }

    // ADD TO CART (STRICT CHECK)
    public function addFromSales($saleItemId)
    {
        $saleItem = collect($this->salesItems)
            ->firstWhere('sale_item_id', $saleItemId);

        if (!$saleItem) {
            return;
        }

        $cartKey = $saleItem['sale_item_id'];

        $currentQty = $this->cart[$cartKey]['qty'] ?? 0;

        $returnedQty = ReturnItem::where(
            'sale_item_id',
            $saleItem['sale_item_id']
        )->sum('quantity');

        if (($returnedQty + $currentQty + 1) > $saleItem['sold_qty']) {
            session()->flash('error', 'Already fully returned');
            return;
        }

        if (isset($this->cart[$cartKey])) {

            $this->cart[$cartKey]['qty']++;

        } else {

            $this->cart[$cartKey] = [
                'id' => $saleItem['id'],
                'sale_item_id' => $saleItem['sale_item_id'],
                'name' => $saleItem['name'],
                'barcode' => $saleItem['barcode'],
                'price' => $saleItem['price'],
                'qty' => 1,
                'is_custom' => $saleItem['is_custom'],
            ];
        }
    }

    // CART CONTROLS
    public function increase($key)
    {
        if (!isset($this->cart[$key])) {
            return;
        }

        $item = $this->cart[$key];

        $returnedQty = ReturnItem::where(
            'sale_item_id',
            $item['sale_item_id']
        )->sum('quantity');

        $soldQty = collect($this->salesItems)
            ->firstWhere(
                'sale_item_id',
                $item['sale_item_id']
            )['sold_qty'] ?? 0;

        if (($returnedQty + $item['qty'] + 1) > $soldQty) {

            session()->flash(
                'error',
                'Max return reached'
            );

            return;
        }

        $this->cart[$key]['qty']++;
    }

    public function decrease($key)
    {
        if (!isset($this->cart[$key])) {
            return;
        }

        if ($this->cart[$key]['qty'] > 1) {
            $this->cart[$key]['qty']--;
        }
        session()->flash('error', 'Minimum quantity is 1. Remove item to delete from cart.');
    }

    public function remove($id)
    {
        unset($this->cart[$id]);
    }

    // TOTAL
    public function getTotalProperty()
    {
        return collect($this->cart)
            ->sum(fn($item) => $item['price'] * $item['qty']);
    }

    // COMPLETE RETURN
    public function completeReturn()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart empty');
            return;
        }

        $return = ReturnModel::create([
            'customer_phone' => Sale::where('receipt_no', $this->filterReceipt)->first()?->customer_phone,
            'total' => $this->total,
        ]);

        foreach ($this->cart as $item) {

        ReturnItem::create([
            'return_id' => $return->id,
            'sale_item_id' => $item['sale_item_id'], 
            'item_id' => $item['id'],
            'quantity' => $item['qty'],
            'price' => $item['price'],
        ]);

        if (!empty($item['id'])) {
            Item::find($item['id'])
                ?->increment('stock', $item['qty']);
        }
    }

        // RESET BUT KEEP PHONE
        $this->cart = [];

        // reload updated data
        $this->loadSales();
        return redirect()->route('return.receipt', $return->id);
    }

    // LOAD RETURN HISTORY
    public function loadReturns()
    {
        $query = ReturnModel::with('items.item')
            ->whereHas('items');

        // FILTER RECEIPT
        if ($this->filterReceipt) {

            $query->whereHas('items', function ($q) {

                $q->whereHas('saleItem', function ($q2) {

                    $q2->whereHas('sale', function ($q3) {

                        $q3->where(
                            'receipt_no',
                            'like',
                            '%' . $this->filterReceipt . '%'
                        );

                    });

                });

            });
        }

        // FILTER DATE
        if ($this->filterDate) {

            $query->whereDate(
                'created_at',
                $this->filterDate
            );
        }

        return $query
            ->latest()
            ->paginate(10);
    }

    public function updatingFilterReceipt()
    {
        $this->resetPage();
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.return.index', [

            'returnHistory' => $this->loadReturns()

        ]);
    }
}