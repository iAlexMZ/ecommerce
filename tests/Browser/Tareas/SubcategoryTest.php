<?php

namespace Tests\Browser\Tareas;

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
        $subcategory1 = Subcategory::factory()->create([
            'category_id'=> Category::factory()->create(),
        ]);

        $subcategory2 = Subcategory::factory()->create([
            'category_id'=> Category::factory()->create(),
        ]);

        $this->browse(function (Browser $browser) use ($subcategory1, $subcategory2) {
            $browser->visit('/')
                ->pause(500)
                ->clickLink('Categorías')
                ->pause(500)
                ->mouseover('@categories')
                ->pause(1000)
                ->assertSee($subcategory1->name)
                ->assertDontSee($subcategory2->name)
                ->screenshot('aaa');
        });
    }
}
