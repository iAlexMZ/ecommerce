<?php

namespace Tests\Feature\Tareas;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\AddCartItem;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Brand, Image, Product, Category, Subcategory};

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unlogged_user_cant_access_to_create_order()
    {
        $this->get('/orders/create')->assertStatus(302)->assertRedirect('/login');
    }

    /** @test */
    public function an_unlogged_user_cant_access_to_admin_view()
    {
        $this->get('/admin')->assertStatus(302)->assertRedirect('/login');
    }

    /** @test */
    public function a_logged_user_can_access_to_create_order()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);

        $this->get('/orders/create')->assertStatus(302)->assertRedirect('/login');
    }

    /** @test */
    public function a_logged_admin_user_cant_access_to_admin_view()
    {
        $this->actingAs(User::factory()->create(['id' => 1]))->get('/orders/create')->assertStatus(200);
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
