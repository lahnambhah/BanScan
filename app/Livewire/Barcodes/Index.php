<?php

namespace App\Livewire\Barcodes;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Item;

#[Layout('layouts.app')]
class Index extends Component
{
    protected $listeners = ['refresh'];
    public $search = '';

    public $selected = [];
    public function render()
    {
        $items = Item::with('category')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();

        return view('livewire.barcodes.index', [
            'items' => $items
        ]);
    }

    public function downloadSelected()
    {
        $ids = implode(',', $this->selected);

        $this->dispatch('openBarcodePrint', url('/barcodes/export?ids=' . $ids));

        $this->selected = [];
    }
}