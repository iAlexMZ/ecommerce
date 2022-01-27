<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Category;
use App\Http\Livewire\Navigation;

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
