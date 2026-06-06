<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Supplier;

#[Layout('layouts.app')]

class Index extends Component
{
    public $search = '';

    public function delete($id)
    {
        Supplier::find($id)->delete();
        session()->flash('success', 'Supplier deleted successfully');
        return redirect('/suppliers');
    }

    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.suppliers.index', [
            'suppliers' => $suppliers
        ]);
    }
}