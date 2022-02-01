<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{Brand, Product, Category, Subcategory};

class EditProduct extends Component
{
    protected $rules = [
        'category_id' => 'required',
        'product.subcategory_id' => 'required',
        'product.name' => 'required',
        'product.slug' => 'required|unique:products',
        'product.description' => 'required',
        'product.brand_id' => 'required',
        'product.price' => 'required',
        'product.quantity' => 'numeric',
    ];

    public $product, $categories, $subcategories, $brands;
    public $category_id;

    public function mount(Product $product)
    {
        $this->product = $product;

        $this->categories = Category::all();
        $this->category_id = $product->subcategory->category->id;

        $this->subcategories = Subcategory::where('category_id', $this->category_id)->get();
        $this->brands = Brand::whereHas('categories', function (Builder $query) {
            $query->where('category_id', $this->category_id);
        })->get();
    }

    public function updatedCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->brands = Brand::whereHas('categories', function (Builder $query) use ($value) {
            $query->where('category_id', $value);
        })->get();
        $this->product->subcategory_id = '';
        $this->product->brand_id = '';
    }

    public function updatedProductName($value)
    {
        $this->product->slug = Str::slug($value);
    }

    public function getSubcategoryProperty()
    {
        return Subcategory::find($this->product->subcategory_id);
    }

    public function render()
    {
        return view('livewire.admin.edit-product')->layout('layouts.admin');
    }

    public function save()
    {
        $this->rules['product.slug'] = 'required|unique:products,slug,' . $this->product->id;
        if ($this->product->subcategory_id) {
            if (!$this->subcategory->color && !$this->subcategory->size) {
                $this->rules['product.quantity'] = 'required|numeric';
            }
        }
        $this->validate();

        $this->product->save();

        $this->emit('saved');
    }
}
