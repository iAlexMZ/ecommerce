<?php

namespace Tests\Feature\Tareas;

use App\CreateData;
use Tests\TestCase;
use Livewire\Livewire;
use App\Listeners\MergeTheCart;
use Illuminate\Auth\Events\Login;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Brand, Image, Product, Category, Subcategory};
use App\Http\Livewire\{AddCartItem, AddCartItemColor, AddCartItemSize, DropdownCart, Search, ShoppingCart, UpdateCartItem};

class CartTest extends TestCase
{
    use RefreshDatabase;
    use CreateData;

    //Test ya modificado con una única línea
    /** @test */
    public function the_cart_increment_when_add_a_product()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);

        $this->assertEquals(Cart::content()->first()->id, $product->id);
    }

    /** @test */
    public function a_product_without_size_and_color_can_add_to_cart()
    {
        $product1 = $this->createProduct(false, false);
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
        $product2 = $this->createProduct(false, false);

        Livewire::test(AddCartItemColor::class, ['product' => $product1])
            ->call('addItem', $product1)
            ->assertStatus(200);

        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
    }

    /** @test */
    public function a_product_with_color_and_size_can_add_to_cart()
    {
        $product1 = $this->createProduct(true, true);
        $product2 = $this->createProduct(false, false);

        Livewire::test(AddCartItemSize::class, ['product' => $product1])
            ->call('addItem', $product1)
            ->assertStatus(200);

        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
    }

    /** @test */
    public function the_product_can_see_in_the_dropdown_cart()
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
    public function it_shows_the_products_in_the_shopping_cart()
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->assertViewIs('livewire.add-cart-item')
            ->call('addItem', $product1)
            ->assertStatus(200);

        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);

        Livewire::test(ShoppingCart::class)
            ->assertViewIs('livewire.shopping-cart')
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);
    }

    //Test ya modificado con una única línea
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

    //Test ya modificado con una única línea
    /** @test */
    public function the_products_can_be_delete_in_the_shopping_cart()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->assertViewIs('livewire.add-cart-item')
            ->call('addItem', $product)
            ->assertStatus(200);

        Livewire::test(ShoppingCart::class, ['product' => $product])
            ->assertViewIs('livewire.shopping-cart')
            ->assertSee($product->name)
            ->call('delete', Cart::content()->first()->rowId)
            ->assertDontSee($product->name);
    }

    //Test ya modificado con una única línea
    /** @test */
    public function the_cart_can_be_destroy()
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

    //Test ya modificado con una única línea
    //Ejercicio2:
    /** @test */
    public function shopping_cart_is_save_when_logout()
    {
        $user = $this->createUser();
        $product = $this->createProduct(false, false, 10);
        $price = $product->price;
        $product2 = $this->createProduct(false, false, 15);
        $price2 = $product->price;
        $product3 = $this->createProduct(false, false, 20);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);

        Livewire::test(AddCartItem::class, ['product' => $product2])
            ->call('addItem', $product2)
            ->assertStatus(200);

        $content = Cart::content();

        $this->post('/logout');

        $this->assertDatabaseHas('shoppingcart', ['content' => serialize($content)]);

        $cartBack = new MergeTheCart();
        $reLogin = new Login('web', $user, true);
        $this->actingAs($user);

        $cartBack->handle($reLogin);

        $this->get('/orders/create')
            ->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee($price)
            ->assertSee($product->quantity)
            ->assertSee($product2->name)
            ->assertSee($price2)
            ->assertSee($product2->quantity)
            ->assertDontSee($product3->name);
    }
}
