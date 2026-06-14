<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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

    #[On('deleteCategory')]
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category->items()->count() > 0) {

            $this->dispatch(
                'categoryError',
                message: 'Cannot delete category that contains items.'
            );
            return;
        }

        $category->delete();
        $this->dispatch(
            'categoryDeleted',
            message: 'Category deleted successfully.'
        );
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
    public function clearSearch()
    {
        $this->search = '';
    }
}