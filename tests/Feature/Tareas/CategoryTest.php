<?php

namespace Tests\Feature\Tareas;

use App\CreateData;
use Tests\TestCase;
use App\Models\Category;
use App\Http\Livewire\Navigation;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use CreateData;

    //Test ya modificado en una única línea
    /** @test */
    public function it_shows_the_categories()
    {
        $this->createCategory();

        Livewire::test(Navigation::class)
            ->assertStatus(200)
            ->assertSee('TV, audio y video');
    }
}
