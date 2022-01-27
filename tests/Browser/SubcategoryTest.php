<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategoryTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_the_subcategories()
    {
        $category = Category::factory()->create();
        $category2 = Category::factory()->create();

        Subcategory::factory()->create([
            'name' => 'Celulares y smartphones',
            'category_id'=> $category->id,
        ]);

        Subcategory::factory()->create([
            'name' => 'TV y audio',
            'category_id'=> $category2->id,
        ]);

        $this->browse(function (Browser $browser) use ($category) {
            $browser->visit('/')
                ->pause(500)
                ->clickLink('Categorías')
                ->pause(500)
                ->assertDontSee('TV y audio')
                ->mouseover('@categories')
                ->pause(1000)
                ->assertSee('Celulares y smartphones');
        });
    }
}
