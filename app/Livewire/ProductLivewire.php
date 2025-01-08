<?php


namespace App\Livewire;

use App\Models\AttChar;
use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Character;
use App\Models\Element;
use App\Models\Option;
use Livewire\WithFileUploads;

class ProductLivewire extends Component
{
    use WithFileUploads;
    public $activeForm = false;
    public $name, $description, $image, $title, $price, $category_id, $attribute_id, $character_id, $count;
    public $editId, $models, $categories, $atts, $characters;
    protected $rules = [
        'name' => 'required',
        'description' => 'required',
        // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:9024',
        'title' => 'required',
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
        $this->categories = Category::all();
        $this->atts = Attribute::all();
        $this->characters = Character::all();
        $this->models = Product::all();
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
    // public function updateCategory($groupIds)
    // {
    //     foreach ($groupIds as $id) {
    //         Category::where('id', $id['value'])->update(['sort' => $id['order']]);
    //     }
    //     $this->models = Category::orderBy('sort', 'asc')->get();
    // }
    public function save()
    {
        $data = $this->validate();

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        $product = Product::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $data['image'],
            'category_id' => $data['category_id'],
        ]);
        $element = Element::create([
           'product_id' => $product->id,
           'title' => $data['title'],
           'price' => $data['price'],
           'count' => $data['count'],
        ]);
        $attchar = AttChar::create([
           'att_id' => $data['attribute_id'],
           'char_id' => $data['character_id'],
        ]);
        Option::create([
           'element_id' => $element->id,
            'attchar_id' => $attchar->id
        ]);
        $this->activeForm = false;
        $this->reset(['name', 'description', 'image', 'price', 'category_id', 'attribute_id', 'character_id', 'count']);
    }

    // public function delete($id)
    // {
    //     $post = Product::findOrFail($id);
    //     if ($post) {
    //         $post->delete();
    //     }
    // }

    // public function edit($id)
    // {
    //     if ($this->editId === $id) {
    //         $this->reset('editId', 'edit');
    //     } else {
    //         $this->editId = $id;
    //         $this->editName = $this->models->find($id)->name;
    //     }
    // }

    // public function update($id)
    // {
    //     $this->models->find($id)->update(['name' => $this->editName]);
    //     $this->reset('editId', 'editName', 'editSort');
    // }
}
