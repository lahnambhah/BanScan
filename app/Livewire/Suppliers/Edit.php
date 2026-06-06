<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Supplier;

#[Layout('layouts.app')]

class Edit extends Component
{
    public $supplierId;
    public $name;
    public $email;
    public $phone;
    public $address;

    public function mount($id)
    {
        $supplier = Supplier::findOrFail($id);

        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
    }

    public function update()
    {
        Supplier::find($this->supplierId)->update([
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'address'=>$this->address
        ]);

        session()->flash('success', 'Supplier updated successfully');
        return redirect('/suppliers');
    }

    public function render()
    {
        return view('livewire.suppliers.edit');
    }
}