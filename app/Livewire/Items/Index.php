<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Item;

#[Layout('layouts.app')]

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all';

    public function render()
    {
        $query = Item::with(['category','supplier']);
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->status === 'instock') {
            $query->where('stock', '>', 10);
        } elseif ($this->status === 'lowstock') {
            $query->whereBetween('stock', [1, 10]);
        } elseif ($this->status === 'outstock') {
            $query->where('stock', 0);
        }

        return view('livewire.items.index', [
            'items' => $query->latest()->paginate(10)
        ]);
    }

    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        $this->dispatch('$refresh');
        session()->flash('success', 'Item deleted successfully');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }
}