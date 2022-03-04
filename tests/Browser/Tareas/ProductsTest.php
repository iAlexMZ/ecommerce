<?php

namespace Tests\Browser\Tareas;

use App\CreateProduct;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Image;

class ProductsTest extends DuskTestCase
{
    use DatabaseMigrations;
    use CreateProduct;

    /** @test */
    public function it_shows_a_five_products()
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();
        $product3 = $this->createProduct();
        $product4 = $this->createProduct();
        $product5 = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product1, $product2, $product3, $product4, $product5) {
            $browser->visit('/')
                ->pause(500)
                ->assertSee(substr($product1->name, 0, 8))
                ->pause(500)
                ->assertSee(substr($product2->name, 0, 8))
                ->pause(500)
                ->assertSee(substr($product3->name, 0, 8))
                ->pause(500)
                ->assertSee(substr($product4->name, 0, 8))
                ->pause(500)
                ->assertSee(substr($product5->name, 0, 8))
                ->screenshot('products_screenshot');
        });
    }

    /** @test */
    public function it_shows_products_published()
    {
        $brand = Brand::factory()->create();
        $brand2 = Brand::factory()->create();

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $category2 = Category::factory()->create();
        $category2->brands()->attach($brand2->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $subcategory2 = Subcategory::factory()->create([
            'category_id' => $category2->id,
        ]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'status' => 2,
        ]);

        Image::factory()->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class,
        ]);

        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
            'status' => 1,
        ]);

        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($product1, $product2) {
            $browser->visit('/')
                ->assertSee($product1->name)
                ->pause(1000)
                ->assertDontSee($product2->name)
                ->screenshot('products_published');
        });
    }

    /** @test */
    public function the_products_can_be_filter_by_subcategory()
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product1, $product2) {
            $browser->visit('/')
                ->click('@show_more')
                ->pause(1000)
                ->assertSee("Subcategorías")
                ->click('@subcategories_filter')
                ->assertSee(ucfirst($product1->name))
                ->pause(500)
                ->assertDontSee(ucfirst($product2->name))
                ->pause(500)
                ->screenshot('filter_by_subcategory');
        });
    }

    /** @test */
    public function the_products_can_be_filter_by_brand()
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product1, $product2) {
            $browser->visit('/')
                ->click('@show_more')
                ->pause(1000)
                ->assertSee("Subcategorías")
                ->click('@brands_filter')
                ->assertSee(ucfirst($product1->name))
                ->pause(500)
                ->assertDontSee(ucfirst($product2->name))
                ->pause(500)
                ->screenshot('filter_by_brand');
        });
    }
    /** @test */
    public function it_shows_products_details()
    {
        $product = $this->createProduct();
        $product2 = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product, $product2) {
            $browser->visit('/')
                ->click('@show_more')
                ->pause(1000)
                ->assertSee("Subcategorías")
                ->click('@product')
                ->pause(500)
                ->assertPathIs('/products/' . $product->slug)
                ->assertPathIsNot('/products/' . $product2->slug)
                ->assertSee($product->description)
                ->assertSee(ucfirst($product->name))
                ->pause(500)
                ->assertSee($product->price)
                ->assertSee($product->quantity)
                ->pause(500)
                ->assertVisible('@button-less')
                ->assertVisible('@button-more')
                ->assertVisible('@add-cart')
                ->screenshot('product_details');
        });
    }

    /** @test */
    public function the_button_to_add_more_quantity_of_product_must_be_limited()
    {
        $product = $this->createProduct(false, false, 5);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(1000)
                ->assertSee(ucfirst($product->name));
            for ($i = 0; $i <= $product->quantity; $i++) {
                $browser->press('@button-more');
                $browser->pause(500);
            };
            $browser->pause(1000);
            $browser->assertButtonDisabled('@button-more');
            $browser->screenshot('add_more');
        });
    }

    /** @test */
    public function the_button_to_less_more_quantity_of_product_must_be_limited()
    {
        $product = $this->createProduct(false, false, 5);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(1000)
                ->assertSee(ucfirst($product->name));
            for ($i = $product->quantity; $i > 1; $i--) {
                $browser->press('@button-less');
                $browser->pause(500);
            };
            $browser->pause(500);
            $browser->assertButtonDisabled('@button-less');
            $browser->screenshot('less_more');
        });
    }

    /** @test */
    public function it_shows_the_color_and_size_dropdowns_depending_on_the_selected_product()
    {
        $product1 = $this->createProduct(true, false);
        $product2 = $this->createProduct(false, false);
        $product3 = $this->createProduct(true, true);

        $this->browse(function (Browser $browser) use ($product1, $product2, $product3) {
            $browser->pause(1000);
            if ($browser->visit('/products/' . $product1->slug)) {
                $browser->pause(500);
                $browser->assertPresent('@add-color-product');
                $browser->assertNotPresent('@size2');
                $browser->screenshot('product_with_color');
            };

            $browser->pause(1000);
            if ($browser->visit('/products/' . $product3->slug)) {
                $browser->pause(500);
                $browser->assertPresent('@add-size-product');
                $browser->assertPresent('@add-color-product');
                $browser->screenshot('product_with_color_size');
            };

            $browser->pause(1000);
            if ($browser->visit('/products/' . $product2->slug)) {
                $browser->pause(500);
                $browser->assertNotPresent('@color');
                $browser->assertNotPresent('@size');
                $browser->screenshot('product_without_color_size');
            };
        });
    }
}
