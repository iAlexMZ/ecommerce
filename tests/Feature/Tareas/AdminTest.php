<?php

namespace Tests\Feature\Tareas;

use Tests\TestCase;
use App\Models\{User, Brand, Image, Product, Category, Subcategory};
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unlogged_user_cant_access_to_admin_view()
    {
        $this->get('/admin')->assertStatus(302)->assertRedirect('/login');
    }

    /** @test */
    public function only_user_admin_can_access_to_admin_view()
    {
        Role::create(['name' => 'admin']);

        $adminUser = User::factory()->create()->assignRole('admin');
        $normalUser = User::factory()->create();

        $this->actingAs($adminUser)->get('/admin')->assertStatus(200);
        $this->actingAs($normalUser)->get('/admin')->assertStatus(403);
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
