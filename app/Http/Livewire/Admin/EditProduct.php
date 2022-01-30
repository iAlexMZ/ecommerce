<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use App\Models\{Brand, Product, Category, Subcategory};
use Livewire\Component;

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

    public function getSubcategoryProperty()
    {
        return Subcategory::find($this->product->subcategory_id);
    }

    public function render()
    {
        return view('livewire.admin.edit-product')->layout('layouts.admin');
    }
}
