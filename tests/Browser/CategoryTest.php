<?php

namespace Tests\Browser;

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

        $category = Category::factory()->create([
            'name' => 'Celulares y tablets',
        ]);

        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'name' => 'Celulares y smartphones',
            'category_id' => $category->id,
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ]);

        $this->browse(function (Browser $browser) use ($brand, $category, $subcategory, $product) {
            $browser->visit('/')
                ->pause(1000)
                ->assertSee(strtoupper($category->name))
                ->click('@show_more')
                ->assertPathIs('/categories/' . $category->slug)
                ->assertSee(strtoupper($category->name))
                ->assertSee("Subcategorías")
                ->assertSee(mb_convert_case($subcategory->name, MB_CASE_TITLE))
                ->assertSee('Marcas')
                ->assertSee(ucfirst($brand->name))
                ->assertSee($product->name);
        });
    }
}
