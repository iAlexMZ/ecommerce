<?php

namespace Tests\Feature\Tareas;

use App\CreateProduct;
use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;
use Tests\TestCase;
use App\Models\{User, Brand, Image, Product, Category, Subcategory};
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizedUserTest extends TestCase
{
    use RefreshDatabase;
    use CreateProduct;

    /** @test */
    public function an_unlogged_user_cant_access_to_admin_view()
    {
        $this->get('/admin')->assertStatus(302)->assertRedirect('/login');
    }

    /** @test */
    public function only_user_admin_can_access_to_admin_view()
    {
        Role::create(['name' => 'admin']);

        $adminUser = User::factory()->create()->assignRole('admin');
        $normalUser = User::factory()->create();

        $this->actingAs($adminUser)->get('/admin')->assertStatus(200);
        $this->actingAs($normalUser)->get('/admin')->assertStatus(403);
    }

    /** @test */
    public function cannot_access_to_a_order_of_another_user()
    {
        $this->actingAs(User::factory()->create(['id' => 1]));
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(CreateOrder::class)
            ->set('contact', 'Alejandro')
            ->set('phone', '66666666')
            ->call('create_order');

        $this->actingAs(User::factory()->create(['id' => 2]))->get('/orders/1/payment')->assertStatus(403);
    }
}
