<?php

namespace Tests\Feature\Tareas;

use Tests\TestCase;
use App\Models\Category;
use App\Http\Livewire\Navigation;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_categories()
    {
        Category::factory()->create([
            'name' => 'TV, audio y video',
        ]);

        Livewire::test(Navigation::class)
            ->assertStatus(200)
            ->assertSee('TV, audio y video');
    }
}
