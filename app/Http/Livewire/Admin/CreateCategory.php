<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class CreateCategory extends Component
{
    use WithFileUploads;

    public $listeners = ['delete'];

    public $createForm = [
        'name' => null,
        'slug' => null,
        'icon' => null,
        'image' => null,
        'brands' => [],
    ];

    public $editForm = [
        'open' => false,
        'name' => null,
        'slug' => null,
        'icon' => null,
        'image' => null,
        'brands' => [],
    ];

    protected $rules = [
        'createForm.name' => 'required',
        'createForm.slug' => 'required|unique:categories,slug',
        'createForm.icon' => 'required',
        'createForm.image' => 'required|image|max:1024',
        'createForm.brands' => 'required',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.slug' => 'slug',
        'createForm.icon' => 'icono',
        'createForm.image' => 'imagen',
        'createForm.brands' => 'marcas',
        'editForm.name' => 'nombre',
        'editForm.slug' => 'slug',
        'editForm.icon' => 'ícono',
        'editImage' => 'imagen',
        'editForm.brands' => 'marcas'
    ];


    public $brands, $category, $categories, $image;

    public $editImage;

    public function mount()
    {
        $this->getBrands();
        $this->getCategories();
        $this->image = 1;
    }

    public function getBrands()
    {
        $this->brands = Brand::all();
    }

    public function getCategories()
    {
        $this->categories = Category::all();
    }

    public function updatedCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        $image = $this->createForm['image']->store('categories', 'public');

        $category = Category::create([
            'name' => $this->createForm['name'],
            'slug' => $this->createForm['slug'],
            'icon' => $this->createForm['icon'],
            'image' => $image
        ]);
        $category->brands()->attach($this->createForm['brands']);

        $this->image = 2;
        $this->reset('createForm');

        $this->getCategories();
        $this->emit('saved');
    }

    public function edit(Category $category)
    {
        $this->image = rand();
        $this->reset(['editImage']);
        $this->category = $category;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $category->name;
        $this->editForm['slug'] = $category->slug;
        $this->editForm['icon'] = $category->icon;
        $this->editForm['image'] = $category->image;
        $this->editForm['brands'] = $category->brands->pluck('id');
    }

    public function update()
    {
        $rules = [
            'editForm.name' => 'required',
            'editForm.slug' => 'required|unique:categories,slug,' . $this->category->id,
            'editForm.icon' => 'required',
            'editForm.brands' => 'required',
        ];

        if ($this->editImage) {
            $rules['editImage'] = 'required|image|max:1024';
        }
        $this->validate($rules);
    }

    public function updatedEditFormName($value)
    {
        $this->editForm['slug'] = Str::slug($value);
    }

    public function delete(Category $category)
    {
        $category->brands()->detach();
        $category->delete();
        $this->getCategories();
    }

    public function render()
    {
        return view('livewire.admin.create-category');
    }
}
