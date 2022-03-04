<?php

namespace Tests\Browser\Tareas;

use App\CreateData;
use Livewire\Livewire;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Http\Livewire\{CreateOrder, AddCartItem};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{Brand, Category, Subcategory, Image, Product, User};

class OrdersPageTest extends DuskTestCase
{
    use DatabaseMigrations;
    use CreateData;

    //Test ya modificado con una única línea
    /** @test */
    public function a_user_can_see_his_orders()
    {
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->loginAs(User::factory()->create())
                ->visit('/')
                ->click('@product')
                ->press('AGREGAR AL CARRITO DE COMPRAS')
                ->pause(500)
                ->visit('/shopping-cart')
                ->assertSee($product->name)
                ->click('@continue')
                ->screenshot('pene');
            $browser->visit('/orders/create')
                ->pause(500)
                ->assertSee($product->name)
                ->type('@contact-name', 'Alejandro')
                ->type('@contact-phone', '666666666')
                ->click('@create-order')
                ->pause(500)
                ->assertPathIs('/orders/' . $product->id . '/payment')
                ->pause(500)
                ->visit('/')
                ->click('@profile_image')
                ->pause(500)
                ->assertSee('Mis Pedidos')
                ->click('@my-orders')
                ->assertPathIs('/orders')
                ->assertSee('Pedidos recientes')
                ->screenshot('user-order-view');
        });
    }
}
