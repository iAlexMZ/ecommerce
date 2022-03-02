<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ShowProducts2 extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 15;
    public $columns = ['Nombre', 'Categoría', 'Estado', 'Precio', 'Subcategoría', 'Marca', 'Stock', 'Colores', 'Tallas', 'Fecha Creación', 'Fecha Edición'];
    public $selectedColumns = [];
    public $show = false;
    public $camp = null;
    public $order = null;
    public $icon = '-circle';

    public function mount()
    {
        $this->selectedColumns = $this->columns;
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPagination()
    {
        $this->resetPage();
    }

    public function clear()
    {
        $this->search = null;
        $this->pagination = 15;
        $this->order = null;
        $this->camp = null;
        $this->selectedColumns = $this->columns;
        $this->icon = '-circle';
    }

    public function sortable($camp)
    {
        if ($camp !== $this->camp) {
            $this->order = null;
        }
        switch ($this->order) {
            case null:
                $this->order = 'asc';
                $this->icon = '-arrow-circle-up';
                break;
            case 'asc':
                $this->order = 'desc';
                $this->icon = '-arrow-circle-down';
                break;
            case 'desc':
                $this->order = null;
                $this->icon = '-circle';
                break;
        }

        $this->camp = $camp;
    }

    public function render()
    {
        $products = Product::query()->where('name', 'LIKE', "%{$this->search}%");

        if ($this->camp && $this->order) {
            $products = $products->orderBy($this->camp, $this->order);
        }

        $products = $products->paginate($this->pagination);

        return view('livewire.admin.show-products2',  compact('products'))
            ->layout('layouts.admin');
    }
}
