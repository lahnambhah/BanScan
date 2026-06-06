<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewItemNotification;

#[Layout('layouts.app')]
class Create extends Component
{
    public $name;
    public $category_id;
    public $supplier_id;
    public $stock;
    public $price;
    public $barcode;
    public $manufactured_at;
    public $expiry_date;
    public $low_stock_limit;

    public function generateBarcode()
    {
        $this->barcode = '20' . rand(100000000, 999999999);
    }

    public function save()
    {
        $this->validate([

            'name' => 'required|min:2|unique:items,name',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'stock' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:1',
            'barcode' => 'required|unique:items,barcode',
            'manufactured_at' => 'nullable|date|before_or_equal:today',
            'expiry_date' => 'nullable|date|after:manufactured_at',
            'low_stock_limit' => 'required|integer|min:1',

        ], [

            // NAME
            'name.required' => 'Item name is required.',
            'name.min' => 'Item name must be at least 2 characters.',
            'name.unique' => 'This item already exists.',

            // CATEGORY
            'category_id.required' => 'Please select a category.',

            // SUPPLIER
            'supplier_id.required' => 'Please select a supplier.',

            // STOCK
            'stock.required' => 'Quantity is required.',
            'stock.numeric' => 'Quantity must be a number.',
            'stock.min' => 'Quantity cannot be negative.',

            // PRICE
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be greater than 0.',

            // BARCODE
            'barcode.required' => 'Barcode is required.',
            'barcode.unique' => 'This barcode already exists.',

            // DATES
            'manufactured_at.date' => 'Manufactured date is invalid.',
            'manufactured_at.before_or_equal' => 'Manufactured date cannot be in the future.',
            'expiry_date.date' => 'Expiry date is invalid.',
            'expiry_date.after' => 'Expiry date must be after manufactured date.',
            
            // LOW STOCK LIMIT
            'low_stock_limit.required' => 'Low stock alert level is required.',
            'low_stock_limit.integer' => 'Low stock alert level must be an integer.',
            'low_stock_limit.min' => 'Low stock alert level must be at least 1.',

        ]);

        $item = Item::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'stock' => $this->stock,
            'price' => $this->price,
            'barcode' => $this->barcode,
            'manufactured_at' => $this->manufactured_at,
            'expiry_date' => $this->expiry_date,
            'low_stock_limit' => $this->low_stock_limit,
        ]);

        $users = User::where('notify_new_items', true)->get();
        Notification::send($users, new NewItemNotification($item));

        session()->flash('success', 'Item created successfully');

        return redirect('/items');
    }

    public function render()
    {
        return view('livewire.items.create', [
            'categories' => Category::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    public function startScan()
    {
        $this->dispatch('startScanner');
    }
}