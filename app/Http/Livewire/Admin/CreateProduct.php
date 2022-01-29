<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\{Category, Subcategory};

class CreateProduct extends Component
{
    public $categories, $subcategories = [];
    public $category_id = '', $subcategory_id = '';

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function updatedCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->reset('subcategory_id');
    }

    public function render()
    {
        return view('livewire.admin.create-product')
            ->layout('layouts.admin');
    }
}
