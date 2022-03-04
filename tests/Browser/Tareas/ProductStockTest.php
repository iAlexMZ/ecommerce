<?php

namespace Tests\Browser\Tareas;

use App\CreateData;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use App\Models\{Brand, Image, Product, Category, Color, Size, Subcategory};
use Tests\DuskTestCase;

class ProductStockTest extends DuskTestCase
{
    use DatabaseMigrations;
    use CreateData;

    /** @test */
    public function check_that_the_stock_varies_when_adding_a_product_without_color_and_size()
    {
        $normalProduct = $this->createProduct(false, false, 10);

        $this->browse(function (Browser $browser) use ($normalProduct) {
            $browser->visit('/products/' . $normalProduct->slug)
            ->pause(500)
            ->assertSee('Stock disponible: 10')
            ->click('@button_more')
            ->pause(500)
            ->click('@add-cart')
            ->pause(500)
            ->assertSee('Stock disponible: 8')
            ->screenshot('stock-normal-product');
        });
    }

    /** @test */
    public function check_that_the_stock_varies_when_adding_a_product_without_size()
    {
        $normalProduct = $this->createProduct(true, false, $quantity = 20);
        $colorProduct = Color::factory()->create();

        $normalProduct->colors()->attach($colorProduct->id, ['quantity' => $quantity]);

        $this->browse(function (Browser $browser) use ($normalProduct) {
            $browser->visit('/products/' . $normalProduct->slug)
            ->assertSee('Stock disponible: 20')
            ->pause(500)
            ->click('@add-color-product')
            ->pause(500)
            ->click('@color')
            ->pause(1000)
            ->press('@button-more-color')
            ->pause(500)
            ->click('@add-cart')
            ->pause(500)
            ->assertSee('Stock disponible: 18')
            ->screenshot('stock-color-size-product');
        });
    }

    /** @test */
    public function check_that_the_stock_varies_when_adding_a_product_with_color_and_size()
    {
        $normalProduct = $this->createProduct(true, true, $quantity = 20);
        $colorProduct = Color::factory()->create();
        $sizeProduct = Size::factory()->create([
            'product_id' => $normalProduct->id,
        ]);

        $sizeProduct->colors()->attach($colorProduct->id, ['quantity' => $quantity]);

        $this->browse(function (Browser $browser) use ($normalProduct) {
            $browser->visit('/products/' . $normalProduct->slug)
            ->assertSee('Stock disponible: 20')
            ->click('@add-size-product')
            ->pause(500)
            ->click('@size')
            ->pause(500)
            ->click('@add-color-product')
            ->pause(500)
            ->click('@color')
            ->pause(1000)
            ->click('@button-more-color-size')
            ->screenshot('stock-color-size-product')
            ->pause(500)
            ->click('@add-cart')
            ->pause(500)
            ->assertSee('Stock disponible: 18')
            ->screenshot('stock-color-size-product');
        });
    }
}
