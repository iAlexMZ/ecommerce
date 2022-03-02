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
            'name' => $name,
            'brand_id' => $brand->id,
        ]);
        
        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        return $product;
    }

}
