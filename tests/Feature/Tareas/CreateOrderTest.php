<?php

namespace Tests\Feature\Tareas;

use App\CreateProduct;
use Tests\TestCase;
use App\Http\Livewire\{AddCartItem, CreateOrder};
use App\Models\{Brand, Image, Product, Category, Subcategory, User};
use Livewire\Livewire;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;


class CreateOrderTest extends TestCase
{
    use RefreshDatabase;
    use CreateProduct;

    /** @test */
    public function the_cart_is_destroyed_when_the_order_is_created_and_the_user_is_redirect_to_payment_route()
    {
        $product = $this->createProduct();

        $this->actingAs(User::factory()->create());

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);
        $this->assertTrue(count(Cart::content()) == 1);

        Livewire::test(CreateOrder::class, ['product' => $product])
            ->set('contact', 'Alejandro')
            ->set('phone', '666666666')
            ->call('create_order')
            ->assertStatus(200)
            ->assertRedirect('/orders/' . $product->id . '/payment');

        $this->assertTrue(count(Cart::content()) == 0);
    }

    /** @test */
    public function only_logged_user_can_create_a_order()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);
        $this->actingAs(User::factory()->create())->get('/orders/create')->assertStatus(200);

        Livewire::test(CreateOrder::class)
            ->assertSee($product->name)
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_unlogged_cant_create_a_order()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);
        $this->get('/orders/create')->assertStatus(302)->assertRedirect('/login');
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
