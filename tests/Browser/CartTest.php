<?php

namespace Tests\Browser;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Product;
use Tests\DuskTestCase;
use App\Models\Category;
use Laravel\Dusk\Browser;
use App\Models\Subcategory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CartTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function when_the_users_not_registered_try_to_make_a_order_they_redirect_to_login_page()
    {
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(3000)
                ->press('AGREGAR AL CARRITO DE COMPRAS')
                ->visit('/shopping-cart')
                ->assertSee($product->name)
                ->click('@continue')
                ->assertPathIs('/login')
                ->screenshot('cart');
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
