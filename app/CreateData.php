<?php

namespace App;

use App\Models\City;
use App\Models\User;
use App\Models\District;
use App\Models\Department;
use PhpParser\Node\Expr\FuncCall;
use App\Models\{Brand, Image, Product, Category, Subcategory};

trait CreateData
{
    //Este método ya lo tenia previamente desarrollado
    public function createProduct($color = false, $size = false, $quantity = 10)
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => $color,
            'size' => $size,
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'quantity' => $quantity,
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ]);

        return $product;
    }

    public function createUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    public function createProductName($name)
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $brand = Brand::factory()->create();
        $category->brands()->attach([$brand->id]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'name' => $name,
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        return $product;
    }

    public function createCategory()
    {
        $category = Category::factory()->create([
            'name' => 'TV, audio y video',
        ]);

        return $category;
    }

    public function createSendData()
    {
        $department = Department::factory()->create();

        $city = City::factory()->create([
            'department_id' => $department->id,
        ]);

        $district = District::factory()->create([
            'city_id' => $city->id,
        ]);
    }
}
