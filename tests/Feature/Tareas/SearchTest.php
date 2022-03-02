<?php

namespace Tests\Feature\Tareas;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Search;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Brand, Image, Product, Category, Subcategory};

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_be_filter_by_name()
    {
        $this->createProduct('Teclado');
        $this->createProduct('Teléfono');

        Livewire::test(Search::class)
            ->set('search', 'Tecl')
            ->assertSee('Teclado')
            ->assertDontSee('Teléfono');
    }

    /** @test */
    public function dont_show_any_product_if_nothing_is_written_in_the_search_input()
    {
        $this->createProduct('Teclado');
        $this->get('/');

        Livewire::test(Search::class)
            ->set('search', '')
            ->assertDontSee('Teclado');
    }






    public function createProduct($name)
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $brand = Brand::factory()->create();
        $category->brands()->attach([$brand->id]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'name' => $name,
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        return $product;
    }
}
