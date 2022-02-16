<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Livewire\{AddCartItem, AddCartItemColor, AddCartItemSize, DropdownCart, Search, ShoppingCart, UpdateCartItem};
use App\Models\{Brand, Image, Product, Category, Subcategory};

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_without_size_or_color_can_add_to_cart()
    {
        $product1 = $this->createProduct(false, false, 10);
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1)
            ->assertStatus(200);

        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
    }

    /** @test */
    public function a_product_without_size_can_add_to_cart()
    {
        $product1 = $this->createProduct(true, false);
        $product2 = $this->createProduct(true, false);

        Livewire::test(AddCartItemColor::class, ['product' => $product1])
            ->call('addItem', $product1)
            ->assertStatus(200);

        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
    }

    /** @test */
    public function a_product_can_add_to_cart()
    {
        $product1 = $this->createProduct(true, true);
        $product2 = $this->createProduct(true, true);

        Livewire::test(AddCartItemSize::class, ['product' => $product1])
            ->call('addItem', $product1)
            ->assertStatus(200);

        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
    }

    /** @test */
    public function the_product_can_see_in_the_cart()
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1)
            ->assertStatus(200);

        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);

        Livewire::test(DropdownCart::class)
            ->assertSee($product1->name)
            ->assertDontSee($product2->name)
            ->assertStatus(200);
    }

    /** @test */
    public function the_cart_increment_when_add_a_product()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);

        $this->assertEquals(Cart::content()->first()->id, 1);
    }

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

    /** @test */
    public function can_be_filter_by_name()
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(Search::class)
            ->assertSet('search', $product1->name)
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /** @test */
    public function it_shows_the_products_in_the_cart()
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->assertViewIs('livewire.add-cart-item')
            ->call('addItem', $product1)
            ->assertStatus(200);

        Livewire::test(ShoppingCart::class)
            ->assertViewIs('livewire.shopping-cart')
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);
    }

    /** @test */
    public function can_change_the_quantity_in_the_shopping_cart()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->assertViewIs('livewire.add-cart-item')
            ->call('addItem', $product)
            ->assertStatus(200);

        Livewire::test(ShoppingCart::class)
            ->assertViewIs('livewire.shopping-cart')
            ->assertSee($product->name);

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId, 'qty' => Cart::content()->first()->qty])
            ->assertViewIs('livewire.update-cart-item')
            ->call('increment')
            ->assertSet('qty', 2);

        $this->assertEquals(Cart::subtotal(), $product->price * 2);

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId, 'qty' => Cart::content()->first()->qty])
            ->assertViewIs('livewire.update-cart-item')
            ->call('decrement')
            ->assertSet('qty', 1);

        $this->assertEquals(Cart::subtotal(), $product->price);
    }

    /** @test */
    public function the_products_can_be_delete_in_the_shopping_cart()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->assertViewIs('livewire.add-cart-item')
            ->call('addItem', $product)
            ->assertStatus(200);

        Livewire::test(ShoppingCart::class, ['rowId' => Cart::content()->first()->rowId])
            ->assertViewIs('livewire.shopping-cart')
            ->assertSee($product->name)
            ->call('delete')
            ->assertDontSee($product->name);
    }

    /** @test */
    public function the_cart_can_be_empty()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->assertViewIs('livewire.add-cart-item')
            ->call('addItem', $product)
            ->assertStatus(200);

        Livewire::test(ShoppingCart::class, ['rowId' => Cart::content()->first()->rowId])
            ->assertViewIs('livewire.shopping-cart')
            ->assertSee($product->name)
            ->call('destroy', 'rowId')
            ->assertDontSee($product->name);
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