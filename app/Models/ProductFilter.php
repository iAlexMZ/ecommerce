<?php

namespace App\Models;


use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends QueryFilter
{
     public function rules(): array
    {
        return [
            'categorySearch' => 'filled',
        ];
    }

    /*
    public function filterByCategorySearch($query, $categorySearch)
    {
        return $query->whereHas('subcategory', function ($query) use ($categorySearch) {
            $query->whereHas('category', function ($query) use ($categorySearch) {
                $query->where('name', 'LIKE', "%{$categorySearch}%");
            });
        });
    } */
}
