<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;

#[Layout('layouts.app')]

class Edit extends Component
{
    public $categoryId;
    public $name;
    public $description;

    public function mount($id)
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function update()
    {

        $this->validate([
            'name' => 'required|min:2|unique:categories,name,' . $this->categoryId,
        ], [

            // NAME
            'name.required' => 'Category name is required.',
            'name.min' => 'Category name must be at least 2 characters.',
            'name.unique' => 'This category already exists.',
        ]);

        Category::find($this->categoryId)->update([
            'name'=>$this->name,
            'description'=>$this->description
        ]);

        session()->flash('success','Category updated successfully');

        return redirect('/categories');
    }

    public function render()
    {
        return view('livewire.categories.edit');
    }
}