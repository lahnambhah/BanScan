<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Supplier;

#[Layout('layouts.app')]

class Index extends Component
{
    public $search = '';

    #[On('deleteSupplier')]
    public function deleteSupplier($id)
    {
        $supplier = Supplier::withCount('items')->findOrFail($id);

        if ($supplier->items_count > 0) {

            $this->dispatch(
                'supplierDeleteError',
                message: 'Cannot delete this supplier because it is assigned to '
                    . $supplier->items_count
                    . ' item(s).'
            );

            return;
        }

        $supplier->delete();

        $this->dispatch(
            'supplierDeleted',
            message: 'Supplier deleted successfully.'
        );
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

    public function clearSearch()
    {
        $this->search = '';
    }
}