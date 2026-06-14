<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Supplier;
use function Laravel\Prompts\alert;

#[Layout('layouts.app')]

class Create extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;

    public function save()
    {
        $this->validate([

            'name' => 'required|min:2',
            'email' => 'email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/|unique:suppliers,email',
            'phone' => 'required|numeric|digits:10|unique:suppliers,phone',
        ], [

            // NAME
            'name.required' => 'Supplier name is required.',
            'name.min' => 'Supplier name must be at least 2 characters.',

            // EMAIL
            'email' => 'Email must be a valid email address.',
            'email.email' => 'Enter a valid email address.',
            'email.regex' => 'Only Gmail addresses are allowed (example@gmail.com).',
            'email.unique' => 'This email already exists.',

            // PHONE
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must contain only numbers.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'phone.unique' => 'This phone number already exists.',

        ]);

        Supplier::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

        session()->flash('success', 'Supplier added successfully');

        return redirect('/suppliers');
    }

    public function render()
    {
        return view('livewire.suppliers.create');
    }
}