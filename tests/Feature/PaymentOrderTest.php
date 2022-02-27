<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Brand, Category, Subcategory, Image, Order, Product};
use App\Http\Livewire\{AddCartItem, CreateOrder, ShoppingCart, PaymentOrder};
use Gloudemans\Shoppingcart\Facades\Cart;

class PaymentOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_cart_is_destroyed_when_the_order_is_created()
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);
        $this->assertTrue(count(Cart::content()) == 1);

        Livewire::test(CreateOrder::class, ['product' => $product])
            ->set('contact', 'Alejandro')
            ->set('phone', 'Alejandro')
            ->call('create_order')
            ->assertStatus(200)
            ->assertRedirect('/orders/' . $product->id . '/payment');

        $this->assertTrue(count(Cart::content()) == 0);
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
