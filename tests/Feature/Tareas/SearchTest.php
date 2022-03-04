<?php

namespace Tests\Feature\Tareas;

use App\CreateData;
use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Search;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Brand, Image, Product, Category, Subcategory};

class SearchTest extends TestCase
{
    use RefreshDatabase;
    use CreateData;

    /** @test */
    public function can_be_filter_by_name()
    {
        $this->createProductName('Teclado');
        $this->createProductName('Teléfono');

        Livewire::test(Search::class)
            ->set('search', 'Tecl')
            ->assertSee('Teclado')
            ->assertDontSee('Teléfono');
    }

    //Test ya modificado con una única línea
    /** @test */
    public function dont_show_any_product_if_nothing_is_written_in_the_search_input()
    {
        $this->createProductName('Teclado');

        $this->get('/');

        Livewire::test(Search::class)
            ->set('search', '')
            ->assertDontSee('Teclado');
    }
}
