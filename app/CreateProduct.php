<?php
    namespace App;
    
    use App\Models\{Brand, Image, Product, Category, Subcategory};

    trait CreateProduct
    {
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
    }
