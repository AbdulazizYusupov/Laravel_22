<?php


namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Character;

class ProductLivewire extends Component
{
    public $activeForm = false;
    public $name, $description, $image, $price, $category_id, $attribute_id, $character_id, $count;
    public $editId, $models;
    protected $rules = [
        'name' => 'required|max:255',
        'description' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'price' => 'required',
        'category_id' => 'required',
        'attribute_id' => 'required',
        'character_id' => 'required',
        'count' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        $this->models = Product::with('category', 'attribute', 'character')->get();
        return view('livewire.product-livewire');
    }

    public function create()
    {
        $this->activeForm = true;
    }

    public function cancel()
    {
        $this->activeForm = false;
    }
    public function updateCategory($groupIds)
    {
        foreach ($groupIds as $id) {
            Category::where('id', $id['value'])->update(['sort' => $id['order']]);
        }
        $this->models = Category::orderBy('sort', 'asc')->get();
    }
    public function save()
    {
        $data = $this->validate();
        Product::create($data);
        $this->activeForm = false;
        $this->reset(['name', 'description', 'image', 'price', 'category_id', 'attribute_id', 'character_id', 'count']);
    }

    public function delete($id)
    {
        $post = Product::findOrFail($id);
        if ($post) {
            $post->delete();
        }
    }

    public function edit($id)
    {
        if ($this->editId === $id) {
            $this->reset('editId', 'edit');
        } else {
            $this->editId = $id;
            $this->editName = $this->models->find($id)->name;
        }
    }

    public function update($id)
    {
        $this->models->find($id)->update(['name' => $this->editName]);
        $this->reset('editId', 'editName', 'editSort');
    }
}
