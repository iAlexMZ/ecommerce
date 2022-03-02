<?php

namespace Tests\Feature\Tareas;

use Tests\TestCase;
use App\Http\Livewire\{AddCartItem};
use App\Models\{Brand, Image, Product, Category, Subcategory};
use Livewire\Livewire;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
   public function is_not_possible_to_add_more_products_that_the_stock_is_0()
   {
       $quantity = 4;
       $product = $this->createProduct(false, false, $quantity);

       for ($i = 0; $i < $quantity; $i++) {
           Livewire::test(AddCartItem::class, ['product' => $product])
               ->call('addItem', $product);
           $product->quantity = qty_available($product->id);
       }

       $this->assertEquals($quantity, Cart::content()->first()->qty);
   }

   /** @test */
   public function it_shows_the_product_stock()
   {
       $product1 = $this->createProduct(false, false, 10);
       $product2 = $this->createProduct(false, false, 0);

       $this->get('products/' . $product1->slug)
           ->assertStatus(200)
           ->assertSeeText('Stock disponible: ' . $product1->quantity)
           ->assertDontSeeText('Stock disponible: ' . $product2->quantity);
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
