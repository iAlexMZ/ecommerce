<?php

namespace Tests\Feature\Tareas;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Brand, Image, Product, Category, Color, Size, Subcategory};

class ProductStockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_see_the_stock_of_product_without_color_and_size()
    {
        $product = $this->createProduct();

        $this->get('/products/' . $product->slug)
            ->assertSee($product->quantity);
    }

    /** @test */
    public function can_see_the_stock_of_product_with_color()
    {
        $normalProduct = $this->createProduct(true, false, $quantity = 5);
        $colorProduct = Color::factory()->create();

        $normalProduct->colors()->attach($colorProduct->id, ['quantity' => $quantity]);

        $this->get('/products/' . $normalProduct->slug)
            ->assertSee($normalProduct->quantity);
    }

    /** @test */
    public function can_see_the_stock_of_product_with_color_and_size()
    {
        $normalProduct = $this->createProduct(true, true, $quantity = 5);
        $colorProduct = Color::factory()->create();
        $sizeProduct = Size::factory()->create([
            'product_id' => $normalProduct->id,
        ]);

        $sizeProduct->colors()->attach($colorProduct->id, ['quantity' => $quantity]);

        $this->get('/products/' . $normalProduct->slug)
            ->assertSee($normalProduct->quantity);
    }







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
