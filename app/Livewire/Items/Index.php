<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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

    #[On('deleteItem')]
    public function deleteItem($id)
    {
        $item = Item::findOrFail($id);

        $item->delete();

        $this->dispatch(
            'itemDeleted',
            message: 'Item deleted successfully.'
        );

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

    public function clearSearch()
    {
        $this->search = '';
    }
}