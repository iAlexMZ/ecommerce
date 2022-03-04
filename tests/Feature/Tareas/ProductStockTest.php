<?php

namespace Tests\Feature\Tareas;

use App\CreateProduct;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Brand, Image, Product, Category, Color, Size, Subcategory};

class ProductStockTest extends TestCase
{
    use RefreshDatabase;
    use CreateProduct;

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
}
