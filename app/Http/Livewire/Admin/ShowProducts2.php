<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

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
        $products = Product::search($this->search);

        $prueba = DB::select("SELECT *
        FROM (

        SELECT p.name as 'nombre_producto', sc.category_id AS 'id_categoria', p.status as status, p.price as precio ,p.subcategory_id as subcategoria,
        b.name as nombre_marca, SUM(cs.quantity) as cantidad_color, sc.color as color, sc.size as talla, p.created_at, p.updated_at
        from products as p
        INNER JOIN subcategories as sc
        ON p.subcategory_id = sc.id
        INNER JOIN sizes as sz
        ON sz.product_id = p.id
        INNER JOIN color_size as cs
        ON cs.size_id = sz.id
        INNER JOIN brands as b
        ON b.id = p.brand_id
        WHERE (sc.color = 1 AND sc.size = 1)
        GROUP BY p.name, sc.category_id, p.status, p.price ,p.subcategory_id ,
        b.name , sc.color , sc.size , p.created_at, p.updated_at

        UNION

        SELECT p.name as 'nombre_producto', sc.category_id AS 'id_categoria', p.status as status, p.price as precio ,p.subcategory_id as subcategoria,
        b.name as nombre_marca, SUM(cp.quantity) as stock, sc.color as color, sc.size as talla, p.created_at, p.updated_at
        from products as p
        INNER JOIN subcategories as sc
        ON p.subcategory_id = sc.id
        INNER JOIN color_product as cp
        ON cp.product_id = p.id
        INNER JOIN brands as b
        ON b.id = p.brand_id
        WHERE (sc.size = 0 AND sc.color = 1)
        GROUP BY p.name, sc.category_id, p.status, p.price ,p.subcategory_id,
        b.name, sc.color, sc.size , p.created_at, p.updated_at

        UNION

        SELECT p.name as 'nombre_producto', sc.category_id AS 'id_categoria', p.status as status, p.price as precio ,p.subcategory_id as subcategoria,
        b.name as nombre_marca, p.quantity as stock, sc.color as color, sc.size as talla, p.created_at, p.updated_at
        from products as p
        INNER JOIN subcategories as sc
        ON p.subcategory_id = sc.id
        INNER JOIN brands as b
        ON b.id = p.brand_id
        WHERE (sc.size = 0 AND sc.color = 0)) as catalogo
        ORDER BY catalogo.nombre_producto;");


        if ($this->camp && $this->order) {
            $products = $products->orderBy($this->camp, $this->order);
        }
        $products = $products->paginate($this->pagination);

        return view('livewire.admin.show-products2',  compact('products'))
            ->layout('layouts.admin');
    }
}
