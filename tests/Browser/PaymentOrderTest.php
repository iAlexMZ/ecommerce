<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{Brand, Category, Subcategory, Image, Product, User};
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PaymentOrderTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_user_can_choose_the_shipping_option()
    {
        $product = $this->createProduct();
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($product, $user) {
            $browser->visit('/login')
                ->pause(1000)
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('INICIAR SESIÓN')
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
                ->assertSee('Departamento')
                ->assertSee('Ciudad')
                ->assertSee('Distrito')
                ->assertSee('Dirección')
                ->assertSee('Referencia')
                ->screenshot('home-delivery');
        });
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
