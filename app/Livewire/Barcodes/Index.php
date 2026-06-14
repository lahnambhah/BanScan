<?php

namespace App\Livewire\Barcodes;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Item;
use Livewire\WithPagination;
use App\Models\Category;

#[Layout('layouts.app')]
class Index extends Component
{
    protected $listeners = ['refresh'];
    
    use WithPagination;
    public $search = '';
    public $category = '';
    public $selected = [];

    public function render()
    {
        $items = Item::with('category')

        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('barcode', 'like', "%{$this->search}%");
            });
        })

        ->when($this->category, function ($query) {
            $query->where('category_id', $this->category);
        })

        ->latest()

        ->paginate(24);

        return view('livewire.barcodes.index', [
            'items' => $items,
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    public function toggleSelectAll()
    {
        $items = Item::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        })->pluck('id');

        if (count($this->selected) === $items->count()) {
            $this->selected = [];
        } else {
            $this->selected = $items->toArray();
        }
    }

    public function downloadSelected()
    {
        if (empty($this->selected)) {

            $this->dispatch(
                'barcodeError',
                message: 'Please select at least one barcode to download.'
            );

            return;
        }

        $ids = implode(',', $this->selected);

        $this->dispatch(
            'openBarcodePrint',
            url('/barcodes/export?ids=' . $ids)
        );
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
    }
}