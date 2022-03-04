<?php

namespace Tests\Browser\Tareas;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{Brand, Category, Image, Product, Subcategory};

class CategoryTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_the_details_of_category()
    {
        $brand = Brand::factory()->create();
        $brand2 = Brand::factory()->create();

        $category = Category::factory()->create();

        $category2 = Category::factory()->create();

        $category->brands()->attach($brand->id);
        $category2->brands()->attach($brand2->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $subcategory2 = Subcategory::factory()->create([
            'category_id' => $category2->id,
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);


        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
        ]);


        $this->browse(function (Browser $browser) use ($brand, $category, $subcategory, $product, $brand2, $category2, $subcategory2, $product2) {
            $browser->visit('/')
                ->pause(1000)
                ->assertSee(strtoupper($category->name))
                ->assertSee(strtoupper($category2->name))
                ->click('@show_more')
                ->assertPathIs('/categories/' . $category->slug)
                ->pause(1000)
                ->assertSee(strtoupper($category->name))
                ->assertDontSee(strtoupper($category2->name))
                ->assertSee("Subcategorías")
                ->assertSee(mb_convert_case($subcategory->name, MB_CASE_TITLE))
                ->assertDontSee(mb_convert_case($subcategory2->name, MB_CASE_TITLE))
                ->pause(500)
                ->assertSee('Marcas')
                ->assertSee(ucfirst($brand->name))
                ->assertDontSee(ucfirst($brand2->name))
                ->pause(500)
                ->assertSee($product->name)
                ->assertDontSee($product2->name);
        });
    }
}
