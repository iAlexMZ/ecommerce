<?php

namespace Tests\Browser;

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

    /** @test */
    public function it_shows_a_five_products()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class,
        ]);

        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class,
        ]);

        $product3 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product3->id,
            'imageable_type' => Product::class,
        ]);

        $product4 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product4->id,
            'imageable_type' => Product::class,
        ]);

        $product5 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product5->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($product1, $product2, $product3, $product4, $product5) {
            $browser->visit('/')
                ->pause(1000)
                ->assertSee($product1->name)
                ->pause(1000)
                ->assertSee($product2->name)
                ->pause(1000)
                ->assertSee($product3->name)
                ->pause(1000)
                ->assertSee($product4->name)
                ->pause(1000)
                ->assertSee($product5->name)
                ->screenshot('products_screenshot');
        });
    }

    /** @test */
    public function it_shows_products_published()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class,
        ]);

        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
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
        $brand = Brand::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category->brands()->attach($brand->id);

        $subcategory1 = Subcategory::factory()->create([
            'name' => 'Celulares y smartphones',
            'category_id' => $category->id,
        ]);

        $subcategory2 = Subcategory::factory()->create([
            'name' => 'Accesorios para celulares',
            'category_id' => $category->id,
        ]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class,
        ]);

        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($category, $product1, $product2) {
            $browser->visit('/categories/' . $category->slug)
                ->pause(1000)
                ->assertSee(strtoupper($category->name))
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
        $brand = Brand::factory()->create();

        $category1 = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category2 = Category::factory()->create([
            'name' => 'TV, audio y video',
        ]);

        $category1->brands()->attach($brand->id);
        $category2->brands()->attach($brand->id);

        $subcategory1 = Subcategory::factory()->create([
            'name' => 'Celulares y smartphones',
            'category_id' => $category1->id,
        ]);

        $subcategory2 = Subcategory::factory()->create([
            'name' => 'Accesorios para celulares',
            'category_id' => $category2->id,
        ]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class,
        ]);

        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($category1, $product1, $product2) {
            $browser->visit('/categories/' . $category1->slug)
                ->pause(1000)
                ->assertSee(strtoupper($category1->name))
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
        $brand = Brand::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($category, $product) {
            $browser->visit('/categories/' . $category->slug)
                ->pause(1000)
                ->assertSee(strtoupper($category->name))
                ->assertSee("Subcategorías")
                ->click('@product')
                ->pause(500)
                ->assertPathIs('/products/' . $product->slug)
                ->assertSee(ucfirst($product->name))
                ->pause(500)
                ->assertSee($product->price)
                ->assertSee($product->quantity)
                ->pause(500)
                ->assertVisible('@button_less')
                ->assertVisible('@button_more')
                ->assertVisible('@add-cart')
                ->screenshot('product_details');
        });
    }

    /** @test */
    public function the_button_to_add_more_quantity_of_product_must_be_limited()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'quantity' => 5,
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(1000)
                ->assertSee(ucfirst($product->name));
            for ($i = 1; $i < $product->quantity; $i++) {
                $browser->press('@button_more');
            };
            $browser->pause(500);
            $browser->assertButtonDisabled('@button_more');
            $browser->screenshot('add_more');
        });
    }

    /** @test */
    public function the_button_to_less_more_quantity_of_product_must_be_limited()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'quantity' => 5,
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(1000)
                ->assertSee(ucfirst($product->name));
            for ($i = $product->quantity; $i > 1; $i--) {
                $browser->press('@button_less');
            };
            $browser->pause(500);
            $browser->assertButtonDisabled('@button_less');
            $browser->screenshot('less_more');
        });
    }

    /** @test */
    public function it_shows_the_color_and_size_dropdowns_depending_on_the_selected_product()
    {
        $brand = Brand::factory()->create();

        $category1 = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category2 = Category::factory()->create([
            'name' => 'TV, audio y video',
        ]);

        $category3 = Category::factory()->create([
            'name' => 'Moda',
        ]);

        $category1->brands()->attach($brand->id);
        $category2->brands()->attach($brand->id);
        $category3->brands()->attach($brand->id);

        $subcategory1 = Subcategory::factory()->create([
            'category_id' => $category1->id,
            'color' => true,
            'size' => false
        ]);

        $subcategory2 = Subcategory::factory()->create([
            'category_id' => $category2->id,
            'color' => false,
            'size' => false
        ]);

        $subcategory3 = Subcategory::factory()->create([
            'category_id' => $category3->id,
            'color' => true,
            'size' => true
        ]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'quantity' => 5,
        ]);

        Image::factory()->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class,
        ]);

        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
            'quantity' => 5,
        ]);

        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class,
        ]);

        $product3 = Product::factory()->create([
            'subcategory_id' => $subcategory3->id,
            'quantity' => 5,
        ]);

        Image::factory()->create([
            'imageable_id' => $product3->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($product1, $product2, $product3) {
            $browser->pause(1000);
            if ($browser->visit('/products/' . $product1->slug)) {
                $browser->pause(500);
                $browser->assertPresent('@color');
                $browser->assertNotPresent('@size');
                $browser->screenshot('product_with_color');
            };
            $browser->pause(1000);
            if ($browser->visit('/products/' . $product2->slug)) {
                $browser->pause(500);
                $browser->assertNotPresent('@color');
                $browser->assertNotPresent('@size');
                $browser->screenshot('product_without_color_size');
            };
            $browser->pause(1000);
            if ($browser->visit('/products/' . $product3->slug)) {
                $browser->pause(500);
                $browser->assertPresent('@color');
                $browser->assertPresent('@size');
                $browser->screenshot('product_with_color_size');
            };
        });
    }
}
