<?php

namespace Tests\Browser\Tareas;

use App\CreateData;
use Livewire\Livewire;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Http\Livewire\AddCartItem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{Brand, Category, City, Department, District, Subcategory, Image, Product, User};

class PaymentOrderTest extends DuskTestCase
{
    use DatabaseMigrations;
    use CreateData;

    //Test ya modificado en una sola línea
    /** @test */
    public function the_user_can_choose_the_shipping_option()
    {
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->loginAs(User::factory()->create())
                ->assertPathIs('/')
                ->click('@product')
                ->press('AGREGAR AL CARRITO DE COMPRAS')
                ->pause(1000)
                ->visit('/shopping-cart')
                ->assertSee($product->name)
                ->click('@continue')
                ->pause(1000)
                ->visit('/orders/create')
                ->click('@shop')
                ->screenshot('picked-up-at-the-store')
                ->click('@home')
                ->pause(1000)
                ->assertSee('Departamento', 'Ciudad', 'Distrito', 'Dirección', 'Referencia')
                ->screenshot('home-delivery');
        });
    }

    /** @test */
    public function chained_selections_load_correctly()
    {
        $department = Department::factory()->create();

        $city = City::factory()->create([
            'department_id' => $department->id,
        ]);

        $district = District::factory()->create([
            'city_id' => $city->id,
        ]);

        $this->browse(function (Browser $browser) use ($department, $city, $district) {
            $product = $this->createProduct();

            $browser->loginAs(User::factory()->create())
                ->visit('/products/' . $product->slug)
                ->press('AGREGAR AL CARRITO DE COMPRAS')
                ->visit('/orders/create')
                ->check('@home')
                ->click('@department')
                ->pause(500)
                ->click('@option-department')
                ->assertSee($department->name)
                ->screenshot('select-department')
                ->click('@city')
                ->pause(500)
                ->click('@option-city')
                ->assertSee($city->name)
                ->pause(500)
                ->screenshot('select-city')
                ->click('@district')
                ->pause(500)
                ->click('@option-district')
                ->assertSee($district->name)
                ->screenshot('select-district');
        });
    }
}
