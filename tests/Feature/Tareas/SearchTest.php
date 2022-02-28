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
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(Search::class)
            ->assertSet('search', $product1->name)
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
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
