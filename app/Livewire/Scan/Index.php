<?php

namespace App\Livewire\Scan;

use Livewire\Component;
use App\Models\Item;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\SaleNotification;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

#[Layout('layouts.app')]
class Index extends Component
{
    public $barcodeInput = '';
    public $cart = [];
    public $customerPhone = '';
    public $lastSaleId = null;
    public $showSuccess = false;
    public $lastScanned = '';
    public $searchItem = '';
    public $manualName = '';
    public $manualPrice = '';
    public $manualQty = 1;

    // LOOKUP BARCODE
    public function lookup()
    {
        $code = trim($this->barcodeInput);

        // EMPTY BARCODE
        if (!$code) {
            $this->addError(
                'barcodeInput',
                'Please scan or enter a barcode.'
            );
            return;
        }

        $this->resetErrorBag('barcodeInput');
        $this->addToCart($code);
        $this->reset('barcodeInput');

        // autofocus again
        $this->dispatch('focusScanner');

        if ($code === $this->lastScanned) {
            return;
        }

        $this->lastScanned = $code;
    }

    // ADD TO CART
    private function addToCart($code)
    {
        $item = Item::where('barcode', $code)->first();

        // INVALID BARCODE
        if (!$item) {
            $this->addError(
                'barcodeInput',
                'Barcode not found.'
            );
            return;
        }

        // OUT OF STOCK
        if ($item->stock <= 0) {
            $this->addError(
                'barcodeInput',
                'Item is out of stock.'
            );
            return;
        }

        // EXISTING ITEM
        if (isset($this->cart[$code])) {
            // EXCEED STOCK
            if ($this->cart[$code]['qty'] >= $item->stock) {
                $this->addError(
                    'barcodeInput',
                    'Cannot exceed available stock.'
                );
                return;
            }
            $this->cart[$code]['qty']++;
        } else {

            $this->cart[$code] = [
                'id' => $item->id,
                'name' => $item->name,
                'barcode' => $item->barcode,
                'price' => $item->price,
                'qty' => 1
            ];
        }
    }

    // MANUAL ADD ITEM
    public function addManualItem($id)
    {
        $item = Item::find($id);

        if (!$item) {

            $this->addError(
                'searchItem',
                'Item not found.'
            );

            return;
        }

        if ($item->stock <= 0) {

            $this->addError(
                'searchItem',
                'Item is out of stock.'
            );

            return;
        }

        $this->addToCart($item->barcode);

        // CLEAR SEARCH
        $this->searchItem = '';

        $this->dispatch('focusScanner');
    }

    // CUSTOM MANUAL ITEM
    public function addCustomItem()
    {
        $this->validate([
            'manualName' => 'required|min:2',
            'manualPrice' => 'required|numeric|min:1',
            'manualQty' => 'required|numeric|min:1',

        ], [

            'manualName.required' => 'Item name is required.',
            'manualName.min' => 'Item name is too short.',

            'manualPrice.required' => 'Price is required.',
            'manualPrice.numeric' => 'Price must be a number.',
            'manualPrice.min' => 'Price must be greater than 0.',

            'manualQty.required' => 'Quantity is required.',
            'manualQty.numeric' => 'Quantity must be a number.',
            'manualQty.min' => 'Quantity must be at least 1.',

        ]);

        $key = 'manual_' . uniqid();

        $this->cart[$key] = [

            'id' => null,
            'name' => $this->manualName,
            'barcode' => 'MANUAL ITEM',
            'price' => $this->manualPrice,
            'qty' => $this->manualQty,
            'manual' => true,
        ];

        // RESET
        $this->reset([
            'manualName',
            'manualPrice',
            'manualQty'
        ]);
    }

    // INCREASE
    public function increase($code)
    {
        $item = Item::find($this->cart[$code]['id']);

        if (!$item) {
            return;
        }

        if ($this->cart[$code]['qty'] >= $item->stock) {
            $this->addError(
                'barcodeInput',
                'Cannot exceed available stock.'
            );
            return;
        }
        $this->cart[$code]['qty']++;
    }

    // DECREASE
    public function decrease($code)
    {
        if ($this->cart[$code]['qty'] > 1) {
            $this->cart[$code]['qty']--;
        }
    }

    // UPDATE QTY
    public function updateQty($code, $qty)
    {
        if (!isset($this->cart[$code])) {
            return;
        }

        $qty = (int) $qty;

        if ($qty < 1) {
            $qty = 1;
        }

        // Custom item
        if (isset($this->cart[$code]['manual'])) {
            $this->cart[$code]['qty'] = $qty;
            return;
        }

        $item = Item::find($this->cart[$code]['id']);

        if (!$item) {
            return;
        }

        if ($qty > $item->stock) {
            $qty = $item->stock;
        }

        $this->cart[$code]['qty'] = $qty;
    }

    // REMOVE
    public function remove($code)
    {
        unset($this->cart[$code]);
    }

    // TOTAL
    public function getTotalProperty()
    {
        return collect($this->cart)
            ->sum(fn($i) => $i['price'] * $i['qty']);
    }

    // COMPLETE SALE
    public function completeSale()
    {
        // EMPTY CART
        if (empty($this->cart)) {
            $this->addError(
                'barcodeInput',
                'Cart is empty.'
            );
            return;
        }

        // PHONE VALIDATION
        $this->validate([
            'customerPhone' => 'nullable|numeric|digits:10',
        ], [
            'customerPhone.numeric' => 'Phone number must contain only digits.',
            'customerPhone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        // UNIQUE RECEIPT
        $receiptNo = 'BS-' . strtoupper(Str::random(8));

        $sale = Sale::create([
            'receipt_no' => $receiptNo,
            'customer_phone' => $this->customerPhone,
            'total' => $this->total,
        ]);

        // SALES NOTIFICATION
        $salesUsers = User::where(
            'notify_sales',
            true
        )->get();

        Notification::send(
            $salesUsers,
            new SaleNotification($sale)
        );

        // SAVE ITEMS
        foreach ($this->cart as $item) {

    // CUSTOM ITEM
    if (!$item['id']) {

        SaleItem::create([
            'sale_id' => $sale->id,
            'item_id' => null,
            'custom_name' => $item['name'],
            'is_custom' => true,
            'quantity' => $item['qty'],
            'price' => $item['price'],
        ]);

        continue;
    }

    $dbItem = Item::find($item['id']);

            // EXTRA SAFETY CHECK
            if ($dbItem->stock < $item['qty']) {
                $this->addError(
                    'barcodeInput',
                    $dbItem->name . ' does not have enough stock.'
                );
                return;
            }

            SaleItem::create([
                'sale_id' => $sale->id,
                'item_id' => $dbItem->id,
                'quantity' => $item['qty'],
                'price' => $item['price'],
            ]);

            $dbItem->decrement(
                'stock',
                $item['qty']
            );

            // LOW STOCK
            $dbItem->refresh();

            if ($dbItem->stock <= $dbItem->low_stock_limit) {
                $lowStockUsers = User::where(
                    'notify_low_stock',
                    true
                )->get();

                Notification::send(
                    $lowStockUsers,
                    new LowStockNotification($dbItem)
                );
            }
        }

        // SUCCESS
        $this->lastSaleId = $sale->id;
        $this->cart = [];
        $this->customerPhone = '';
        $this->showSuccess = true;
        $this->dispatch('focusScanner');
    }

    // PROCESS USB SCAN
    public function processScan()
    {
        $this->lookup();
        $this->barcodeInput = '';
        $this->dispatch('clearBarcodeInput');
    }

    public function render()
    {
        return view('livewire.scan.index', [

            'searchResults' => Item::where(
                    'name',
                    'like',
                    '%' . $this->searchItem . '%'
                )
                ->where('stock', '>', 0)
                ->take(8)
                ->get()

        ]);
    }
}