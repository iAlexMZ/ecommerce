<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class Search extends Component
{
    public $search;
    public $open = false;

    public function render()
    {
        $products = $this->search
            ? Product::where('name', 'LIKE', "%{$this->search}%")->where('status', 2)->take(8)->get()
            : [];

        return view('livewire.search', compact('products'));
    }

    public function updatedSearch($value)
    {
        $value ? $this->open = true :$this->open = false;
    }
}
