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
            'category' => 'filled|exists:categories,id',
        ];
    }

    public function category($query, $category)
    {
        return $query->whereHas('subcategory.category', function ($query) use ($category) {
            $query->where('id', $category);
        });
    }
}
