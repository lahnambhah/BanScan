<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;

#[Layout('layouts.app')]

class Edit extends Component
{
    public $itemId;

    public $name;
    public $category_id;
    public $supplier_id;
    public $stock;
    public $price;
    public $manufactured_at;
    public $expiry_date;
    public $low_stock_limit;
    public $barcode;
    public function mount($id)
    {
        $item = Item::findOrFail($id);

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->category_id = $item->category_id;
        $this->supplier_id = $item->supplier_id;
        $this->stock = $item->stock;
        $this->price = $item->price;
        $this->manufactured_at = $item->manufactured_at;
        $this->expiry_date = $item->expiry_date;
        $this->low_stock_limit = $item->low_stock_limit;
        $this->barcode = $item->barcode;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:2|unique:items,name,' . $this->itemId,
            'category_id' => 'required',
            'supplier_id' => 'required',
            'stock' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:1',
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

            // DATES
            'manufactured_at.required' => 'Manufactured date is required.',
            'manufactured_at.date' => 'Manufactured date is invalid.',

            'expiry_date.required' => 'Expiry date is required.',
            'expiry_date.date' => 'Expiry date is invalid.',
            'expiry_date.after_or_equal' => 'Expiry date must be after or equal to manufactured date.',
            
            // LOW STOCK LIMIT
            'low_stock_limit.required' => 'Low stock alert level is required.',
            'low_stock_limit.integer' => 'Low stock alert level must be an integer.',
            'low_stock_limit.min' => 'Low stock alert level must be at least 1.',

        ]);

        Item::find($this->itemId)->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'stock' => $this->stock,
            'price' => $this->price,
            'low_stock_limit' => $this->low_stock_limit
        ]);

        session()->flash('success','Item updated successfully');

        return redirect('/items');
    }

    public function render()
    {
        return view('livewire.items.edit',[
            'categories' => Category::all(),
            'suppliers' => Supplier::all()
        ]);
    }
}