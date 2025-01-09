<?php

namespace App\Livewire;

use App\Models\Attribute;
use App\Models\Category;
use Livewire\Component;

class AttributeLivewire extends Component
{
    public $categoryId;
    public $name;
    public $activeForm = false;
    public $editId;
    public $editName;
    public $editCategoryId;

    protected $rules = [
        'name' => 'required|max:255',
        'categoryId' => 'required|exists:categories,id',  // category_id required
    ];

    public function render()
    {
        $categories = Category::all();  // All categories to display in the dropdown
        $attributes = Attribute::with('category')->get();  // Eager load category for each attribute
        return view('livewire.attribute-livewire', [
            'categories' => $categories,
            'attributes' => $attributes
        ]);
    }

    public function create()
    {
        $this->activeForm = true;
    }

    public function cancel()
    {
        $this->activeForm = false;
    }

    public function save()
    {
        $data = $this->validate();
        Attribute::create([
            'name' => $this->name,
            'category_id' => $this->categoryId
        ]);
        $this->activeForm = false;
        $this->reset(['name', 'categoryId']);
    }

    public function delete($id)
    {
        $attribute = Attribute::find($id);
        if ($attribute) {
            $attribute->delete();
        }
    }

    public function edit($id)
    {
        $this->editId = $id;
        $attribute = Attribute::find($id);
        if ($attribute) {
            $this->editName = $attribute->name;
            $this->editCategoryId = $attribute->category_id;  // Edit the category as well
        }
    }

    public function update($id)
    {
        $this->validate();
        $attribute = Attribute::find($id);
        if ($attribute) {
            $attribute->update([
                'name' => $this->editName,
                'category_id' => $this->editCategoryId  // Update the category
            ]);
        }
        $this->reset('editId', 'editName', 'editCategoryId');
    }
}
