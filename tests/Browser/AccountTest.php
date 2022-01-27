<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Category;
use App\Models\Subcategory;

class AccountTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_the_options_login_when_logout()
    {
        Category::factory()->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->pause(500)
                ->assertSee('Categorías')
                ->click('i')
                ->pause(500)
                ->assertSee('Iniciar sesión')
                ->assertSee('Registrarse');
        });
    }

    /** @test */
    public function it_shows_the_account_options()
    {
        Category::factory()->create();
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->pause(500)
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('@button')
                ->assertPathIs('/')
                ->click('@profile_image')
                ->pause(500)
                ->screenshot('account')
                ->assertSee('Perfil')
                ->assertSee('Finalizar sesión')
                ->pause(500);
        });
    }
}
