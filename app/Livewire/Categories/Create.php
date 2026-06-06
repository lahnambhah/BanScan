<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;

#[Layout('layouts.app')]

class Create extends Component
{

    public $name;
    public $description;

    public function save()
    {

        $this->validate([
            'name' => 'required|min:2|unique:categories,name',
        ], [
            // NAME
            'name.required' => 'Category name is required.',
            'name.min' => 'Category name must be at least 2 characters.',
            'name.unique' => 'This category already exists.',
        ]);

        Category::create([
            'name'=>$this->name,
            'description'=>$this->description
        ]);

        session()->flash('success','Category created successfully');

        return redirect('/categories');
    }

    public function render()
    {
        return view('livewire.categories.create');
    }
}