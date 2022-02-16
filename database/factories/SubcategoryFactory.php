<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

class SubcategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::all()->random();
        $color = collect([true, false])->random();
        $size = collect([true, false])->random();
        $name = $this->faker->sentence();

        return [
            'category_id' => $category->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'color' => $color,
            'size' => $color ? $size : false,
        ];
    }
}
