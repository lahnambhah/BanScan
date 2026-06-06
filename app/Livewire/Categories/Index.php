<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Category;

#[Layout('layouts.app')]

class Index extends Component
{
    use WithPagination;
    public $name;
    public $description;
    public $search = '';
    public $editId = null;
    public $openModal = false; 

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2'
        ]);

        Category::create([
            'name' => $this->name,
            'description' => $this->description
        ]);

        session()->flash('success','Category created');

        $this->reset(['name','description','openModal']);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->editId = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->openModal = true;
    }

    public function update()
    {
        Category::find($this->editId)->update([
            'name'=>$this->name,
            'description'=>$this->description
        ]);

        session()->flash('success','Category updated');

        $this->reset(['name','description','editId','openModal']);
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        session()->flash('success','Category deleted');
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::withCount('items')
            ->where('name','like','%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.categories.index',[
            'categories'=>$categories
        ]);
    }
    
    public function openModal()
    {
        $this->reset(['name','description','editId']);
        $this->openModal = true;
    }
}